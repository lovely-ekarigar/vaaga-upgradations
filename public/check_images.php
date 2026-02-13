<?php
// Check storage directories and images

echo "=== Checking Storage Setup ===\n\n";

// Check if storage link exists
$publicStorage = __DIR__ . '/storage';
$appStorage = dirname(__DIR__) . '/storage/app/public';

echo "1. Public/storage link:\n";
if (is_link($publicStorage)) {
    echo "   ✓ Link exists -> " . readlink($publicStorage) . "\n";
} elseif (is_dir($publicStorage)) {
    echo "   ⚠ Directory exists (should be a symlink)\n";
} else {
    echo "   ✗ Missing! Need to create storage link\n";
}

echo "\n2. Storage/app/public folder:\n";
if (is_dir($appStorage)) {
    echo "   ✓ Exists\n";
    $files = glob($appStorage . '/uploads/*');
    echo "   Found " . count($files) . " files in uploads\n";
} else {
    echo "   ✗ Missing!\n";
}

echo "\n3. Checking for broken image paths in homepage:\n";

// Common image paths that might be missing
$imagePaths = [
    '/storage/uploads/',
    '/newassets/img/',
    '/frontend/assets/img/',
];

foreach ($imagePaths as $path) {
    $fullPath = __DIR__ . $path;
    if (is_dir($fullPath)) {
        echo "   ✓ $path exists\n";
    } else {
        echo "   ✗ $path missing\n";
    }
}

echo "\n=== Suggested Fix ===\n";
if (!is_link($publicStorage) && !is_dir($publicStorage)) {
    echo "Run: php artisan storage:link\n";
}

// Check old codebase for images
$oldCodebase = 'C:\\Users\\malik\\Downloads\\_public_html_vaaga_mock\\storage\\app\\public';
echo "\n4. Old codebase storage:\n";
if (is_dir($oldCodebase)) {
    echo "   ✓ Old storage exists\n";
    $files = glob($oldCodebase . '/uploads/*');
    echo "   Found " . count($files) . " files in old uploads\n";
} else {
    echo "   ✗ Not found\n";
}
