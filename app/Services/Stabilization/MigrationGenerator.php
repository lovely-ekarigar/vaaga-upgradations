<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrationGenerator
{
    /**
     * Generate migration to add missing columns
     * Creates properly named migration file with up/down methods
     * 
     * @param string $table Table name
     * @param array $columns Array of column definitions [['name' => 'status', 'type' => 'string', 'nullable' => true, ...], ...]
     * @return string Path to generated migration file
     */
    public function generateAddColumnMigration(string $table, array $columns): string
    {
        $timestamp = date('Y_m_d_His');
        $columnNames = implode('_and_', array_map(fn($col) => $col['name'] ?? $col, $columns));
        $className = 'Add' . Str::studly($columnNames) . 'To' . Str::studly($table) . 'Table';
        $fileName = "{$timestamp}_add_{$columnNames}_to_{$table}_table.php";
        
        // Check if file already exists and append suffix if needed
        $migrationPath = database_path('migrations');
        $fullPath = $migrationPath . '/' . $fileName;
        $suffix = 2;
        
        while (File::exists($fullPath)) {
            $fileName = "{$timestamp}_add_{$columnNames}_to_{$table}_table_{$suffix}.php";
            $fullPath = $migrationPath . '/' . $fileName;
            $suffix++;
        }
        
        $content = $this->generateAddColumnMigrationContent($className, $table, $columns);
        
        File::put($fullPath, $content);
        
        return $fullPath;
    }

    /**
     * Generate migrations for all missing columns (table => [column names]).
     * @param array $missingColumns e.g. ['users' => ['status', 'role_id']]
     * @return array Paths to generated migration files
     */
    public function generateForMissingColumns(array $missingColumns): array
    {
        $paths = [];
        foreach ($missingColumns as $table => $columnNames) {
            $columns = array_map(fn($name) => ['name' => $name, 'type' => 'string', 'nullable' => true], $columnNames);
            $paths[] = $this->generateAddColumnMigration($table, $columns);
        }
        return $paths;
    }
    
    /**
     * Generate the content for add column migration
     */
    protected function generateAddColumnMigrationContent(string $className, string $table, array $columns): string
    {
        $upMethods = [];
        $downMethods = [];
        
        foreach ($columns as $column) {
            // Handle both array format and string format
            if (is_string($column)) {
                $columnName = $column;
                $columnType = 'string';
                $nullable = false;
                $default = null;
                $after = null;
            } else {
                $columnName = $column['name'];
                $columnType = $column['type'] ?? 'string';
                $nullable = $column['nullable'] ?? false;
                $default = $column['default'] ?? null;
                $after = $column['after'] ?? null;
            }
            
            $method = "\$table->{$columnType}('{$columnName}')";
            
            if ($nullable) {
                $method .= "->nullable()";
            }
            
            if ($default !== null) {
                $defaultValue = is_string($default) ? "'{$default}'" : $default;
                $method .= "->default({$defaultValue})";
            }
            
            if ($after !== null) {
                $method .= "->after('{$after}')";
            }
            
            $method .= ";";
            
            $upMethods[] = "            {$method}";
            $downMethods[] = "            \$table->dropColumn('{$columnName}');";
        }
        
        $upMethodsStr = implode("\n", $upMethods);
        $downMethodsStr = implode("\n", $downMethods);
        
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$className} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('{$table}', function (Blueprint \$table) {
{$upMethodsStr}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('{$table}', function (Blueprint \$table) {
{$downMethodsStr}
        });
    }
}

PHP;
    }
    
    /**
     * Generate migration to fix foreign key constraint
     * Handles dropping invalid constraint and creating correct one
     * 
     * @param string $table Table containing the foreign key
     * @param string $foreignKey Foreign key column name
     * @param string $referencedTable Referenced table name
     * @param string $referencedColumn Referenced column name
     * @param string|null $constraintName Existing constraint name to drop (optional)
     * @param string $onDelete On delete action (cascade, set null, restrict, no action)
     * @param string $onUpdate On update action (cascade, set null, restrict, no action)
     * @return string Path to generated migration file
     */
    public function generateFixForeignKeyMigration(
        string $table,
        string $foreignKey,
        string $referencedTable,
        string $referencedColumn,
        ?string $constraintName = null,
        string $onDelete = 'cascade',
        string $onUpdate = 'cascade'
    ): string {
        $timestamp = date('Y_m_d_His');
        $className = 'Fix' . Str::studly($foreignKey) . 'ForeignKeyOn' . Str::studly($table) . 'Table';
        $fileName = "{$timestamp}_fix_{$foreignKey}_foreign_key_on_{$table}_table.php";
        
        // Check if file already exists and append suffix if needed
        $migrationPath = database_path('migrations');
        $fullPath = $migrationPath . '/' . $fileName;
        $suffix = 2;
        
        while (File::exists($fullPath)) {
            $fileName = "{$timestamp}_fix_{$foreignKey}_foreign_key_on_{$table}_table_{$suffix}.php";
            $fullPath = $migrationPath . '/' . $fileName;
            $suffix++;
        }
        
        $content = $this->generateFixForeignKeyMigrationContent(
            $className,
            $table,
            $foreignKey,
            $referencedTable,
            $referencedColumn,
            $constraintName,
            $onDelete,
            $onUpdate
        );
        
        File::put($fullPath, $content);
        
        return $fullPath;
    }
    
    /**
     * Generate the content for fix foreign key migration
     */
    protected function generateFixForeignKeyMigrationContent(
        string $className,
        string $table,
        string $foreignKey,
        string $referencedTable,
        string $referencedColumn,
        ?string $constraintName,
        string $onDelete,
        string $onUpdate
    ): string {
        $dropConstraintCode = '';
        
        if ($constraintName) {
            $dropConstraintCode = "\n            // Drop invalid constraint\n";
            $dropConstraintCode .= "            \$table->dropForeign('{$constraintName}');";
        } else {
            $dropConstraintCode = "\n            // Drop constraint by column name if exists\n";
            $dropConstraintCode .= "            \$table->dropForeign(['{$foreignKey}']);";
        }
        
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$className} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('{$table}', function (Blueprint \$table) {{$dropConstraintCode}
            
            // Add correct foreign key constraint
            \$table->foreign('{$foreignKey}')
                  ->references('{$referencedColumn}')
                  ->on('{$referencedTable}')
                  ->onDelete('{$onDelete}')
                  ->onUpdate('{$onUpdate}');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('{$table}', function (Blueprint \$table) {
            // Drop the foreign key constraint
            \$table->dropForeign(['{$foreignKey}']);
        });
    }
}

