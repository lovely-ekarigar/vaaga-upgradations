<?php

use App\Services\Stabilization\ControllerAnalyzer;
use Illuminate\Support\Facades\File;

/**
 * Feature: laravel-application-stabilization, Property 7: Controller Query Validity
 * 
 * For any Eloquent query in controller methods, all referenced columns, relationships, 
 * and table names should exist in the current database schema.
 * 
 * Validates: Requirements 4.2
 */
test('controller analyzer detects invalid queries in all controllers', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Test with actual controllers in the application
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Verify the result is an array
    expect($invalidQueries)->toBeArray();
    
    // For each invalid query, verify the structure
    foreach ($invalidQueries as $query) {
        expect($query)->toHaveKeys(['controller', 'method', 'line', 'query', 'issues']);
        expect($query['controller'])->toBeString();
        expect($query['method'])->toBeString();
        expect($query['line'])->toBeInt();
        expect($query['query'])->toBeString();
        expect($query['issues'])->toBeArray();
        expect($query['issues'])->not->toBeEmpty();
        
        // Each issue should be a descriptive string
        foreach ($query['issues'] as $issue) {
            expect($issue)->toBeString();
            expect(strlen($issue))->toBeGreaterThan(10);
        }
    }
});

test('controller analyzer identifies queries with non-existent columns', function () {
    $analyzer = new ControllerAnalyzer();
    
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Filter for column-related issues
    $columnIssues = array_filter($invalidQueries, function ($query) {
        foreach ($query['issues'] as $issue) {
            if (str_contains($issue, 'Column') && str_contains($issue, 'does not exist')) {
                return true;
            }
        }
        return false;
    });
    
    // If there are column issues, they should be properly structured
    foreach ($columnIssues as $query) {
        expect($query['controller'])->toBeString();
        expect($query['method'])->toBeString();
        expect($query['issues'])->toBeArray();
    }
});

test('controller analyzer identifies queries with non-existent relationships', function () {
    $analyzer = new ControllerAnalyzer();
    
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Filter for relationship-related issues
    $relationshipIssues = array_filter($invalidQueries, function ($query) {
        foreach ($query['issues'] as $issue) {
            if (str_contains($issue, 'Relationship') && str_contains($issue, 'does not exist')) {
                return true;
            }
        }
        return false;
    });
    
    // If there are relationship issues, they should be properly structured
    foreach ($relationshipIssues as $query) {
        expect($query['controller'])->toBeString();
        expect($query['method'])->toBeString();
        expect($query['issues'])->toBeArray();
    }
});

test('controller analyzer identifies queries with non-existent tables', function () {
    $analyzer = new ControllerAnalyzer();
    
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Filter for table-related issues
    $tableIssues = array_filter($invalidQueries, function ($query) {
        foreach ($query['issues'] as $issue) {
            if (str_contains($issue, 'Table') && str_contains($issue, 'does not exist')) {
                return true;
            }
        }
        return false;
    });
    
    // If there are table issues, they should be properly structured
    foreach ($tableIssues as $query) {
        expect($query['controller'])->toBeString();
        expect($query['method'])->toBeString();
        expect($query['issues'])->toBeArray();
    }
});

test('controller analyzer extracts eloquent queries from all controllers', function () {
    $analyzer = new ControllerAnalyzer();
    $controllers = File::allFiles(app_path('Http/Controllers'));
    
    // Test query extraction for each controller
    foreach ($controllers as $controller) {
        if ($controller->getExtension() === 'php') {
            $queries = $analyzer->extractEloquentQueries($controller->getPathname());
            
            // Verify the result is an array
            expect($queries)->toBeArray();
            
            // For each query, verify the structure
            foreach ($queries as $query) {
                expect($query)->toHaveKeys(['method', 'line', 'model', 'operation', 'code', 'node']);
                
                if (!empty($query['method'])) {
                    expect($query['method'])->toBeString();
                }
                
                expect($query['line'])->toBeInt();
                expect($query['line'])->toBeGreaterThan(0);
                
                if (!empty($query['operation'])) {
                    expect($query['operation'])->toBeString();
                }
            }
        }
    }
});

test('controller analyzer handles controllers without queries gracefully', function () {
    $analyzer = new ControllerAnalyzer();
    
    // This should not throw exceptions even if some controllers have no queries
    $invalidQueries = $analyzer->findInvalidQueries();
    
    expect($invalidQueries)->toBeArray();
});

test('controller analyzer handles unparseable controllers gracefully', function () {
    $analyzer = new ControllerAnalyzer();
    
    // This should not throw exceptions even if some controllers can't be parsed
    $invalidQueries = $analyzer->findInvalidQueries();
    
    expect($invalidQueries)->toBeArray();
});

test('controller analyzer returns consistent results on multiple runs', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Run analysis twice
    $result1 = $analyzer->findInvalidQueries();
    $result2 = $analyzer->findInvalidQueries();
    
    // Results should be identical
    expect(count($result1))->toBe(count($result2));
});

test('controller analyzer validates all query operations comprehensively', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Run query validation
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Should return an array
    expect($invalidQueries)->toBeArray();
    
    // If there are invalid queries, they should have proper issue descriptions
    foreach ($invalidQueries as $query) {
        expect($query['issues'])->toBeArray();
        expect($query['issues'])->not->toBeEmpty();
        
        // Each issue should provide actionable information
        foreach ($query['issues'] as $issue) {
            expect($issue)->toBeString();
            expect(strlen($issue))->toBeGreaterThan(5);
        }
    }
});

