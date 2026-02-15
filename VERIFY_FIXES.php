<?php
/**
 * VERIFY ALL FIXES ARE APPLIED CORRECTLY
 * Run: php VERIFY_FIXES.php
 */

echo "======================================================\n";
echo "VERIFYING ALL FIXES\n";
echo "======================================================\n\n";

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$allGood = true;

// 1. Check CKEditor Route
echo "1. Checking CKEditor Upload Route...\n";
try {
    $route = route('admin.uploadImageCkEditor');
    echo "   ✅ Route 'admin.uploadImageCkEditor' exists: $route\n";
} catch (Exception $e) {
    echo "   ❌ Route 'admin.uploadImageCkEditor' NOT FOUND!\n";
    $allGood = false;
}

// 2. Check Courses Demo Route
echo "\n2. Checking Courses Demo Route...\n";
try {
    $route = route('courses.demo', 'test-course');
    echo "   ✅ Route 'courses.demo' exists: $route\n";
} catch (Exception $e) {
    echo "   ❌ Route 'courses.demo' NOT FOUND!\n";
    $allGood = false;
}

// 3. Check DemoRequest Model
echo "\n3. Checking DemoRequest Model...\n";
if (class_exists('App\Models\DemoRequest')) {
    echo "   ✅ DemoRequest model exists\n";
} else {
    echo "   ❌ DemoRequest model NOT FOUND!\n";
    $allGood = false;
}

// 4. Check Database Tables
echo "\n4. Checking Database Tables...\n";
$tables = [
    'demo_requests',
    'mock_series',
    'mock_list',
    'mock_tests',
    'subjects' // Should have mock_id column
];

foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        echo "   ✅ Table '$table' exists\n";
    } else {
        echo "   ❌ Table '$table' NOT FOUND!\n";
        $allGood = false;
    }
}

// 5. Check subjects.mock_id column
echo "\n5. Checking subjects.mock_id column...\n";
if (Illuminate\Support\Facades\Schema::hasColumn('subjects', 'mock_id')) {
    echo "   ✅ Column 'subjects.mock_id' exists\n";
} else {
    echo "   ❌ Column 'subjects.mock_id' NOT FOUND! Run ADD_MISSING_COLUMNS.php\n";
    $allGood = false;
}

// 6. Check CKEditor views
echo "\n6. Checking CKEditor in Views...\n";
$views = [
    'resources/views/backend/lessons/create.blade.php',
    'resources/views/backend/lessons/edit.blade.php',
];

foreach ($views as $view) {
    $content = file_get_contents(__DIR__ . '/' . $view);
    if (strpos($content, 'CKEDITOR.replace') !== false) {
        echo "   ✅ CKEditor configured in " . basename($view) . "\n";
    } else {
        echo "   ❌ CKEditor NOT found in " . basename($view) . "\n";
        $allGood = false;
    }
}

// 7. Check Demo Form
echo "\n7. Checking Demo Form...\n";
$courseView = file_get_contents(__DIR__ . '/resources/views/frontend/course.blade.php');
if (strpos($courseView, "route('courses.demo'") !== false) {
    echo "   ✅ Demo form action is set correctly\n";
} else {
    echo "   ❌ Demo form action NOT set correctly!\n";
    $allGood = false;
}

// Summary
echo "\n======================================================\n";
if ($allGood) {
    echo "✅ ALL FIXES VERIFIED SUCCESSFULLY!\n";
    echo "======================================================\n";
    echo "\nNext steps:\n";
    echo "1. Clear caches: php artisan view:clear\n";
    echo "2. Test Book Free Demo form on course page\n";
    echo "3. Test CKEditor in Lessons → Create/Edit\n";
    exit(0);
} else {
    echo "❌ SOME FIXES ARE MISSING!\n";
    echo "======================================================\n";
    exit(1);
}
