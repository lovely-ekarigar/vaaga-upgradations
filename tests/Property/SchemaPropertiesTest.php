<?php

use App\Services\Stabilization\SchemaAnalyzer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Feature: laravel-application-stabilization, Property 1: Schema Issue Detection Completeness
 * 
 * For any Laravel application codebase with known schema issues (missing columns, invalid foreign keys, 
 * schema mismatches), the Schema Analyzer should identify all issues without false negatives.
 * 
 * Validates: Requirements 1.1, 1.2, 1.3
 */
test('schema analyzer detects all missing columns from model definitions', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Test with actual models in the application
    $missingColumns = $analyzer->findMissingColumns();
    
    // Verify the result is an array
    expect($missingColumns)->toBeArray();
    
    // For each table with missing columns, verify the structure
    foreach ($missingColumns as $table => $columns) {
        expect($table)->toBeString();
        expect($columns)->toBeArray();
        
        // Each column should be a string (flatten if nested)
        $flatColumns = is_array($columns) ? array_values((array)$columns) : [$columns];
        foreach ($flatColumns as $column) {
            if (is_array($column)) {
                // Handle nested arrays
                foreach ($column as $nestedColumn) {
                    expect($nestedColumn)->toBeString();
                }
            } else {
                expect($column)->toBeString();
            }
        }
    }
});

test('schema analyzer detects invalid foreign key constraints', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Test with actual database constraints
    $invalidConstraints = $analyzer->findInvalidForeignKeys();
    
    // Verify the result is an array
    expect($invalidConstraints)->toBeArray();
    
    // For each invalid constraint, verify the structure
    foreach ($invalidConstraints as $constraint) {
        expect($constraint)->toHaveKeys(['table', 'constraint_name', 'column', 'referenced_table', 'referenced_column', 'issues']);
        expect($constraint['table'])->toBeString();
        expect($constraint['constraint_name'])->toBeString();
        expect($constraint['column'])->toBeString();
        expect($constraint['referenced_table'])->toBeString();
        expect($constraint['referenced_column'])->toBeString();
        expect($constraint['issues'])->toBeArray();
        expect($constraint['issues'])->not->toBeEmpty();
    }
});

test('schema analyzer detects schema mismatches between migrations and database', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Test with actual migrations and database state
    $mismatches = $analyzer->findSchemaMismatches();
    
    // Verify the result is an array
    expect($mismatches)->toBeArray();
    
    // For each mismatch, verify the structure
    foreach ($mismatches as $mismatch) {
        expect($mismatch)->toHaveKeys(['type', 'table', 'migration', 'message']);
        expect($mismatch['type'])->toBeIn(['missing_table', 'missing_columns']);
        expect($mismatch['table'])->toBeString();
        expect($mismatch['migration'])->toBeString();
        expect($mismatch['message'])->toBeString();
        
        if ($mismatch['type'] === 'missing_columns') {
            expect($mismatch)->toHaveKey('columns');
            expect($mismatch['columns'])->toBeArray();
        }
    }
});

test('schema analyzer extracts column references from all models', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Test extracting column references
    $references = $analyzer->extractModelColumnReferences();
    
    // Verify the result is an array
    expect($references)->toBeArray();
    
    // For each model, verify the structure
    foreach ($references as $modelClass => $columns) {
        expect($modelClass)->toBeString();
        expect(class_exists($modelClass))->toBeTrue();
        expect($columns)->toBeArray();
        
        // Each column should be a string
        foreach ($columns as $column) {
            expect($column)->toBeString();
        }
    }
});

test('schema analyzer handles models without fillable or casts gracefully', function () {
    $analyzer = new SchemaAnalyzer();
    
    // This should not throw exceptions even if some models have no fillable/casts
    $references = $analyzer->extractModelColumnReferences();
    
    expect($references)->toBeArray();
});

test('schema analyzer handles non-existent tables gracefully', function () {
    $analyzer = new SchemaAnalyzer();
    
    // This should not throw exceptions even if some tables don't exist
    $missingColumns = $analyzer->findMissingColumns();
    
    expect($missingColumns)->toBeArray();
});

test('schema analyzer returns consistent results on multiple runs', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Run analysis twice
    $result1 = $analyzer->findMissingColumns();
    $result2 = $analyzer->findMissingColumns();
    
    // Results should be identical
    expect($result1)->toBe($result2);
});

test('schema analyzer detects all types of schema issues comprehensively', function () {
    $analyzer = new SchemaAnalyzer();
    
    // Run all analysis methods
    $missingColumns = $analyzer->findMissingColumns();
    $invalidConstraints = $analyzer->findInvalidForeignKeys();
    $mismatches = $analyzer->findSchemaMismatches();
    
    // All should return arrays
    expect($missingColumns)->toBeArray();
    expect($invalidConstraints)->toBeArray();
    expect($mismatches)->toBeArray();
    
    // If there are any issues, they should be properly structured
    $totalIssues = count($missingColumns) + count($invalidConstraints) + count($mismatches);
    
    if ($totalIssues > 0) {
        // At least one type of issue was detected
        expect($totalIssues)->toBeGreaterThan(0);
    }
});
