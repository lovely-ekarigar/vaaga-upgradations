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
        if (!Schema::hasTable('subject_chapters')) {
            Schema::create('subject_chapters', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('lesson_id')->nullable();
                $table->unsignedBigInteger('test_id')->nullable();
                $table->integer('question_count')->default(0);
                $table->timestamps();

                $table->index(['subject_id']);
                $table->index(['lesson_id']);
                $table->index(['test_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_chapters');
    }
};
