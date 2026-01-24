<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
        }

        // Backfill existing single-course assignments into the new pivot.
        // This keeps old mock tests visible immediately after deploy.
        try {
            if (Schema::hasTable('mock_tests')) {
                $rows = DB::table('mock_tests')
                    ->whereNotNull('course_id')
                    ->select(['id', 'course_id'])
                    ->get();

                $now = now();
                foreach ($rows as $row) {
                    DB::table('mock_test_courses')->updateOrInsert(
                        ['mock_test_id' => (int) $row->id, 'course_id' => (int) $row->course_id],
                        ['created_at' => $now, 'updated_at' => $now]
                    );
                }
            }
        } catch (\Throwable $e) {
            // keep migration non-blocking in legacy environments
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_test_courses');
    }
};

