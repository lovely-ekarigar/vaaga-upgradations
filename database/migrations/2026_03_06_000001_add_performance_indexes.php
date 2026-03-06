<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add performance indexes for live tracking and exam systems
     */
    public function up(): void
    {
        // MyExam table - for live exam tracking performance
        Schema::table('my_exams', function (Blueprint $table) {
            // Composite index for live tracking query (runningStatusExam)
            if (!$this->hasIndex('my_exams', 'idx_live_tracking')) {
                $table->index(['status', 'last_ping'], 'idx_live_tracking');
            }
            
            // Index for user exam history
            if (!$this->hasIndex('my_exams', 'idx_user_exams')) {
                $table->index(['user_id', 'status'], 'idx_user_exams');
            }
            
            // Index for batch mock test queries
            if (!$this->hasIndex('my_exams', 'idx_batch_mock_status')) {
                $table->index(['batch_mock_test_id', 'status'], 'idx_batch_mock_status');
            }
        });

        // Recordings table - for class recording queries
        Schema::table('recordings', function (Blueprint $table) {
            // Composite index for parent + created (commitment page)
            if (!$this->hasIndex('recordings', 'idx_parent_created')) {
                $table->index(['parent', 'created_at'], 'idx_parent_created');
            }
            
            // Index for batch recording queries
            if (!$this->hasIndex('recordings', 'idx_batch_created')) {
                $table->index(['batch_id', 'created_at'], 'idx_batch_created');
            }
            
            // Index for API class ID lookups
            if (!$this->hasIndex('recordings', 'idx_api_class_id')) {
                $table->index('api_class_id', 'idx_api_class_id');
            }
        });

        // Student joins table - for attendance tracking
        Schema::table('student_joins', function (Blueprint $table) {
            // Composite index for attendance queries
            if (!$this->hasIndex('student_joins', 'idx_user_batch_date')) {
                $table->index(['user_id', 'batch_id', 'created_at'], 'idx_user_batch_date');
            }
            
            // Index for batch joins
            if (!$this->hasIndex('student_joins', 'idx_batch_created')) {
                $table->index(['batch_id', 'created_at'], 'idx_batch_created');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_exams', function (Blueprint $table) {
            $table->dropIndex('idx_live_tracking');
            $table->dropIndex('idx_user_exams');
            $table->dropIndex('idx_batch_mock_status');
        });

        Schema::table('recordings', function (Blueprint $table) {
            $table->dropIndex('idx_parent_created');
            $table->dropIndex('idx_batch_created');
            $table->dropIndex('idx_api_class_id');
        });

        Schema::table('student_joins', function (Blueprint $table) {
            $table->dropIndex('idx_user_batch_date');
            $table->dropIndex('idx_batch_created');
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }
        return false;
    }
};
