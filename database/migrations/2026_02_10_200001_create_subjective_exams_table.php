<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectiveExamsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subjective_exams')) {
            return;
        }

        Schema::create('subjective_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->string('title');
            $table->date('exam_date')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();

            $table->index(['batch_id']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjective_exams');
    }
}
