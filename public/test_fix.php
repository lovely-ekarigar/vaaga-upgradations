<?php
// Simple test to check if routes are working
echo "Testing Laravel application...\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";

// Check if key files exist
$files = [
    '../routes/web.php',
    '../resources/views/welcome.blade.php',
    '../app/Http/Controllers/Frontend/HomeController.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ Found: $file\n";
    } else {
        echo "✗ Missing: $file\n";
    }
}

echo "\nTest complete!\n";
