<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_joins', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            
            // Add batch_id column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable();
            }
            
            // Add course_id column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable();
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'status')) {
                $table->string('status')->nullable()->default('pending');
            }
            
            // Add name column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'name')) {
                $table->string('name')->nullable();
            }
            
            // Add email column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'email')) {
                $table->string('email')->nullable();
            }
            
            // Add phone column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            // Add message column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'message')) {
                $table->text('message')->nullable();
            }
            
            // Add joined_at column if it doesn't exist
            if (!Schema::hasColumn('student_joins', 'joined_at')) {
                $table->timestamp('joined_at')->nullable();
            }
        });
        
        // Add indexes separately to avoid errors
        Schema::table('student_joins', function (Blueprint $table) {
            // Drop existing indexes if they exist (to avoid duplicate errors)
            try {
                $table->dropIndex(['user_id']);
            } catch (\Exception $e) {
                // Index doesn't exist, ignore
            }
            try {
                $table->dropIndex(['batch_id']);
            } catch (\Exception $e) {
                // Index doesn't exist, ignore
            }
            try {
                $table->dropIndex(['course_id']);
            } catch (\Exception $e) {
                // Index doesn't exist, ignore
            }
            
            // Add indexes
            $table->index('user_id');
            $table->index('batch_id');
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_joins', function (Blueprint $table) {
            // Drop indexes
            try {
                $table->dropIndex(['user_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['batch_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropIndex(['course_id']);
            } catch (\Exception $e) {}
            
            // Drop columns
            $columns = ['user_id', 'batch_id', 'course_id', 'status', 'name', 'email', 'phone', 'message', 'joined_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('student_joins', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
