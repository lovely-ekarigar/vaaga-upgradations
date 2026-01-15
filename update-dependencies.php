<?php
/**
 * Script to update Laravel dependencies while suppressing PHP 8.1 deprecation warnings
 * Run this with: php update-dependencies.php
 */

// Suppress deprecation warnings for PHP 8.1
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

echo "Starting Laravel dependency update...\n";
echo "Current PHP version: " . PHP_VERSION . "\n\n";

// Check if composer is available
$composerPath = __DIR__ . '/vendor/composer/composer/bin/composer';
if (!file_exists($composerPath)) {
    echo "ERROR: Composer not found at $composerPath\n";
    echo "Please install composer first or ensure vendor directory exists.\n";
    exit(1);
}

// Step 1: Update to Laravel 8 first (safer approach)
echo "Step 1: Updating composer.json for Laravel 8.0...\n";
echo "Note: This script assumes composer.json is already updated.\n\n";

// Step 2: Run composer update
echo "Step 2: Running composer update (this may take several minutes)...\n";
echo "Suppressing deprecation warnings during update...\n\n";

$command = "php \"$composerPath\" update --no-interaction --prefer-dist --optimize-autoloader 2>&1";
$output = [];
$returnVar = 0;

exec($command, $output, $returnVar);

// Filter out deprecation warnings from output
$filteredOutput = array_filter($output, function($line) {
    return strpos($line, 'Deprecated:') === false && 
           strpos($line, 'Return type of') === false &&
           strpos($line, 'should either be compatible') === false;
});

foreach ($filteredOutput as $line) {
    echo $line . "\n";
}

if ($returnVar !== 0) {
    echo "\nERROR: Composer update failed with exit code $returnVar\n";
    echo "Please review the errors above and fix dependency conflicts.\n";
    exit(1);
}

echo "\n✓ Composer update completed successfully!\n";
echo "\nNext steps:\n";
echo "1. Run: php artisan config:clear\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan route:clear\n";
echo "4. Test your application: php artisan serve\n";
