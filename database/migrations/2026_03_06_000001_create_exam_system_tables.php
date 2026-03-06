<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Exam Users table - links main app users to exam system
        if (!Schema::hasTable('exam_users')) {
            Schema::create('exam_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sync_id')->index(); // Links to main users.id
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->timestamps();
            });
        }

        // Exam Batches table - links main app batches to exam system
        if (!Schema::hasTable('exam_batches')) {
            Schema::create('exam_batches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sync_id')->index(); // Links to main batches.id
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }

        // Batch Users - many-to-many relationship between exam_users and exam_batches
        if (!Schema::hasTable('exam_batch_users')) {
            Schema::create('exam_batch_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // exam_users.id
                $table->unsignedBigInteger('batch_id'); // exam_batches.id
                $table->timestamps();
                
                $table->index(['user_id', 'batch_id']);
            });
        }

        // Tests table
        if (!Schema::hasTable('exam_tests')) {
            Schema::create('exam_tests', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('duration')->default(0); // in minutes
                $table->timestamps();
            });
        }

        // Batch Tests - links tests to batches
        if (!Schema::hasTable('exam_batch_tests')) {
            Schema::create('exam_batch_tests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_id'); // exam_batches.id
                $table->unsignedBigInteger('test_id'); // exam_tests.id
                $table->timestamps();
                
                $table->index(['batch_id', 'test_id']);
            });
        }

        // My Tests - student test attempts
        if (!Schema::hasTable('exam_my_tests')) {
            Schema::create('exam_my_tests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // exam_users.id
                $table->unsignedBigInteger('test_id'); // exam_tests.id
                $table->enum('status', ['started', 'completed', 'submitted'])->default('started');
                $table->timestamps();
                
                $table->index(['user_id', 'test_id']);
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
        Schema::dropIfExists('exam_my_tests');
        Schema::dropIfExists('exam_batch_tests');
        Schema::dropIfExists('exam_tests');
        Schema::dropIfExists('exam_batch_users');
        Schema::dropIfExists('exam_batches');
        Schema::dropIfExists('exam_users');
    }
}
