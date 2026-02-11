<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bundle_student')) {
            Schema::create('bundle_student', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bundle_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('rating')->unsigned()->default(0);
                $table->timestamps();

                $table->unique(['bundle_id', 'user_id']);
                $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('bundle_student');
    }
}
