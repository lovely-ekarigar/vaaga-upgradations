<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateTestsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tests')) {
            return;
        }

        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('lesson_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('published')->nullable()->default(0);
            $table->string('slug')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
            $table->index(['course_id']);
            $table->index(['lesson_id']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        // no-op
    }
}

