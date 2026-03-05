<?php
/**
 * Clear all caches
 * Run: php clear_cache.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Clearing caches...\n";

// Clear view cache
$viewPath = __DIR__ . '/storage/framework/views';
if (is_dir($viewPath)) {
    $files = glob($viewPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ View cache cleared\n";
}

// Clear application cache
Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "✓ Application cache cleared\n";

Illuminate\Support\Facades\Artisan::call('config:clear');
echo "✓ Config cache cleared\n";

echo "\nAll caches cleared!\n";
echo "Now refresh your browser page with Ctrl+F5\n";
