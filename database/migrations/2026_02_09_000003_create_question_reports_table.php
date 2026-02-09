<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('question_reports')) {
            Schema::create('question_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('question_id')->nullable();
                $table->unsignedBigInteger('test_id')->nullable();
                $table->text('report_reason')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();

                $table->index(['user_id']);
                $table->index(['question_id']);
                $table->index(['test_id']);
                $table->index(['status']);
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
        Schema::dropIfExists('question_reports');
    }
}
