<?php

// Configuration
$currentProject = 'c:\Projects\vaagaacademy';
$oldProject = 'C:\Users\malik\Downloads\_public_html_vaaga_mock';

echo "========================================\n";
echo "DATABASE DIRECTORY COMPARISON\n";
echo "========================================\n\n";

echo "Current Project: {$currentProject}\n";
echo "Old Project: {$oldProject}\n\n";

// Function to get files from a directory
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

// Function to get all files recursively
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

// 1. Compare Migrations
$migrationsCurrent = getFiles($currentProject . '\database\migrations', '*.php');
$migrationsOld = getFiles($oldProject . '\database\migrations', '*.php');

echo "========================================\n";
echo "MIGRATIONS COMPARISON\n";
echo "========================================\n";
echo "Current project migrations: " . count($migrationsCurrent) . "\n";
echo "Old project migrations: " . count($migrationsOld) . "\n\n";

$missingInCurrent = array_diff($migrationsOld, $migrationsCurrent);
$newInCurrent = array_diff($migrationsCurrent, $migrationsOld);

echo "--- Migrations MISSING in current project (exist in old): ---\n";
if (empty($missingInCurrent)) {
    echo "None - all old migrations exist in current project.\n";
} else {
    foreach ($missingInCurrent as $file) {
        echo "  ❌ {$file}\n";
    }
}
echo "\n";

echo "--- NEW migrations in current project (not in old): ---\n";
if (empty($newInCurrent)) {
    echo "None - no new migrations in current project.\n";
} else {
    foreach ($newInCurrent as $file) {
        echo "  ✓ {$file}\n";
    }
}
echo "\n";

// 2. Compare Seeders
$seedersCurrent = getFiles($currentProject . '\database\seeders', '*.php');
$seedersOld = getFiles($oldProject . '\database\seeders', '*.php');

echo "========================================\n";
echo "SEEDERS COMPARISON\n";
echo "========================================\n";
echo "Current project seeders: " . count($seedersCurrent) . "\n";
echo "Old project seeders: " . count($seedersOld) . "\n\n";

$missingSeeders = array_diff($seedersOld, $seedersCurrent);
$newSeeders = array_diff($seedersCurrent, $seedersOld);

echo "--- Seeders MISSING in current project (exist in old): ---\n";
if (empty($missingSeeders)) {
    echo "None - all old seeders exist in current project.\n";
} else {
    foreach ($missingSeeders as $file) {
        echo "  ❌ {$file}\n";
    }
}
echo "\n";

echo "--- NEW seeders in current project (not in old): ---\n";
if (empty($newSeeders)) {
    echo "None - no new seeders in current project.\n";
} else {
    foreach ($newSeeders as $file) {
        echo "  ✓ {$file}\n";
    }
}
echo "\n";

// 3. Check for SQL files in database folder
$sqlCurrent = getFiles($currentProject . '\database', '*.sql');
$sqlOld = getFiles($oldProject . '\database', '*.sql');

echo "========================================\n";
echo "SQL FILES IN DATABASE FOLDER\n";
echo "========================================\n";
echo "Current project SQL files: " . count($sqlCurrent) . "\n";
echo "Old project SQL files: " . count($sqlOld) . "\n\n";

echo "--- SQL files in current project: ---\n";
if (empty($sqlCurrent)) {
    echo "None\n";
} else {
    foreach ($sqlCurrent as $file) {
        echo "  📄 {$file}\n";
    }
}
echo "\n";

echo "--- SQL files in old project: ---\n";
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

// 4. Check database directory structure
$foldersCurrent = glob($currentProject . '\database\*', GLOB_ONLYDIR);
$foldersOld = glob($oldProject . '\database\*', GLOB_ONLYDIR);

echo "========================================\n";
echo "DATABASE DIRECTORY STRUCTURE\n";
echo "========================================\n";

echo "Current project subdirectories:\n";
foreach ($foldersCurrent as $folder) {
    $name = basename($folder);
    $fileCount = count(glob($folder . '\*'));
    echo "  📁 {$name} ({$fileCount} items)\n";
}
echo "\n";

echo "Old project subdirectories:\n";
foreach ($foldersOld as $folder) {
    $name = basename($folder);
    $fileCount = count(glob($folder . '\*'));
    echo "  📁 {$name} ({$fileCount} items)\n";
}
echo "\n";

// 5. Summary
echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n";
echo "Total missing migrations: " . count($missingInCurrent) . "\n";
echo "Total new migrations: " . count($newInCurrent) . "\n";
echo "Total missing seeders: " . count($missingSeeders) . "\n";
echo "Total new seeders: " . count($newSeeders) . "\n";
echo "Total SQL files in current: " . count($sqlCurrent) . "\n";
echo "Total SQL files in old: " . count($sqlOld) . "\n";

if (count($missingInCurrent) > 0 || count($missingSeeders) > 0) {
    echo "\n⚠️  WARNING: There are missing files that may need to be migrated!\n";
}

echo "\n========================================\n";
echo "COMPARISON COMPLETE\n";
echo "========================================\n";
