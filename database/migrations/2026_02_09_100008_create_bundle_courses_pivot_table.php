<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleCoursesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bundle_courses')) {
            Schema::create('bundle_courses', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bundle_id')->unsigned();
                $table->integer('course_id')->unsigned();
                $table->timestamps();

                $table->unique(['bundle_id', 'course_id']);
                $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('cascade');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
        Schema::dropIfExists('bundle_courses');
    }
}
