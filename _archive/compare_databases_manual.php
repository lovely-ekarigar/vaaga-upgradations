<?php
/**
 * Database Comparison Script
 * Compare database directories between two Laravel projects
 * 
 * Run this with: php compare_databases_manual.php
 */

// ============================================
// CONFIGURATION - Update these paths as needed
// ============================================
$currentProject = 'c:\Projects\vaagaacademy';
$oldProject = 'C:\Users\malik\Downloads\_public_html_vaaga_mock';

// ============================================
// FUNCTIONS
// ============================================

function getFiles($path, $pattern = '*') {
    $fullPath = $path . DIRECTORY_SEPARATOR . $pattern;
    $files = glob($fullPath);
    $result = [];
    foreach ($files as $file) {
        if (is_file($file)) {
            $result[] = basename($file);
        }
    }
    sort($result);
    return $result;
}

function getFilesRecursive($path, $extensions = []) {
    if (!is_dir($path)) {
        return [];
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    $files = [];
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();
            if (empty($extensions) || in_array($file->getExtension(), $extensions)) {
                $files[] = $filename;
            }
        }
    }
    sort($files);
    return $files;
}

function checkDirectoryExists($path, $name) {
    if (!is_dir($path)) {
        echo "⚠️  WARNING: {$name} does not exist: {$path}\n";
        return false;
    }
    echo "✓ {$name} found: {$path}\n";
    return true;
}

// ============================================
// MAIN EXECUTION
// ============================================

echo "========================================\n";
echo "DATABASE DIRECTORY COMPARISON\n";
echo "========================================\n\n";

echo "Current Project: {$currentProject}\n";
echo "Old Project: {$oldProject}\n\n";

// Verify directories exist
$currentExists = checkDirectoryExists($currentProject . '\database', 'Current project database');
$oldExists = checkDirectoryExists($oldProject . '\database', 'Old project database');

if (!$currentExists || !$oldExists) {
    echo "\n❌ Cannot proceed - one or both directories not found!\n";
    exit(1);
}

// ============================================
// 1. COMPARE MIGRATIONS
// ============================================

echo "\n========================================\n";
echo "MIGRATIONS COMPARISON\n";
echo "========================================\n";

$currentMigrationsPath = $currentProject . '\database\migrations';
$oldMigrationsPath = $oldProject . '\database\migrations';

$migrationsCurrent = is_dir($currentMigrationsPath) ? getFiles($currentMigrationsPath, '*.php') : [];
$migrationsOld = is_dir($oldMigrationsPath) ? getFiles($oldMigrationsPath, '*.php') : [];

echo "Current project migrations: " . count($migrationsCurrent) . "\n";
echo "Old project migrations: " . count($migrationsOld) . "\n\n";

$missingInCurrent = array_diff($migrationsOld, $migrationsCurrent);
$newInCurrent = array_diff($migrationsCurrent, $migrationsOld);

echo "--- Migrations MISSING in current (exist in old): ---\n";
if (empty($missingInCurrent)) {
    echo "None - all old migrations exist in current project.\n";
} else {
    foreach ($missingInCurrent as $file) {
        echo "  ❌ {$file}\n";
    }
}
echo "\n";

echo "--- NEW migrations in current (not in old): ---\n";
if (empty($newInCurrent)) {
    echo "None - no new migrations in current project.\n";
} else {
    foreach ($newInCurrent as $file) {
        echo "  ✓ {$file}\n";
    }
}
echo "\n";

// ============================================
// 2. COMPARE SEEDERS (both seeds and seeders folders)
// ============================================

echo "========================================\n";
echo "SEEDERS COMPARISON\n";
echo "========================================\n";

$currentSeedsPath = $currentProject . '\database\seeds';
$oldSeedsPath = $oldProject . '\database\seeds';
$currentSeedersPath = $currentProject . '\database\seeders';
$oldSeedersPath = $oldProject . '\database\seeders';

$seedsCurrent = is_dir($currentSeedsPath) ? getFilesRecursive($currentSeedsPath, ['php']) : [];
$seedsOld = is_dir($oldSeedsPath) ? getFilesRecursive($oldSeedsPath, ['php']) : [];
$seedersCurrent = is_dir($currentSeedersPath) ? getFilesRecursive($currentSeedersPath, ['php']) : [];
$seedersOld = is_dir($oldSeedersPath) ? getFilesRecursive($oldSeedersPath, ['php']) : [];

// Combine seeds and seeders
$allSeedersCurrent = array_unique(array_merge($seedsCurrent, $seedersCurrent));
$allSeedersOld = array_unique(array_merge($seedsOld, $seedersOld));

echo "Current project seeders: " . count($allSeedersCurrent) . "\n";
echo "  - in database/seeds: " . count($seedsCurrent) . "\n";
echo "  - in database/seeders: " . count($seedersCurrent) . "\n";
echo "Old project seeders: " . count($allSeedersOld) . "\n";
echo "  - in database/seeds: " . count($seedsOld) . "\n";
echo "  - in database/seeders: " . count($seedersOld) . "\n\n";

