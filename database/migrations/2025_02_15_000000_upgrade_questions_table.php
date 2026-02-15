<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // Add new columns for enhanced question bank functionality
            
            // JSON storage for bilingual questions (English + Hindi)
            if (!Schema::hasColumn('questions', 'question_json')) {
                $table->json('question_json')->nullable()->after('question');
            }
            
            if (!Schema::hasColumn('questions', 'question_text')) {
                $table->json('question_text')->nullable()->after('question_json');
            }
            
            // Course and chapter relationships
            if (!Schema::hasColumn('questions', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('exam_id');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('questions', 'chapter_id')) {
                $table->unsignedBigInteger('chapter_id')->nullable()->after('course_id');
                $table->foreign('chapter_id')->references('id')->on('lessons')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('questions', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('chapter_id');
            }
            
            // Question metadata
            if (!Schema::hasColumn('questions', 'marks')) {
                $table->integer('marks')->default(1)->after('correct_answer');
            }
            
            if (!Schema::hasColumn('questions', 'difficulty')) {
                $table->string('difficulty')->default('medium')->after('marks');
            }
            
            // Solution stored as JSON for bilingual support
            if (!Schema::hasColumn('questions', 'solution')) {
                $table->json('solution')->nullable()->after('difficulty');
            }
            
            // Options stored as JSON
            if (!Schema::hasColumn('questions', 'options')) {
                $table->json('options')->nullable()->after('solution');
            }
            
            // Verification workflow fields
            if (!Schema::hasColumn('questions', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('options');
            }
            
            if (!Schema::hasColumn('questions', 'verification_remarks')) {
                $table->text('verification_remarks')->nullable()->after('verification_status');
            }
            
            if (!Schema::hasColumn('questions', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verification_remarks');
                $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('questions', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
            
            // Previous year question flag
            if (!Schema::hasColumn('questions', 'is_prev_year')) {
                $table->boolean('is_prev_year')->default(false)->after('verified_at');
            }
            
            // Reference to old question ID for imports
            if (!Schema::hasColumn('questions', 'old')) {
                $table->unsignedBigInteger('old')->nullable()->after('is_prev_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $columns = [
                'question_json',
                'question_text',
                'course_id',
                'chapter_id',
                'subject_id',
                'marks',
                'difficulty',
                'solution',
                'options',
                'verification_status',
                'verification_remarks',
                'verified_by',
                'verified_at',
                'is_prev_year',
                'old'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('questions', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Drop foreign keys if they exist
            try {
                $table->dropForeign(['course_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist
            }
            
            try {
                $table->dropForeign(['chapter_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist
            }
            
            try {
                $table->dropForeign(['verified_by']);
            } catch (\Exception $e) {
                // Foreign key may not exist
            }
        });
    }
};
