<?php
/**
 * ADD MISSING COLUMNS FOR MOCK SYSTEM
 * 
 * Usage: php ADD_MISSING_COLUMNS.php
 */

echo "======================================================\n";
echo "ADDING MISSING COLUMNS FOR MOCK SYSTEM\n";
echo "======================================================\n\n";

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Add mock_id to subjects table
if (Schema::hasTable('subjects') && !Schema::hasColumn('subjects', 'mock_id')) {
    Schema::table('subjects', function (Blueprint $table) {
        $table->unsignedBigInteger('mock_id')->nullable();
    });
    echo "✅ Added column: subjects.mock_id\n";
} else {
    echo "ℹ️  Column already exists or table missing: subjects.mock_id\n";
}

// Add mock_sort_order to courses table
if (Schema::hasTable('courses') && !Schema::hasColumn('courses', 'mock_sort_order')) {
    Schema::table('courses', function (Blueprint $table) {
        $table->integer('mock_sort_order')->default(0);
    });
    echo "✅ Added column: courses.mock_sort_order\n";
} else {
    echo "ℹ️  Column already exists or table missing: courses.mock_sort_order\n";
}

// Add batch_mock_test_id to my_exams table
if (Schema::hasTable('my_exams') && !Schema::hasColumn('my_exams', 'batch_mock_test_id')) {
    Schema::table('my_exams', function (Blueprint $table) {
        $table->unsignedBigInteger('batch_mock_test_id')->nullable();
    });
    echo "✅ Added column: my_exams.batch_mock_test_id\n";
} else {
    echo "ℹ️  Column already exists or table missing: my_exams.batch_mock_test_id\n";
}

echo "\n======================================================\n";
echo "✅ COLUMN FIX COMPLETE!\n";
echo "======================================================\n";