$missingSeeders = array_diff($allSeedersOld, $allSeedersCurrent);
$newSeeders = array_diff($allSeedersCurrent, $allSeedersOld);

echo "--- Seeders MISSING in current (exist in old): ---\n";
if (empty($missingSeeders)) {
    echo "None - all old seeders exist in current project.\n";
} else {
    foreach ($missingSeeders as $file) {
        echo "  ❌ {$file}\n";
    }
}
echo "\n";

echo "--- NEW seeders in current (not in old): ---\n";
if (empty($newSeeders)) {
    echo "None - no new seeders in current project.\n";
} else {
    foreach ($newSeeders as $file) {
        echo "  ✓ {$file}\n";
    }
}
echo "\n";

// ============================================
// 3. CHECK FOR SQL FILES
// ============================================

echo "========================================\n";
echo "SQL FILES IN DATABASE FOLDER\n";
echo "========================================\n";

$currentDbPath = $currentProject . '\database';
$oldDbPath = $oldProject . '\database';

$sqlCurrent = is_dir($currentDbPath) ? getFiles($currentDbPath, '*.sql') : [];
$sqlOld = is_dir($oldDbPath) ? getFiles($oldDbPath, '*.sql') : [];

echo "Current project SQL files: " . count($sqlCurrent) . "\n";
echo "Old project SQL files: " . count($sqlOld) . "\n\n";

echo "--- SQL files in current project database folder: ---\n";
if (empty($sqlCurrent)) {
    echo "None\n";
} else {
    foreach ($sqlCurrent as $file) {
        echo "  📄 {$file}\n";
    }
}
echo "\n";

echo "--- SQL files in old project database folder: ---\n";
if (empty($sqlOld)) {
    echo "None\n";
} else {
    foreach ($sqlOld as $file) {
        echo "  📄 {$file}\n";
    }
}
echo "\n";

$missingSql = array_diff($sqlOld, $sqlCurrent);
if (!empty($missingSql)) {
    echo "--- SQL files MISSING in current project: ---\n";
    foreach ($missingSql as $file) {
        echo "  ❌ {$file}\n";
    }
    echo "\n";
}

// Also check root directories for SQL files
$sqlCurrentRoot = getFiles($currentProject, '*.sql');
$sqlOldRoot = getFiles($oldProject, '*.sql');

echo "--- SQL files in current project ROOT: ---\n";
if (empty($sqlCurrentRoot)) {
    echo "None\n";
} else {
    foreach ($sqlCurrentRoot as $file) {
        echo "  📄 {$file}\n";
    }
}
echo "\n";

echo "--- SQL files in old project ROOT: ---\n";
if (empty($sqlOldRoot)) {
    echo "None\n";
} else {
    foreach ($sqlOldRoot as $file) {
        echo "  📄 {$file}\n";
    }
}
echo "\n";

// ============================================
// 4. DIRECTORY STRUCTURE
// ============================================

echo "========================================\n";
echo "DATABASE DIRECTORY STRUCTURE\n";
echo "========================================\n";

$currentDbFolders = glob($currentProject . '\database\*', GLOB_ONLYDIR);
$oldDbFolders = glob($oldProject . '\database\*', GLOB_ONLYDIR);

echo "Current project subdirectories:\n";
foreach ($currentDbFolders as $folder) {
    $name = basename($folder);
    $phpCount = count(glob($folder . '\*.php'));
    $sqlCount = count(glob($folder . '\*.sql'));
    echo "  📁 {$name} ({$phpCount} PHP, {$sqlCount} SQL files)\n";
}
echo "\n";

echo "Old project subdirectories:\n";
foreach ($oldDbFolders as $folder) {
    $name = basename($folder);
    $phpCount = count(glob($folder . '\*.php'));
    $sqlCount = count(glob($folder . '\*.sql'));
    echo "  📁 {$name} ({$phpCount} PHP, {$sqlCount} SQL files)\n";
}
echo "\n";

// ============================================
// 5. SUMMARY
// ============================================

echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n";
echo "Total migrations in current: " . count($migrationsCurrent) . "\n";
echo "Total migrations in old: " . count($migrationsOld) . "\n";
echo "Missing migrations: " . count($missingInCurrent) . "\n";
echo "New migrations: " . count($newInCurrent) . "\n\n";

echo "Total seeders in current: " . count($allSeedersCurrent) . "\n";
echo "Total seeders in old: " . count($allSeedersOld) . "\n";
echo "Missing seeders: " . count($missingSeeders) . "\n";
echo "New seeders: " . count($newSeeders) . "\n\n";

echo "SQL files in current project root: " . count($sqlCurrentRoot) . "\n";
echo "SQL files in old project root: " . count($sqlOldRoot) . "\n\n";

if (count($missingInCurrent) > 0 || count($missingSeeders) > 0) {
    echo "⚠️  WARNING: There are missing files that may need to be migrated!\n";
}

echo "\n========================================\n";
echo "COMPARISON COMPLETE\n";
echo "========================================\n";

// Save results to file
$outputFile = 'database_comparison_results.txt';
$command = "php compare_databases_manual.php > {$outputFile} 2>&1";
echo "\nTo save results to file, run: {$command}\n";
