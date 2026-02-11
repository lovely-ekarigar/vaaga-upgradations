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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('total_marks')->nullable()->default(100);
            $table->timestamp('due_date')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->text('instructions')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index('course_id');
            $table->index('lesson_id');
            $table->index('batch_id');
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
