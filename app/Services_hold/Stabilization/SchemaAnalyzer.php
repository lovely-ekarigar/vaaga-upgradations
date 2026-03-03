<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;

class SchemaAnalyzer
{
    /**
     * Analyze database schema for missing columns
     * Returns array of [table => [missing_columns]]
     */
    public function findMissingColumns(): array
    {
        $missingColumns = [];
        $models = $this->getAllModels();

        foreach ($models as $modelClass) {
            try {
                $reflection = new ReflectionClass($modelClass);
                $instance = $reflection->newInstanceWithoutConstructor();
                $table = $instance->getTable();
                
                // Get columns referenced in model
                $referencedColumns = $this->extractModelColumnReferences($modelClass);
                
                // Get actual database columns
                $actualColumns = $this->getDatabaseColumns($table);
                
                // Find missing columns
                $missing = array_diff($referencedColumns, $actualColumns);
                
                if (!empty($missing)) {
                    $missingColumns[$table] = array_values($missing);
                }
            } catch (\Exception $e) {
                // Skip models that can't be instantiated or analyzed
                continue;
            }
        }

        return $missingColumns;
    }

    /**
     * Analyze foreign key constraints for validity
     * Returns array of invalid constraints with details
     */
    public function findInvalidForeignKeys(): array
    {
        $invalidConstraints = [];
        $database = DB::getDatabaseName();
        
        // Check if we're using MySQL
        $driver = DB::getDriverName();
        if ($driver !== 'mysql') {
            // Skip foreign key validation for non-MySQL databases
            return $invalidConstraints;
        }

        try {
            // Get all foreign key constraints
            $constraints = DB::select("
                SELECT 
                    TABLE_NAME,
                    CONSTRAINT_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$database]);

            foreach ($constraints as $constraint) {
                $issues = [];

                // Check if referenced table exists
                if (!$this->tableExists($constraint->REFERENCED_TABLE_NAME)) {
                    $issues[] = "Referenced table '{$constraint->REFERENCED_TABLE_NAME}' does not exist";
                } else {
                    // Check if referenced column exists
                    $referencedColumns = $this->getDatabaseColumns($constraint->REFERENCED_TABLE_NAME);
                    if (!in_array($constraint->REFERENCED_COLUMN_NAME, $referencedColumns)) {
                        $issues[] = "Referenced column '{$constraint->REFERENCED_COLUMN_NAME}' does not exist in table '{$constraint->REFERENCED_TABLE_NAME}'";
                    }
                }

                // Check if source column exists
                $sourceColumns = $this->getDatabaseColumns($constraint->TABLE_NAME);
                if (!in_array($constraint->COLUMN_NAME, $sourceColumns)) {
                    $issues[] = "Source column '{$constraint->COLUMN_NAME}' does not exist in table '{$constraint->TABLE_NAME}'";
                }

                if (!empty($issues)) {
                    $invalidConstraints[] = [
                        'table' => $constraint->TABLE_NAME,
                        'constraint_name' => $constraint->CONSTRAINT_NAME,
                        'column' => $constraint->COLUMN_NAME,
                        'referenced_table' => $constraint->REFERENCED_TABLE_NAME,
                        'referenced_column' => $constraint->REFERENCED_COLUMN_NAME,
                        'issues' => $issues
                    ];
                }
            }
        } catch (\Exception $e) {
            // Return empty array if query fails
            return [];
        }

