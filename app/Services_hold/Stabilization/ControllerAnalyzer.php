<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ControllerAnalyzer
{
    protected $parser;
    protected $schemaAnalyzer;
    
    public function __construct()
    {
        $this->parser = (new ParserFactory)->createForNewestSupportedVersion();
        $this->schemaAnalyzer = new SchemaAnalyzer();
    }
    
    /**
     * Find controller methods with incomplete implementations
     * Returns array of [controller => [methods]]
     * 
     * @return array
     */
    public function findIncompleteControllerMethods(): array
    {
        $incompleteMethod = [];
        $controllers = $this->getAllControllers();
        
        foreach ($controllers as $controllerPath) {
            try {
                $methods = $this->extractControllerMethods($controllerPath);
                $incomplete = [];
                
                foreach ($methods as $method) {
                    if ($this->isMethodIncomplete($method)) {
                        $incomplete[] = $method['name'];
                    }
                }
                
                if (!empty($incomplete)) {
                    $incompleteMethod[$controllerPath] = $incomplete;
                }
            } catch (\Exception $e) {
                // Skip controllers that can't be parsed
                continue;
            }
        }
        
        return $incompleteMethod;
    }
    
    /**
     * Find controller methods using invalid Eloquent queries
     * Returns array of methods with query issues
     * 
     * @return array
     */
    public function findInvalidQueries(): array
    {
        $invalidQueries = [];
        $controllers = $this->getAllControllers();
        
        foreach ($controllers as $controllerPath) {
            try {
                $queries = $this->extractEloquentQueries($controllerPath);
                
                foreach ($queries as $query) {
                    $issues = $this->validateQuery($query);
                    
                    if (!empty($issues)) {
                        $invalidQueries[] = [
                            'controller' => $controllerPath,
                            'method' => $query['method'],
                            'line' => $query['line'],
                            'query' => $query['code'],
                            'issues' => $issues
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Skip controllers that can't be parsed
                continue;
            }
        }
        
        return $invalidQueries;
    }
    
    /**
     * Find controller methods with improper response formats
     * Returns array of methods with response issues
     * 
     * @return array
     */
    public function findImproperResponses(): array
    {
        $improperResponses = [];
        $controllers = $this->getAllControllers();
        
        foreach ($controllers as $controllerPath) {
            try {
                $methods = $this->extractControllerMethods($controllerPath);
                
                foreach ($methods as $method) {
                    if ($this->hasImproperResponse($method)) {
                        $improperResponses[] = [
                            'controller' => $controllerPath,
                            'method' => $method['name'],
                            'line' => $method['line'],
                            'issue' => 'Response format does not follow standard structure'
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Skip controllers that can't be parsed
                continue;
            }
        }
        
        return $improperResponses;
    }
    
    /**
     * Find controller methods missing validation
     * Returns array of methods that should use Form Requests
     * 
     * @return array
     */
    public function findMissingValidation(): array
    {
        $missingValidation = [];
        $controllers = $this->getAllControllers();
        
        foreach ($controllers as $controllerPath) {
            try {
                $methods = $this->extractControllerMethods($controllerPath);
                
                foreach ($methods as $method) {
                    // Check if method is POST/PUT and lacks Form Request validation
                    if ($this->isDataModificationMethod($method)) {
                        $usesFormRequest = $this->usesFormRequest($method);
                        $hasInlineValidation = $this->hasInlineValidation($method);
                        
                        if (!$usesFormRequest) {
                            $issue = 'POST/PUT method should use Form Request validation';
                            
                            if ($hasInlineValidation) {
                                $issue = 'Method uses inline validation instead of Form Request';
                            }
                            
                            $missingValidation[] = [
                                'controller' => $controllerPath,
                                'method' => $method['name'],
                                'line' => $method['line'],
                                'issue' => $issue,
                                'has_inline_validation' => $hasInlineValidation
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip controllers that can't be parsed
                continue;
            }
        }
        
        return $missingValidation;
    }

    /**
     * Run all controller checks and return unified issues (for FixOrchestrator).
     * @return array List of ['message' => string, ...context]
     */
    public function analyze(): array
    {
        $issues = [];
        foreach ($this->findIncompleteControllerMethods() as $controller => $methods) {
            $issues[] = [
                'message' => 'Incomplete controller methods: ' . implode(', ', $methods),
                'controller' => $controller,
                'methods' => $methods,
            ];
        }
        foreach ($this->findInvalidQueries() as $q) {
            $issues[] = [
                'message' => ($q['issues'][0] ?? 'Invalid query') . ' in ' . ($q['method'] ?? ''),
                'controller' => $q['controller'] ?? null,
                'method' => $q['method'] ?? null,
                'line' => $q['line'] ?? null,
            ];
        }
        foreach ($this->findImproperResponses() as $r) {
            $issues[] = [
                'message' => 'Improper response format: ' . ($r['issue'] ?? ''),
                'controller' => $r['controller'] ?? null,
                'method' => $r['method'] ?? null,
            ];
        }
        foreach ($this->findMissingValidation() as $v) {
            $issues[] = [
                'message' => $v['issue'] ?? 'Missing Form Request validation',
                'controller' => $v['controller'] ?? null,
                'method' => $v['method'] ?? null,
            ];
        }
        return $issues;
    }
    
    /**
     * Check if method has inline validation (using $request->validate() or Validator::make())
     * 
     * @param array $method
     * @return bool
     */
    protected function hasInlineValidation(array $method): bool
    {
        if (empty($method['stmts'])) {
            return false;
        }
        
        // Look for $request->validate() or Validator::make() calls
        $hasValidation = false;
        
        $traverser = new NodeTraverser();
        $visitor = new class($hasValidation) extends NodeVisitorAbstract {
            private $hasValidation;
            
            public function __construct(&$hasValidation)
            {
                $this->hasValidation = &$hasValidation;
            }
            
            public function enterNode(Node $node)
            {
                // Check for $request->validate()
                if ($node instanceof Node\Expr\MethodCall) {
                    if ($node->name instanceof Node\Identifier && $node->name->toString() === 'validate') {
                        if ($node->var instanceof Node\Expr\Variable && $node->var->name === 'request') {
                            $this->hasValidation = true;
                        }
                    }
                }
                
                // Check for Validator::make()
                if ($node instanceof Node\Expr\StaticCall) {
                    if ($node->class instanceof Node\Name && $node->class->toString() === 'Validator') {
                        if ($node->name instanceof Node\Identifier && $node->name->toString() === 'make') {
                            $this->hasValidation = true;
                        }
                    }
                }
                
                // Check for $this->validate()
                if ($node instanceof Node\Expr\MethodCall) {
                    if ($node->name instanceof Node\Identifier && $node->name->toString() === 'validate') {
                        if ($node->var instanceof Node\Expr\Variable && $node->var->name === 'this') {
                            $this->hasValidation = true;
                        }
                    }
                }
            }
        };
        
        $traverser->addVisitor($visitor);
        
        try {
            $traverser->traverse($method['stmts']);
        } catch (\Exception $e) {
            // Return false if traversal fails
            return false;
        }
        
        return $hasValidation;
    }
    
    /**
     * Get all controller files in the application
     * 
     * @return array
     */
    protected function getAllControllers(): array
    {
        $controllers = [];
        $controllersPath = app_path('Http/Controllers');
        
        if (!File::exists($controllersPath)) {
            return $controllers;
        }
        
        $files = File::allFiles($controllersPath);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $controllers[] = $file->getPathname();
            }
        }
        
        return $controllers;
    }
    
    /**
     * Extract all methods from a controller file
     * 
     * @param string $controllerPath
     * @return array
     */
    protected function extractControllerMethods(string $controllerPath): array
    {
        $methods = [];
        
        try {
            $code = File::get($controllerPath);
            $ast = $this->parser->parse($code);
            
            if ($ast === null) {
                return $methods;
            }
            
            $traverser = new NodeTraverser();
            $visitor = new class($methods) extends NodeVisitorAbstract {
                private $methods;
                
                public function __construct(&$methods)
                {
                    $this->methods = &$methods;
                }
                
                public function enterNode(Node $node)
                {
                    if ($node instanceof Node\Stmt\ClassMethod) {
                        $this->methods[] = [
                            'name' => $node->name->toString(),
                            'line' => $node->getStartLine(),
                            'params' => $node->params,
                            'stmts' => $node->stmts,
                            'node' => $node
                        ];
                    }
                }
            };
            
            $traverser->addVisitor($visitor);
            $traverser->traverse($ast);
            
        } catch (Error $e) {
            // Return empty array if parsing fails
            return [];
        }
        
        return $methods;
    }
    
    /**
     * Extract Eloquent queries from a controller file
     * 
     * @param string $controllerPath
     * @return array
     */
    public function extractEloquentQueries(string $controllerPath): array
    {
        $queries = [];
        
        try {
            $code = File::get($controllerPath);
            $ast = $this->parser->parse($code);
            
            if ($ast === null) {
                return $queries;
            }
            
            $currentMethod = null;
            
            $traverser = new NodeTraverser();
            $visitor = new class($queries, $currentMethod) extends NodeVisitorAbstract {
                private $queries;
                private $currentMethod;
                
                public function __construct(&$queries, &$currentMethod)
                {
                    $this->queries = &$queries;
                    $this->currentMethod = &$currentMethod;
                }
                
                public function enterNode(Node $node)
                {
                    // Track current method
                    if ($node instanceof Node\Stmt\ClassMethod) {
                        $this->currentMethod = $node->name->toString();
                    }
                    
                    // Detect Eloquent query patterns
                    if ($node instanceof Node\Expr\StaticCall) {
                        // Model::where(), Model::find(), etc.
                        if ($node->class instanceof Node\Name) {
                            $this->queries[] = [
                                'method' => $this->currentMethod,
                                'line' => $node->getStartLine(),
                                'model' => $node->class->toString(),
                                'operation' => $node->name instanceof Node\Identifier ? $node->name->toString() : '',
                                'code' => $this->nodeToString($node),
                                'node' => $node
                            ];
                        }
                    }
                    
                    // Detect $model->relationship() calls
                    if ($node instanceof Node\Expr\MethodCall) {
                        if ($node->var instanceof Node\Expr\Variable) {
                            $this->queries[] = [
                                'method' => $this->currentMethod,
                                'line' => $node->getStartLine(),
                                'model' => null, // Will be determined from context
                                'operation' => $node->name instanceof Node\Identifier ? $node->name->toString() : '',
                                'code' => $this->nodeToString($node),
                                'node' => $node
                            ];
                        }
                    }
                }
                
                private function nodeToString($node): string
                {
                    // Simple string representation
                    if ($node instanceof Node\Expr\StaticCall && $node->class instanceof Node\Name) {
                        $method = $node->name instanceof Node\Identifier ? $node->name->toString() : '';
                        return $node->class->toString() . '::' . $method . '()';
                    }
                    return '';
                }
            };
            
            $traverser->addVisitor($visitor);
            $traverser->traverse($ast);
            
        } catch (Error $e) {
            // Return empty array if parsing fails
            return [];
        }
        
        return $queries;
    }
    
    /**
     * Check if a method is incomplete (empty or just returns null/empty)
     * 
     * @param array $method
     * @return bool
     */
    protected function isMethodIncomplete(array $method): bool
    {
        // Check if method has no statements
        if (empty($method['stmts'])) {
            return true;
        }
        
        // Check if method only returns null or empty
        if (count($method['stmts']) === 1) {
            $stmt = $method['stmts'][0];
            
            if ($stmt instanceof Node\Stmt\Return_) {
                // Check if return value is null or empty
                if ($stmt->expr === null) {
                    return true;
                }
                
                if ($stmt->expr instanceof Node\Expr\ConstFetch) {
                    $name = $stmt->expr->name->toString();
                    if (strtolower($name) === 'null') {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Validate an Eloquent query
     * 
     * @param array $query
     * @return array Issues found
     */
    protected function validateQuery(array $query): array
    {
        $issues = [];
        
        // Skip if no model identified
        if (empty($query['model'])) {
            return $issues;
        }
        
        // Check if model class exists
        $modelClass = $this->resolveModelClass($query['model']);
        
        if (!class_exists($modelClass)) {
            $issues[] = "Model class '{$query['model']}' does not exist";
            return $issues;
        }
        
        try {
            $reflection = new \ReflectionClass($modelClass);
            
            // Check if it's actually an Eloquent model
            if (!$reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                $issues[] = "Class '{$query['model']}' is not an Eloquent model";
                return $issues;
            }
            
            $instance = $reflection->newInstanceWithoutConstructor();
            $table = $instance->getTable();
            
            // Check if table exists
            if (!$this->tableExists($table)) {
                $issues[] = "Table '{$table}' referenced by model '{$query['model']}' does not exist";
                return $issues;
            }
            
            $actualColumns = $this->getDatabaseColumns($table);
            
            // For where() calls, check if columns exist
            if (in_array($query['operation'], ['where', 'whereHas', 'whereIn', 'whereNotIn', 'orWhere', 'orderBy', 'groupBy'])) {
                $columns = $this->extractColumnsFromQuery($query['node']);
                
                foreach ($columns as $column) {
                    if (!in_array($column, $actualColumns)) {
                        $issues[] = "Column '{$column}' does not exist in table '{$table}'";
                    }
                }
            }
            
            // For relationship methods, verify they exist on the model
            if (in_array($query['operation'], ['with', 'has', 'whereHas', 'doesntHave', 'whereDoesntHave'])) {
                $relationships = $this->extractRelationshipsFromQuery($query['node']);
                
                foreach ($relationships as $relationship) {
                    if (!method_exists($modelClass, $relationship)) {
                        $issues[] = "Relationship method '{$relationship}' does not exist on model '{$query['model']}'";
                    }
                }
            }
            
        } catch (\Exception $e) {
            // Skip validation if model can't be instantiated
        }
        
        return $issues;
    }
    
    /**
     * Extract relationship names from query node
     * 
     * @param Node $node
     * @return array
     */
    protected function extractRelationshipsFromQuery(Node $node): array
    {
        $relationships = [];
        
        if ($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall) {
            if (!empty($node->args)) {
                foreach ($node->args as $arg) {
                    if ($arg->value instanceof Node\Scalar\String_) {
                        // Relationship name is typically the first string argument
                        $relationships[] = $arg->value->value;
                    } elseif ($arg->value instanceof Node\Expr\Array_) {
                        // Handle array of relationships: with(['user', 'posts'])
                        foreach ($arg->value->items as $item) {
                            if ($item->value instanceof Node\Scalar\String_) {
                                $relationships[] = $item->value->value;
                            }
                        }
                    }
                }
            }
        }
        
        return $relationships;
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
            return !empty(\DB::select("SHOW TABLES LIKE '{$table}'"));
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Extract column names from query node (simplified)
     * 
     * @param Node $node
     * @return array
     */
    protected function extractColumnsFromQuery(Node $node): array
    {
        $columns = [];
        
        // This is a simplified implementation
        // In production, you'd need more sophisticated AST traversal
        if ($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall) {
            if (!empty($node->args)) {
                foreach ($node->args as $arg) {
                    if ($arg->value instanceof Node\Scalar\String_) {
                        // First argument might be a column name
                        $columns[] = $arg->value->value;
                        break;
                    }
                }
            }
        }
        
        return $columns;
    }
    
    /**
     * Check if method has improper response format
     * 
     * @param array $method
     * @return bool
     */
    protected function hasImproperResponse(array $method): bool
    {
        // This is a simplified check
        // In production, you'd analyze return statements more thoroughly
        
        if (empty($method['stmts'])) {
            return false;
        }
        
        // Look for return statements
        $hasReturn = false;
        
        foreach ($method['stmts'] as $stmt) {
            if ($stmt instanceof Node\Stmt\Return_) {
                $hasReturn = true;
                
                // Check if it's a response()->json() or similar
                if ($stmt->expr instanceof Node\Expr\MethodCall) {
                    // Likely a proper response
                    return false;
                }
            }
        }
        
        // If method name suggests it should return JSON but doesn't use proper response
        if (Str::contains($method['name'], ['getData', 'api', 'json'])) {
            return !$hasReturn;
        }
        
        return false;
    }
    
    /**
     * Check if method is a data modification method (store, update, etc.)
     * 
     * @param array $method
     * @return bool
     */
    protected function isDataModificationMethod(array $method): bool
    {
        $dataModificationMethods = ['store', 'update', 'destroy', 'massDestroy'];
        
        return in_array($method['name'], $dataModificationMethods);
    }
    
    /**
     * Check if method uses Form Request validation
     * 
     * @param array $method
     * @return bool
     */
    protected function usesFormRequest(array $method): bool
    {
        // Check method parameters for Form Request type hints
        if (empty($method['params'])) {
            return false;
        }
        
        foreach ($method['params'] as $param) {
            if ($param->type instanceof Node\Name) {
                $typeName = $param->type->toString();
                
                // Check if it's a Form Request (ends with Request and not just Request)
                if (Str::endsWith($typeName, 'Request') && $typeName !== 'Request') {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Resolve model class name to fully qualified class name
     * 
     * @param string $modelName
     * @return string
     */
    protected function resolveModelClass(string $modelName): string
    {
        // If already fully qualified, return as is
        if (strpos($modelName, '\\') !== false) {
            return $modelName;
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
     * Get actual database columns for a table
     * 
     * @param string $table
     * @return array
     */
    protected function getDatabaseColumns(string $table): array
    {
        try {
            $columns = \DB::select("SHOW COLUMNS FROM `{$table}`");
            return array_map(fn($col) => $col->Field, $columns);
        } catch (\Exception $e) {
            return [];
        }
    }
}
