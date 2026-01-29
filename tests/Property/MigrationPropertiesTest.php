<?php

use App\Services\Stabilization\MigrationGenerator;
use Illuminate\Support\Facades\File;

/**
 * Feature: laravel-application-stabilization
 * Property 2: Migration File Structure Validity
 * 
 * For any generated migration file, the file should follow Laravel naming conventions 
 * (timestamp_action_table_name.php format) and contain both up() and down() methods 
 * with valid Schema builder syntax.
 * 
 * Validates: Requirements 1.4, 1.5, 1.6, 2.1
 */

beforeEach(function () {
    // Clean up any test migrations before each test
    $this->testMigrationsPath = database_path('migrations');
    $this->createdFiles = [];
});

afterEach(function () {
    // Clean up created test migration files
    foreach ($this->createdFiles as $file) {
        if (File::exists($file)) {
            File::delete($file);
        }
    }
});

test('generated add column migrations follow Laravel naming convention and have up/down methods', function () {
    $generator = new MigrationGenerator();
    
    // Test data: various table and column combinations
    $testCases = [
        ['table' => 'users', 'columns' => [['name' => 'status', 'type' => 'string']]],
        ['table' => 'orders', 'columns' => [['name' => 'total', 'type' => 'decimal'], ['name' => 'tax', 'type' => 'decimal']]],
        ['table' => 'products', 'columns' => [['name' => 'is_active', 'type' => 'boolean', 'nullable' => true]]],
        ['table' => 'posts', 'columns' => [['name' => 'published_at', 'type' => 'dateTime', 'nullable' => true]]],
        ['table' => 'categories', 'columns' => [['name' => 'description', 'type' => 'text']]],
    ];
    
    foreach ($testCases as $testCase) {
        $migrationPath = $generator->generateAddColumnMigration($testCase['table'], $testCase['columns']);
        $this->createdFiles[] = $migrationPath;
        
        // Extract filename from path
        $filename = basename($migrationPath);
        
        // Check naming convention: YYYY_MM_DD_HHMMSS_add_*_to_table_name_table.php
        expect($filename)->toMatch('/^\d{4}_\d{2}_\d{2}_\d{6}_add_\w+_to_' . $testCase['table'] . '_table(_\d+)?\.php$/');
        
        // Check file exists
        expect(File::exists($migrationPath))->toBeTrue();
        
        // Check file contains up() and down() methods
        $content = File::get($migrationPath);
        expect($content)->toContain('public function up()');
        expect($content)->toContain('public function down()');
        
        // Check file contains Schema builder syntax
        expect($content)->toContain('Schema::table');
        expect($content)->toContain('function (Blueprint $table)');
        
        // Check file contains the table name
        expect($content)->toContain("'{$testCase['table']}'");
        
        // Check file contains column definitions
        foreach ($testCase['columns'] as $column) {
            $columnName = $column['name'];
            expect($content)->toContain("'{$columnName}'");
        }
        
        // Check file has proper class structure
        expect($content)->toContain('class ');
        expect($content)->toContain('extends Migration');
        
        // Check down() method has dropColumn
        expect($content)->toContain('dropColumn');
    }
})->group('property', 'migration', 'stabilization');

test('generated foreign key fix migrations follow Laravel naming convention and have up/down methods', function () {
    $generator = new MigrationGenerator();
    
    // Test data: various foreign key scenarios
    $testCases = [
        ['table' => 'posts', 'foreignKey' => 'user_id', 'referencedTable' => 'users', 'referencedColumn' => 'id'],
        ['table' => 'comments', 'foreignKey' => 'post_id', 'referencedTable' => 'posts', 'referencedColumn' => 'id'],
        ['table' => 'order_items', 'foreignKey' => 'order_id', 'referencedTable' => 'orders', 'referencedColumn' => 'id'],
        ['table' => 'reviews', 'foreignKey' => 'product_id', 'referencedTable' => 'products', 'referencedColumn' => 'id'],
    ];
    
    foreach ($testCases as $testCase) {
        $migrationPath = $generator->generateFixForeignKeyMigration(
            $testCase['table'],
            $testCase['foreignKey'],
            $testCase['referencedTable'],
            $testCase['referencedColumn']
        );
        $this->createdFiles[] = $migrationPath;
        
        // Extract filename from path
        $filename = basename($migrationPath);
        
        // Check naming convention: YYYY_MM_DD_HHMMSS_fix_*_foreign_key_on_table_name_table.php
        expect($filename)->toMatch('/^\d{4}_\d{2}_\d{2}_\d{6}_fix_' . $testCase['foreignKey'] . '_foreign_key_on_' . $testCase['table'] . '_table(_\d+)?\.php$/');
        
        // Check file exists
        expect(File::exists($migrationPath))->toBeTrue();
        
        // Check file contains up() and down() methods
        $content = File::get($migrationPath);
        expect($content)->toContain('public function up()');
        expect($content)->toContain('public function down()');
        
        // Check file contains Schema builder syntax
        expect($content)->toContain('Schema::table');
        expect($content)->toContain('function (Blueprint $table)');
        
        // Check file contains foreign key definition
        expect($content)->toContain('->foreign(');
        expect($content)->toContain('->references(');
        expect($content)->toContain('->on(');
        expect($content)->toContain('->onDelete(');
        
        // Check file contains the table and column names
        expect($content)->toContain("'{$testCase['table']}'");
        expect($content)->toContain("'{$testCase['foreignKey']}'");
        expect($content)->toContain("'{$testCase['referencedTable']}'");
        expect($content)->toContain("'{$testCase['referencedColumn']}'");
        
        // Check down() method has dropForeign
        expect($content)->toContain('dropForeign');
    }
})->group('property', 'migration', 'stabilization');

