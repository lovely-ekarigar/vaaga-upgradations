<?php
/**
 * Run this script to create the missing mock_tests table
 * Run: php run_mock_migration.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Checking and creating mock_tests table...\n";

try {
    // 1. Create mock_tests table
    if (!Schema::hasTable('mock_tests')) {
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('published')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
            $table->index(['course_id']);
            $table->index(['created_by']);
            $table->index(['published']);
        });
        echo "✓ mock_tests table created\n";
    } else {
        echo "✓ mock_tests table already exists\n";
    }

    // 2. Create mock_test_schedules table
    if (!Schema::hasTable('mock_test_schedules')) {
        Schema::create('mock_test_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mock_test_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->date('scheduled_date');
            $table->string('timezone')->default('Asia/Kolkata');
            $table->enum('status', ['scheduled', 'active', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamp('rescheduled_at')->nullable();
            $table->unsignedBigInteger('rescheduled_by')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->timestamps();

            $table->index(['mock_test_id']);
            $table->index(['batch_id']);
            $table->index(['scheduled_date']);
            $table->index(['status']);
        });
        echo "✓ mock_test_schedules table created\n";
    } else {
        echo "✓ mock_test_schedules table already exists\n";
    }

    // 3. Create mock_test_question pivot table
    if (!Schema::hasTable('mock_test_question')) {
        Schema::create('mock_test_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mock_test_id');
            $table->unsignedBigInteger('question_id');
            $table->integer('sequence')->default(0);
            $table->timestamps();

            $table->unique(['mock_test_id', 'question_id']);
            $table->index(['mock_test_id']);
            $table->index(['question_id']);
        });
        echo "✓ mock_test_question table created\n";
    } else {
        echo "✓ mock_test_question table already exists\n";
    }

    // 4. Create mock_test_courses table
    if (!Schema::hasTable('mock_test_courses')) {
        Schema::create('mock_test_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mock_test_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->unique(['mock_test_id', 'course_id']);
            $table->index(['mock_test_id']);
            $table->index(['course_id']);
        });
        echo "✓ mock_test_courses table created\n";
    } else {
        echo "✓ mock_test_courses table already exists\n";
    }

    // Insert sample data if empty
    $count = DB::table('mock_tests')->count();
    if ($count == 0) {
        DB::table('mock_tests')->insert([
            'id' => 106,
            'title' => 'Sample Mock Test',
            'slug' => 'sample-mock-test',
            'description' => 'Test description',
            'published' => 1,
            'created_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✓ Sample mock test inserted (ID: 106)\n";
    }

    echo "\n✅ All mock test tables created successfully!\n";
    echo "Refresh the page to see the mock test questions.\n";

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