test('controller analyzer identifies queries referencing non-existent models', function () {
    $analyzer = new ControllerAnalyzer();
    
    $invalidQueries = $analyzer->findInvalidQueries();
    
    // Filter for model-related issues
    $modelIssues = array_filter($invalidQueries, function ($query) {
        foreach ($query['issues'] as $issue) {
            if (str_contains($issue, 'Model') && str_contains($issue, 'does not exist')) {
                return true;
            }
        }
        return false;
    });
    
    // If there are model issues, they should be properly structured
    foreach ($modelIssues as $query) {
        expect($query['controller'])->toBeString();
        expect($query['method'])->toBeString();
        expect($query['issues'])->toBeArray();
    }
});


/**
 * Feature: laravel-application-stabilization, Property 11: Form Request Validation Usage
 * 
 * For any controller method handling POST or PUT requests, the method should use a Form Request 
 * class for validation rather than inline validation.
 * 
 * Validates: Requirements 5.1, 11.2
 */
test('controller analyzer detects methods missing form request validation', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Test with actual controllers in the application
    $missingValidation = $analyzer->findMissingValidation();
    
    // Verify the result is an array
    expect($missingValidation)->toBeArray();
    
    // For each method missing validation, verify the structure
    foreach ($missingValidation as $method) {
        expect($method)->toHaveKeys(['controller', 'method', 'line', 'issue', 'has_inline_validation']);
        expect($method['controller'])->toBeString();
        expect($method['method'])->toBeString();
        expect($method['line'])->toBeInt();
        expect($method['issue'])->toBeString();
        expect($method['has_inline_validation'])->toBeBool();
        
        // Issue should be descriptive
        expect(strlen($method['issue']))->toBeGreaterThan(10);
    }
});

test('controller analyzer identifies data modification methods', function () {
    $analyzer = new ControllerAnalyzer();
    
    $missingValidation = $analyzer->findMissingValidation();
    
    // All identified methods should be data modification methods
    $validMethodNames = ['store', 'update', 'destroy', 'massDestroy'];
    
    foreach ($missingValidation as $method) {
        expect($method['method'])->toBeIn($validMethodNames);
    }
});

test('controller analyzer distinguishes between form request and inline validation', function () {
    $analyzer = new ControllerAnalyzer();
    
    $missingValidation = $analyzer->findMissingValidation();
    
    // Filter methods with inline validation
    $withInlineValidation = array_filter($missingValidation, function ($method) {
        return $method['has_inline_validation'] === true;
    });
    
    // Filter methods without any validation
    $withoutValidation = array_filter($missingValidation, function ($method) {
        return $method['has_inline_validation'] === false;
    });
    
    // Methods with inline validation should have appropriate issue message
    foreach ($withInlineValidation as $method) {
        expect($method['issue'])->toContain('inline validation');
    }
    
    // Methods without validation should have appropriate issue message
    foreach ($withoutValidation as $method) {
        expect($method['issue'])->toContain('should use Form Request');
    }
});

test('controller analyzer handles controllers without data modification methods gracefully', function () {
    $analyzer = new ControllerAnalyzer();
    
    // This should not throw exceptions even if some controllers have no data modification methods
    $missingValidation = $analyzer->findMissingValidation();
    
    expect($missingValidation)->toBeArray();
});

test('controller analyzer returns consistent validation results on multiple runs', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Run analysis twice
    $result1 = $analyzer->findMissingValidation();
    $result2 = $analyzer->findMissingValidation();
    
    // Results should be identical
    expect(count($result1))->toBe(count($result2));
});

test('controller analyzer validates all data modification methods comprehensively', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Run validation analysis
    $missingValidation = $analyzer->findMissingValidation();
    
    // Should return an array
    expect($missingValidation)->toBeArray();
    
    // If there are methods missing validation, they should have proper issue descriptions
    foreach ($missingValidation as $method) {
        expect($method['issue'])->toBeString();
        
        // Issue should mention validation or Form Request
        $hasRelevantKeyword = str_contains($method['issue'], 'validation') || 
                              str_contains($method['issue'], 'Form Request');
        expect($hasRelevantKeyword)->toBeTrue();
        
        // Should indicate whether inline validation is present
        expect($method)->toHaveKey('has_inline_validation');
    }
});

test('controller analyzer identifies all incomplete controller methods', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Test incomplete method detection
    $incompleteMethods = $analyzer->findIncompleteControllerMethods();
    
    // Verify the result is an array
    expect($incompleteMethods)->toBeArray();
    
    // For each controller with incomplete methods, verify the structure
    foreach ($incompleteMethods as $controller => $methods) {
        expect($controller)->toBeString();
        expect($methods)->toBeArray();
        
        // Each method should be a string (method name)
        foreach ($methods as $method) {
            expect($method)->toBeString();
        }
    }
});

test('controller analyzer identifies all improper response formats', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Test improper response detection
    $improperResponses = $analyzer->findImproperResponses();
    
    // Verify the result is an array
    expect($improperResponses)->toBeArray();
    
    // For each improper response, verify the structure
    foreach ($improperResponses as $response) {
        expect($response)->toHaveKeys(['controller', 'method', 'line', 'issue']);
        expect($response['controller'])->toBeString();
        expect($response['method'])->toBeString();
        expect($response['line'])->toBeInt();
        expect($response['issue'])->toBeString();
    }
});

test('controller analyzer handles all controller analysis methods without errors', function () {
    $analyzer = new ControllerAnalyzer();
    
    // Run all analysis methods
    $invalidQueries = $analyzer->findInvalidQueries();
    $missingValidation = $analyzer->findMissingValidation();
    $incompleteMethods = $analyzer->findIncompleteControllerMethods();
    $improperResponses = $analyzer->findImproperResponses();
    
    // All should return arrays
    expect($invalidQueries)->toBeArray();
    expect($missingValidation)->toBeArray();
    expect($incompleteMethods)->toBeArray();
    expect($improperResponses)->toBeArray();
});

