<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class RelationshipValidator
{
    /**
     * Validate all relationships in a model
     * Returns array of validation errors
     * 
     * @param string $modelClass Fully qualified model class name
     * @return array Array of validation errors
     */
    public function validateModelRelationships(string $modelClass): array
    {
        $errors = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            
            // Skip abstract classes
            if ($reflection->isAbstract()) {
                return [];
            }
            
            // Get all relationship methods
            $relationships = $this->extractRelationshipMethods($modelClass);
            
            foreach ($relationships as $relationship) {
                $relationshipErrors = $this->validateRelationship($modelClass, $relationship);
                
                if (!empty($relationshipErrors)) {
                    $errors[] = [
                        'model' => $modelClass,
                        'relationship' => $relationship['method'],
                        'type' => $relationship['type'],
                        'errors' => $relationshipErrors
                    ];
                }
            }
        } catch (\Exception $e) {
            // Return empty array if model can't be analyzed
            return [];
        }
        
        return $errors;
    }
    
    /**
     * Extract relationship methods from a model
     * Returns array of relationship definitions
     * 
     * @param string $modelClass Fully qualified model class name
     * @return array Array of relationships with type and method name
     */
    protected function extractRelationshipMethods(string $modelClass): array
    {
        $relationships = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                // Skip magic methods, constructors, and inherited methods from Model
                if ($method->isStatic() || 
                    $method->isConstructor() || 
                    $method->class !== $modelClass ||
                    strpos($method->getName(), '__') === 0) {
                    continue;
                }
                
                // Parse method body to detect relationship types
                $relationshipType = $this->detectRelationshipType($method);
                
                if ($relationshipType) {
                    $relationships[] = [
                        'method' => $method->getName(),
                        'type' => $relationshipType,
                        'definition' => $this->parseRelationshipDefinition($method, $relationshipType)
                    ];
                }
            }
        } catch (\Exception $e) {
            // Return empty array if reflection fails
            return [];
        }
        
        return $relationships;
    }
    
    /**
     * Detect relationship type from method
     * 
     * @param ReflectionMethod $method
     * @return string|null Relationship type or null if not a relationship
     */
    protected function detectRelationshipType(ReflectionMethod $method): ?string
    {
        try {
            $fileName = $method->getFileName();
            $startLine = $method->getStartLine();
            $endLine = $method->getEndLine();
            
            if (!$fileName || !$startLine || !$endLine) {
                return null;
            }
            
            $fileContent = file($fileName);
            $methodBody = implode('', array_slice($fileContent, $startLine - 1, $endLine - $startLine + 1));
            
            // Check for relationship method calls
            $relationshipTypes = [
                'belongsTo',
                'hasOne',
                'hasMany',
                'belongsToMany',
                'hasOneThrough',
                'hasManyThrough',
                'morphOne',
                'morphMany',
                'morphTo',
                'morphToMany',
                'morphedByMany'
            ];
            
            foreach ($relationshipTypes as $type) {
                if (preg_match('/\$this->' . preg_quote($type) . '\s*\(/', $methodBody)) {
                    return $type;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Parse relationship definition from method body
     * 
     * @param ReflectionMethod $method
     * @param string $relationshipType
     * @return array Parsed relationship details
     */
    protected function parseRelationshipDefinition(ReflectionMethod $method, string $relationshipType): array
    {
        $definition = [
            'related_model' => null,
            'foreign_key' => null,
            'owner_key' => null,
            'pivot_table' => null
        ];
        
        try {
            $fileName = $method->getFileName();
            $startLine = $method->getStartLine();
            $endLine = $method->getEndLine();
            
            if (!$fileName || !$startLine || !$endLine) {
                return $definition;
            }
            
            $fileContent = file($fileName);
            $methodBody = implode('', array_slice($fileContent, $startLine - 1, $endLine - $startLine + 1));
            
            // Extract related model class
            $pattern = '/\$this->' . preg_quote($relationshipType) . '\s*\(\s*([^,\)]+)/';
            if (preg_match($pattern, $methodBody, $matches)) {
                $relatedModel = trim($matches[1]);
                // Remove quotes and resolve class name
                $relatedModel = trim($relatedModel, '\'"');
                
                // Handle ::class syntax
                if (strpos($relatedModel, '::class') !== false) {
                    $relatedModel = str_replace('::class', '', $relatedModel);
                }
                
                $definition['related_model'] = $relatedModel;
            }
            
            // Extract foreign key (second parameter)
            $pattern = '/\$this->' . preg_quote($relationshipType) . '\s*\([^,]+,\s*[\'"]([^\'"]+)[\'"]/';
            if (preg_match($pattern, $methodBody, $matches)) {
                $definition['foreign_key'] = $matches[1];
            }
            
            // Extract owner key (third parameter)
            $pattern = '/\$this->' . preg_quote($relationshipType) . '\s*\([^,]+,\s*[^,]+,\s*[\'"]([^\'"]+)[\'"]/';
            if (preg_match($pattern, $methodBody, $matches)) {
                $definition['owner_key'] = $matches[1];
            }
            
        } catch (\Exception $e) {
            // Return partial definition if parsing fails
        }
        
        return $definition;
    }
    
    /**
     * Validate a specific relationship
     * 
     * @param string $modelClass
     * @param array $relationship
     * @return array Validation errors
     */
    protected function validateRelationship(string $modelClass, array $relationship): array
    {
        $errors = [];
        
        switch ($relationship['type']) {
            case 'belongsTo':
                $errors = $this->validateBelongsTo($modelClass, $relationship);
                break;
                
            case 'hasOne':
            case 'hasMany':
                $errors = $this->validateHasRelationship($modelClass, $relationship);
                break;
                
            case 'belongsToMany':
                $errors = $this->validateBelongsToMany($modelClass, $relationship);
                break;
                
            // Other relationship types can be added here
            default:
                // Skip validation for unsupported relationship types
                break;
        }
        
        return $errors;
    }
    
    /**
     * Check if belongsTo relationship has valid foreign key
     * 
     * @param string $modelClass
     * @param array $relationship
     * @return array Validation errors
     */
    public function validateBelongsTo(string $modelClass, array $relationship): array
    {
        $errors = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            $instance = $reflection->newInstanceWithoutConstructor();
            $table = $instance->getTable();
            
            // Determine foreign key name
            $foreignKey = $relationship['definition']['foreign_key'];
            
            if (!$foreignKey) {
                // Use Laravel convention: relationship_method_name + _id
                $foreignKey = Str::snake($relationship['method']) . '_id';
            }
            
            // Check if foreign key column exists on model's table
            $columns = $this->getDatabaseColumns($table);
            
            if (!in_array($foreignKey, $columns)) {
                $errors[] = "Foreign key column '{$foreignKey}' does not exist on table '{$table}'";
            } else {
                // Check if foreign key constraint is properly defined
                $hasConstraint = $this->checkForeignKeyConstraint($table, $foreignKey);
                
                if (!$hasConstraint) {
                    $errors[] = "Foreign key constraint for '{$foreignKey}' is not defined on table '{$table}'";
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to validate belongsTo relationship: " . $e->getMessage();
        }
        
        return $errors;
    }
    
    /**
     * Check if hasMany/hasOne relationship has valid foreign key on related table
     * 
     * @param string $modelClass
     * @param array $relationship
     * @return array Validation errors
     */
    public function validateHasRelationship(string $modelClass, array $relationship): array
    {
        $errors = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            $instance = $reflection->newInstanceWithoutConstructor();
            $table = $instance->getTable();
            
            // Get related model
            $relatedModel = $relationship['definition']['related_model'];
            
            if (!$relatedModel) {
                $errors[] = "Could not determine related model for relationship '{$relationship['method']}'";
                return $errors;
            }
            
            // Resolve full class name if needed
            $relatedModel = $this->resolveModelClass($relatedModel, $modelClass);
            
            // Get table name safely
            $relatedTable = $this->getTableFromModel($relatedModel);
            
            if (!$relatedTable) {
                // Skip validation if related model is not an Eloquent model
                return [];
            }
            
            // Determine foreign key name
            $foreignKey = $relationship['definition']['foreign_key'];
            
            if (!$foreignKey) {
                // Use Laravel convention: parent_table_singular + _id
                $foreignKey = Str::singular($table) . '_id';
            }
            
            // Check if foreign key exists on related table
            $relatedColumns = $this->getDatabaseColumns($relatedTable);
            
            if (!in_array($foreignKey, $relatedColumns)) {
                $errors[] = "Foreign key column '{$foreignKey}' does not exist on related table '{$relatedTable}'";
            } else {
                // Check if foreign key constraint points to correct table
                $hasConstraint = $this->checkForeignKeyConstraint($relatedTable, $foreignKey, $table);
                
                if (!$hasConstraint) {
                    $errors[] = "Foreign key constraint for '{$foreignKey}' on table '{$relatedTable}' does not reference '{$table}'";
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to validate has relationship: " . $e->getMessage();
        }
        
        return $errors;
    }
    
    /**
     * Check if belongsToMany relationship has valid pivot table
     * 
     * @param string $modelClass
     * @param array $relationship
     * @return array Validation errors
     */
    public function validateBelongsToMany(string $modelClass, array $relationship): array
    {
        $errors = [];
        
        try {
            $reflection = new ReflectionClass($modelClass);
            $instance = $reflection->newInstanceWithoutConstructor();
            $table = $instance->getTable();
            
            // Get related model
            $relatedModel = $relationship['definition']['related_model'];
            
            if (!$relatedModel) {
                $errors[] = "Could not determine related model for relationship '{$relationship['method']}'";
                return $errors;
            }
            
            // Resolve full class name if needed
            $relatedModel = $this->resolveModelClass($relatedModel, $modelClass);
            
            // Get table name safely
            $relatedTable = $this->getTableFromModel($relatedModel);
            
            if (!$relatedTable) {
                // Skip validation if related model is not an Eloquent model
                return [];
            }
            
            // Determine pivot table name
            $pivotTable = $relationship['definition']['pivot_table'];
            
            if (!$pivotTable) {
                // Use Laravel convention: alphabetically ordered singular table names
                $tables = [Str::singular($table), Str::singular($relatedTable)];
                sort($tables);
                $pivotTable = implode('_', $tables);
            }
            
            // Check if pivot table exists
            if (!$this->tableExists($pivotTable)) {
                $errors[] = "Pivot table '{$pivotTable}' does not exist";
                return $errors;
            }
            
            // Check pivot table has correct foreign key columns
            $pivotColumns = $this->getDatabaseColumns($pivotTable);
            
            $foreignKey1 = Str::singular($table) . '_id';
            $foreignKey2 = Str::singular($relatedTable) . '_id';
            
            if (!in_array($foreignKey1, $pivotColumns)) {
                $errors[] = "Pivot table '{$pivotTable}' is missing foreign key column '{$foreignKey1}'";
            }
            
            if (!in_array($foreignKey2, $pivotColumns)) {
                $errors[] = "Pivot table '{$pivotTable}' is missing foreign key column '{$foreignKey2}'";
            }
            
            // Validate pivot table constraints
            if (in_array($foreignKey1, $pivotColumns)) {
                $hasConstraint1 = $this->checkForeignKeyConstraint($pivotTable, $foreignKey1, $table);
                if (!$hasConstraint1) {
                    $errors[] = "Pivot table '{$pivotTable}' missing foreign key constraint for '{$foreignKey1}' referencing '{$table}'";
                }
            }
            
            if (in_array($foreignKey2, $pivotColumns)) {
                $hasConstraint2 = $this->checkForeignKeyConstraint($pivotTable, $foreignKey2, $relatedTable);
                if (!$hasConstraint2) {
                    $errors[] = "Pivot table '{$pivotTable}' missing foreign key constraint for '{$foreignKey2}' referencing '{$relatedTable}'";
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to validate belongsToMany relationship: " . $e->getMessage();
        }
        
        return $errors;
    }
    
    /**
     * Get actual database columns for a table
     * 
     * @param string $table
     * @return array
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
     * 
     * @param string $table
     * @return bool
     */
    protected function tableExists(string $table): bool
    {
        try {
            return !empty(DB::select("SHOW TABLES LIKE '{$table}'"));
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if foreign key constraint exists
     * 
     * @param string $table Table containing the foreign key
     * @param string $column Foreign key column name
     * @param string|null $referencedTable Expected referenced table (optional)
     * @return bool
     */
    protected function checkForeignKeyConstraint(string $table, string $column, ?string $referencedTable = null): bool
    {
        try {
            $database = DB::getDatabaseName();
            
            $query = "
                SELECT COUNT(*) as count
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ";
            
            $params = [$database, $table, $column];
            
            if ($referencedTable) {
                $query .= " AND REFERENCED_TABLE_NAME = ?";
                $params[] = $referencedTable;
            }
            
            $result = DB::select($query, $params);
            
            return $result[0]->count > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Resolve model class name to fully qualified class name
     * 
     * @param string $modelName
     * @param string $currentModelClass
     * @return string
     */
    protected function resolveModelClass(string $modelName, string $currentModelClass): string
    {
        // If already fully qualified, return as is
        if (strpos($modelName, '\\') !== false) {
            return $modelName;
        }
        
        // Try to resolve using current model's namespace
        $currentNamespace = substr($currentModelClass, 0, strrpos($currentModelClass, '\\'));
        $resolvedClass = $currentNamespace . '\\' . $modelName;
        
        if (class_exists($resolvedClass)) {
            return $resolvedClass;
        }
        
        // Try App\Models namespace
        $resolvedClass = 'App\\Models\\' . $modelName;
        if (class_exists($resolvedClass)) {
            return $resolvedClass;
        }
        
        // Return original if can't resolve
        return $modelName;
    }
    
    /**
     * Safely get table name from a model class
     * Returns null if the class is not an Eloquent model
     * 
     * @param string $modelClass
     * @return string|null
     */
    protected function getTableFromModel(string $modelClass): ?string
    {
        try {
            if (!class_exists($modelClass)) {
                return null;
            }
            
            $reflection = new ReflectionClass($modelClass);
            
            // Check if it's actually an Eloquent model
            if (!$reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                return null;
            }
            
            $instance = $reflection->newInstanceWithoutConstructor();
            return $instance->getTable();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get all Eloquent model classes
     * 
     * @return array
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
                    continue;
                }
            }
        }

        return $models;
    }
    
    /**
     * Validate all models in the application
     * Returns array of all validation errors grouped by model
     * 
     * @return array
     */
    public function validateAllModels(): array
    {
        $allErrors = [];
        $models = $this->getAllModels();
        
        foreach ($models as $modelClass) {
            $errors = $this->validateModelRelationships($modelClass);
            
            if (!empty($errors)) {
                $allErrors[$modelClass] = $errors;
            }
        }
        
        return $allErrors;
    }
}