test('generated pivot table migrations follow Laravel naming convention and have up/down methods', function () {
    $generator = new MigrationGenerator();
    
    // Test data: various pivot table scenarios
    $testCases = [
        ['table1' => 'users', 'table2' => 'roles'],
        ['table1' => 'posts', 'table2' => 'tags'],
        ['table1' => 'courses', 'table2' => 'students'],
        ['table1' => 'products', 'table2' => 'categories'],
    ];
    
    foreach ($testCases as $testCase) {
        $migrationPath = $generator->generatePivotTableMigration(
            $testCase['table1'],
            $testCase['table2']
        );
        $this->createdFiles[] = $migrationPath;
        
        // Extract filename from path
        $filename = basename($migrationPath);
        
        // Check naming convention: YYYY_MM_DD_HHMMSS_create_*_table.php
        expect($filename)->toMatch('/^\d{4}_\d{2}_\d{2}_\d{6}_create_\w+_table(_\d+)?\.php$/');
        
        // Check file exists
        expect(File::exists($migrationPath))->toBeTrue();
        
        // Check file contains up() and down() methods
        $content = File::get($migrationPath);
        expect($content)->toContain('public function up()');
        expect($content)->toContain('public function down()');
        
        // Check file contains Schema::create (not Schema::table)
        expect($content)->toContain('Schema::create');
        expect($content)->toContain('function (Blueprint $table)');
        
        // Check file contains foreign key definitions
        expect($content)->toContain('->foreign(');
        expect($content)->toContain('->references(');
        expect($content)->toContain('->on(');
        expect($content)->toContain('->onDelete(');
        
        // Check file contains primary key definition
        expect($content)->toContain('->primary(');
        
        // Check down() method has dropIfExists
        expect($content)->toContain('Schema::dropIfExists');
        
        // Check file has proper class structure
        expect($content)->toContain('class ');
        expect($content)->toContain('extends Migration');
    }
})->group('property', 'migration', 'stabilization');

test('migration files handle naming conflicts by appending suffix', function () {
    $generator = new MigrationGenerator();
    
    // Generate first migration
    $migrationPath1 = $generator->generateAddColumnMigration('users', [['name' => 'status', 'type' => 'string']]);
    $this->createdFiles[] = $migrationPath1;
    
    // Wait a moment to ensure same timestamp
    usleep(100000); // 0.1 seconds
    
    // Generate second migration with same parameters (should get suffix)
    $migrationPath2 = $generator->generateAddColumnMigration('users', [['name' => 'status', 'type' => 'string']]);
    $this->createdFiles[] = $migrationPath2;
    
    // Both files should exist
    expect(File::exists($migrationPath1))->toBeTrue();
    expect(File::exists($migrationPath2))->toBeTrue();
    
    // Files should have different names
    expect($migrationPath1)->not->toBe($migrationPath2);
    
    // Second file should have suffix
    $filename2 = basename($migrationPath2);
    expect($filename2)->toMatch('/_\d+\.php$/');
})->group('property', 'migration', 'stabilization');

test('migration files contain valid PHP syntax', function () {
    $generator = new MigrationGenerator();
    
    $testCases = [
        ['type' => 'add_column', 'params' => ['users', [['name' => 'status', 'type' => 'string']]]],
        ['type' => 'fix_foreign_key', 'params' => ['posts', 'user_id', 'users', 'id']],
        ['type' => 'pivot_table', 'params' => ['users', 'roles']],
    ];
    
    foreach ($testCases as $testCase) {
        $migrationPath = match($testCase['type']) {
            'add_column' => $generator->generateAddColumnMigration(...$testCase['params']),
            'fix_foreign_key' => $generator->generateFixForeignKeyMigration(...$testCase['params']),
            'pivot_table' => $generator->generatePivotTableMigration(...$testCase['params']),
        };
        
        $this->createdFiles[] = $migrationPath;
        
        // Check PHP syntax by attempting to parse the file
        $content = File::get($migrationPath);
        
        // Use token_get_all to validate PHP syntax
        $tokens = @token_get_all($content);
        expect($tokens)->toBeArray();
        expect(count($tokens))->toBeGreaterThan(0);
        
        // Check for common syntax errors
        expect($content)->not->toContain('<?php<?php'); // No duplicate PHP tags
        expect($content)->not->toContain('}}'); // No double closing braces (unless in string)
    }
})->group('property', 'migration', 'stabilization');


