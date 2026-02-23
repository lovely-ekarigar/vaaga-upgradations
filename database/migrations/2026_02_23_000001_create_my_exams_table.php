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
        if (!Schema::hasTable('my_exams')) {
            Schema::create('my_exams', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('batch_id')->nullable();
                $table->unsignedBigInteger('exam_id')->nullable();
                $table->json('questions')->nullable();
                $table->json('answers')->nullable();
                $table->decimal('total_marks', 8, 2)->default(0);
                $table->decimal('marks_obtained', 8, 2)->default(0);
                $table->enum('status', ['started', 'completed', 'abandoned'])->default('started');
                $table->unsignedBigInteger('submitted_by')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedBigInteger('batch_exam_id')->nullable();
                $table->unsignedBigInteger('batch_mock_test_id')->nullable();
                $table->unsignedBigInteger('test_series_purchase_id')->nullable();
                $table->dateTime('exam_date_time')->nullable();
                $table->tinyInteger('is_final')->default(0);
                $table->integer('duration')->default(0);
                $table->integer('time_spent')->default(0);
                $table->string('photo')->nullable();
                $table->timestamp('last_ping')->nullable();
                $table->integer('current_question')->default(0);
                $table->timestamp('start_time')->nullable();
                $table->timestamp('end_time')->nullable();
                $table->text('device_info')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['user_id']);
                $table->index(['exam_id']);
                $table->index(['status']);
                $table->index(['batch_id']);
                $table->index(['batch_mock_test_id']);
                $table->index(['test_series_purchase_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_exams');
    }
};
