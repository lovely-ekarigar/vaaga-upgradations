<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectiveExamUploadsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subjective_exam_uploads')) {
            return;
        }

        Schema::create('subjective_exam_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exam_id');
            $table->string('file');
            $table->decimal('marks', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['batch_id']);
            $table->index(['user_id']);
            $table->index(['exam_id']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjective_exam_uploads');
    }
}
