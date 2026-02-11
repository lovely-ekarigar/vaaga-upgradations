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
        Schema::create('demo_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('demo_request_id')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('batch_id');
            $table->index('demo_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_feedbacks');
    }
};
