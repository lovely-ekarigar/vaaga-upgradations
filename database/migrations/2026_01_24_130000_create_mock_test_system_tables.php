<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMockTestSystemTables extends Migration
{
    /**
     * Run the migrations - Creates ALL mock test tables in one migration
     *
     * @return void
     */
    public function up()
    {
        // 1. Create mock_tests table
        if(! Schema::hasTable('mock_tests')) {
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
        }

        // 2. Create mock_test_schedules table
        if(! Schema::hasTable('mock_test_schedules')) {
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
        }

        // 3. Create mock_test_responses table
        if(! Schema::hasTable('mock_test_responses')) {
            Schema::create('mock_test_responses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mock_test_schedule_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('response_option_id')->nullable();
                $table->tinyInteger('is_correct')->default(0);
                $table->integer('time_taken')->nullable()->comment('Time in seconds');
                $table->timestamps();

                $table->index(['mock_test_schedule_id']);
                $table->index(['student_id']);
                $table->index(['question_id']);
            });
        }

        // 4. Create mock_test_results table
        if(! Schema::hasTable('mock_test_results')) {
            Schema::create('mock_test_results', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mock_test_schedule_id');
                $table->unsignedBigInteger('student_id');
                $table->integer('total_questions')->default(0);
                $table->integer('total_correct')->default(0);
                $table->integer('total_incorrect')->default(0);
                $table->integer('total_unattempted')->default(0);
                $table->decimal('score', 8, 2)->default(0);
                $table->decimal('percentage', 5, 2)->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index(['mock_test_schedule_id']);
                $table->index(['student_id']);
                $table->unique(['mock_test_schedule_id', 'student_id']);
            });
        }

        // 5. Create mock_test_question_reports table
        if(! Schema::hasTable('mock_test_question_reports')) {
            Schema::create('mock_test_question_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('reported_by');
                $table->text('report_reason');
                $table->enum('status', ['pending', 'resolved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->index(['question_id']);
                $table->index(['reported_by']);
                $table->index(['status']);
            });
        }

        // 6. Create mock_test_question pivot table
        if(! Schema::hasTable('mock_test_question')) {
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
        }

        // 7. Add question_json column to questions table if not exists
        if(Schema::hasTable('questions') && !Schema::hasColumn('questions', 'question_json')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->longText('question_json')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mock_test_question');
        Schema::dropIfExists('mock_test_question_reports');
        Schema::dropIfExists('mock_test_results');
        Schema::dropIfExists('mock_test_responses');
        Schema::dropIfExists('mock_test_schedules');
        Schema::dropIfExists('mock_tests');
        
        if(Schema::hasColumn('questions', 'question_json')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('question_json');
            });
        }
    }
}