        return $invalidConstraints;
    }

    /**
     * Compare migration files with actual database state
     * Returns array of schema mismatches
     */
    public function findSchemaMismatches(): array
    {
        $mismatches = [];
        $migrations = $this->parseMigrationFiles();

        foreach ($migrations as $migration) {
            foreach ($migration['tables'] as $table => $expectedColumns) {
                if (!$this->tableExists($table)) {
                    $mismatches[] = [
                        'type' => 'missing_table',
                        'table' => $table,
                        'migration' => $migration['file'],
                        'message' => "Table '{$table}' defined in migration but does not exist in database"
                    ];
                    continue;
                }

                $actualColumns = $this->getDatabaseColumns($table);
                $missing = array_diff($expectedColumns, $actualColumns);

                if (!empty($missing)) {
                    $mismatches[] = [
                        'type' => 'missing_columns',
                        'table' => $table,
                        'migration' => $migration['file'],
                        'columns' => array_values($missing),
                        'message' => "Columns defined in migration but missing from table '{$table}'"
                    ];
                }
            }
        }

        return $mismatches;
    }

    /**
     * Extract column references from Eloquent models
     * Returns array of [model => [referenced_columns]]
     */
    public function extractModelColumnReferences(): array
    {
        $references = [];
        $models = $this->getAllModels();

        foreach ($models as $modelClass) {
            try {
                $columns = $this->extractColumnsFromModel($modelClass);
                if (!empty($columns)) {
                    $references[$modelClass] = $columns;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $references;
    }

    /**
     * Get all Eloquent model classes
     */
    protected function getAllModels(): array
    {
        $models = [];
        $modelsPath = app_path('Models');

        if (!File::exists($modelsPath)) {
            return $models;
        }

        $files = File::allFiles($modelsPath);

        foreach ($files as $file) {
            $relativePath = str_replace($modelsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (class_exists($className)) {
                try {
                    $reflection = new ReflectionClass($className);
                    if (!$reflection->isAbstract() && $reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                        $models[] = $className;
                    }
                } catch (\Exception $e) {
                    // Skip models that can't be reflected
                    continue;
                }
            }
        }

        return $models;
    }

    /**
     * Extract columns from a single model
     */
    protected function extractColumnsFromModel(string $modelClass): array
    {
        $columns = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            $instance = $reflection->newInstanceWithoutConstructor();

            // Get fillable columns
            if ($reflection->hasProperty('fillable')) {
                $fillableProperty = $reflection->getProperty('fillable');
                $fillableProperty->setAccessible(true);
                $fillable = $fillableProperty->getValue($instance) ?? [];
                $columns = array_merge($columns, $fillable);
            }

            // Get casts columns
            if ($reflection->hasProperty('casts')) {
                $castsProperty = $reflection->getProperty('casts');
                $castsProperty->setAccessible(true);
                $casts = $castsProperty->getValue($instance) ?? [];
                $columns = array_merge($columns, array_keys($casts));
            }

            // Get columns from accessors (getXxxAttribute methods)
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $methodName = $method->getName();
                if (preg_match('/^get(.+)Attribute$/', $methodName, $matches)) {
                    $columnName = snake_case($matches[1]);
                    $columns[] = $columnName;
                }
            }

            // Remove duplicates and standard timestamps
            $columns = array_unique($columns);
            $columns = array_diff($columns, ['id', 'created_at', 'updated_at', 'deleted_at']);

            return array_values($columns);
        } catch (\Exception $e) {
            // Return empty array if model can't be analyzed
            return [];
        }
    }

    /**
     * Get actual database columns for a table
     */
    protected function getDatabaseColumns(string $table): array
    {
        try {
            $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
            return array_map(fn($col) => $col->Field, $columns);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if a table exists in the database
     */
    protected function tableExists(string $table): bool
    {
        try {
            return DB::select("SHOW TABLES LIKE '{$table}'") !== [];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Parse migration files to extract table and column definitions
     */
    protected function parseMigrationFiles(): array
    {
        $migrations = [];
        $migrationsPath = database_path('migrations');

        if (!File::exists($migrationsPath)) {
            return $migrations;
        }

        $files = File::files($migrationsPath);

        foreach ($files as $file) {
            $content = File::get($file->getPathname());
            $tables = $this->extractTablesFromMigration($content);

            if (!empty($tables)) {
                $migrations[] = [
                    'file' => $file->getFilename(),
                    'tables' => $tables
                ];
            }
        }

        return $migrations;
    }

    /**
     * Extract table and column definitions from migration content
     */
    protected function extractTablesFromMigration(string $content): array
    {
        $tables = [];

        // Match Schema::create and Schema::table calls
        preg_match_all('/Schema::(create|table)\([\'"](\w+)[\'"]/', $content, $matches);

        foreach ($matches[2] as $table) {
            // Extract column definitions for this table
            $pattern = '/Schema::(create|table)\([\'"]' . preg_quote($table) . '[\'"].*?\{(.*?)\}\);/s';
            if (preg_match($pattern, $content, $tableMatch)) {
                $columns = $this->extractColumnsFromSchemaBlock($tableMatch[2]);
                $tables[$table] = $columns;
            }
        }

        return $tables;
    }

    /**
     * Extract column names from Schema builder block
     */
    protected function extractColumnsFromSchemaBlock(string $block): array
    {
        $columns = [];

        // Match column definitions like $table->string('name')
        preg_match_all('/\$table->\w+\([\'"](\w+)[\'"]/', $block, $matches);

        foreach ($matches[1] as $column) {
            $columns[] = $column;
        }

        return array_unique($columns);
    }
}
