<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchMockQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_mock_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_id');
            $table->unsignedInteger('mock_list_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('section_id'); // chapter_id from subjects table
            $table->integer('question_order')->default(0); // to maintain order
            $table->timestamps();
            
            $table->index(['batch_id', 'mock_list_id']);
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_mock_questions');
    }
}