PHP;
    }
    
    /**
     * Generate migration to create missing pivot table
     * Creates table with proper foreign keys and indexes
     * 
     * @param string $table1 First table name
     * @param string $table2 Second table name
     * @param array $additionalColumns Additional columns to add to pivot table
     * @return string Path to generated migration file
     */
    public function generatePivotTableMigration(
        string $table1,
        string $table2,
        array $additionalColumns = []
    ): string {
        // Laravel convention: alphabetically ordered table names
        $tables = [$table1, $table2];
        sort($tables);
        $pivotTable = Str::singular($tables[0]) . '_' . Str::singular($tables[1]);
        
        $timestamp = date('Y_m_d_His');
        $className = 'Create' . Str::studly($pivotTable) . 'Table';
        $fileName = "{$timestamp}_create_{$pivotTable}_table.php";
        
        // Check if file already exists and append suffix if needed
        $migrationPath = database_path('migrations');
        $fullPath = $migrationPath . '/' . $fileName;
        $suffix = 2;
        
        while (File::exists($fullPath)) {
            $fileName = "{$timestamp}_create_{$pivotTable}_table_{$suffix}.php";
            $fullPath = $migrationPath . '/' . $fileName;
            $suffix++;
        }
        
        $content = $this->generatePivotTableMigrationContent(
            $className,
            $pivotTable,
            $table1,
            $table2,
            $additionalColumns
        );
        
        File::put($fullPath, $content);
        
        return $fullPath;
    }
    
    /**
     * Generate the content for pivot table migration
     */
    protected function generatePivotTableMigrationContent(
        string $className,
        string $pivotTable,
        string $table1,
        string $table2,
        array $additionalColumns
    ): string {
        $foreignKey1 = Str::singular($table1) . '_id';
        $foreignKey2 = Str::singular($table2) . '_id';
        
        $additionalColumnsCode = '';
        if (!empty($additionalColumns)) {
            $additionalColumnsCode = "\n            // Additional columns\n";
            foreach ($additionalColumns as $column) {
                $columnName = is_string($column) ? $column : $column['name'];
                $columnType = is_string($column) ? 'string' : ($column['type'] ?? 'string');
                $nullable = is_string($column) ? false : ($column['nullable'] ?? false);
                
                $method = "\$table->{$columnType}('{$columnName}')";
                if ($nullable) {
                    $method .= "->nullable()";
                }
                $method .= ";";
                
                $additionalColumnsCode .= "            {$method}\n";
            }
        }
        
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$className} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{$pivotTable}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$foreignKey1}');
            \$table->unsignedBigInteger('{$foreignKey2}');{$additionalColumnsCode}
            \$table->timestamps();
            
            // Foreign key constraints
            \$table->foreign('{$foreignKey1}')
                  ->references('id')
                  ->on('{$table1}')
                  ->onDelete('cascade');
            
            \$table->foreign('{$foreignKey2}')
                  ->references('id')
                  ->on('{$table2}')
                  ->onDelete('cascade');
            
            // Composite primary key
            \$table->primary(['{$foreignKey1}', '{$foreignKey2}']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{$pivotTable}');
    }
}

PHP;
    }
    
    /**
     * Map common column types to Laravel Schema builder methods
     */
    protected function mapColumnType(string $type): string
    {
        $typeMap = [
            'varchar' => 'string',
            'int' => 'integer',
            'tinyint' => 'boolean',
            'datetime' => 'dateTime',
            'timestamp' => 'timestamp',
            'text' => 'text',
            'longtext' => 'longText',
            'mediumtext' => 'mediumText',
            'decimal' => 'decimal',
            'float' => 'float',
            'double' => 'double',
            'date' => 'date',
            'time' => 'time',
            'json' => 'json',
            'binary' => 'binary',
            'enum' => 'enum',
        ];
        
        return $typeMap[strtolower($type)] ?? 'string';
    }
}