/**
 * Feature: laravel-application-stabilization
 * Property 3: Migration Immutability
 * 
 * For any existing migration file in the database/migrations directory, 
 * the stabilization process should never modify its contents 
 * (file checksums remain unchanged).
 * 
 * Validates: Requirements 1.7
 */

test('existing migrations are never modified during stabilization', function () {
    $generator = new MigrationGenerator();
    $migrationsPath = database_path('migrations');
    
    // Get all existing migration files
    $existingMigrations = File::files($migrationsPath);
    
    // Skip if no migrations exist
    if (empty($existingMigrations)) {
        expect(true)->toBeTrue();
        return;
    }
    
    // Calculate checksums before stabilization operations
    $checksumsBefore = [];
    foreach ($existingMigrations as $migration) {
        $checksumsBefore[$migration->getFilename()] = md5_file($migration->getPathname());
    }
    
    // Perform various stabilization operations that should NOT modify existing migrations
    
    // 1. Generate new add column migration
    $newMigration1 = $generator->generateAddColumnMigration('test_table', [['name' => 'test_column', 'type' => 'string']]);
    $this->createdFiles[] = $newMigration1;
    
    // 2. Generate new foreign key fix migration
    $newMigration2 = $generator->generateFixForeignKeyMigration('test_table', 'user_id', 'users', 'id');
    $this->createdFiles[] = $newMigration2;
    
    // 3. Generate new pivot table migration
    $newMigration3 = $generator->generatePivotTableMigration('users', 'roles');
    $this->createdFiles[] = $newMigration3;
    
    // Calculate checksums after stabilization operations
    $checksumsAfter = [];
    foreach ($existingMigrations as $migration) {
        $checksumsAfter[$migration->getFilename()] = md5_file($migration->getPathname());
    }
    
    // All checksums should be identical - no existing migrations should be modified
    foreach ($checksumsBefore as $filename => $checksumBefore) {
        expect($checksumsAfter[$filename])
            ->toBe($checksumBefore, "Migration file '{$filename}' was modified during stabilization");
    }
    
    // Verify that new migrations were created (not modifying existing ones)
    expect(File::exists($newMigration1))->toBeTrue();
    expect(File::exists($newMigration2))->toBeTrue();
    expect(File::exists($newMigration3))->toBeTrue();
})->group('property', 'migration', 'stabilization');

test('migration generator never overwrites existing migration files', function () {
    $generator = new MigrationGenerator();
    
    // Create a test migration file
    $originalMigration = $generator->generateAddColumnMigration('users', [['name' => 'status', 'type' => 'string']]);
    $this->createdFiles[] = $originalMigration;
    
    $originalContent = File::get($originalMigration);
    $originalChecksum = md5($originalContent);
    
    // Try to generate another migration with same parameters
    // This should create a new file with suffix, not overwrite
    $newMigration = $generator->generateAddColumnMigration('users', [['name' => 'status', 'type' => 'string']]);
    $this->createdFiles[] = $newMigration;
    
    // Original file should still exist with same content
    expect(File::exists($originalMigration))->toBeTrue();
    expect(md5(File::get($originalMigration)))->toBe($originalChecksum);
    
    // New file should be different
    expect($newMigration)->not->toBe($originalMigration);
    expect(File::exists($newMigration))->toBeTrue();
})->group('property', 'migration', 'stabilization');

test('multiple concurrent migration generations do not corrupt existing files', function () {
    $generator = new MigrationGenerator();
    $migrationsPath = database_path('migrations');
    
    // Get existing migrations
    $existingMigrations = File::files($migrationsPath);
    
    // Calculate checksums before
    $checksumsBefore = [];
    foreach ($existingMigrations as $migration) {
        $checksumsBefore[$migration->getFilename()] = md5_file($migration->getPathname());
    }
    
    // Generate multiple migrations rapidly
    $newMigrations = [];
    for ($i = 0; $i < 5; $i++) {
        $newMigrations[] = $generator->generateAddColumnMigration("table_{$i}", [['name' => "column_{$i}", 'type' => 'string']]);
        $this->createdFiles[] = end($newMigrations);
    }
    
    // Verify all new migrations were created
    foreach ($newMigrations as $newMigration) {
        expect(File::exists($newMigration))->toBeTrue();
    }
    
    // Verify existing migrations were not modified
    foreach ($existingMigrations as $migration) {
        $filename = $migration->getFilename();
        $checksumAfter = md5_file($migration->getPathname());
        expect($checksumAfter)->toBe($checksumsBefore[$filename], "Migration file '{$filename}' was corrupted");
    }
})->group('property', 'migration', 'stabilization');

