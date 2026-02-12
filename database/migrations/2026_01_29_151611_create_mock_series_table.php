<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMockSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mock_series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 550);
            $table->text('detail')->nullable();
            $table->unsignedInteger('course_id');
            $table->enum('status', ['0', '1'])->default('0');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('offer_price', 10, 2)->default(0);
            $table->integer('total_test')->default(0);
            $table->string('validity', 250)->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->timestamps();
            
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mock_series');
    }
}
