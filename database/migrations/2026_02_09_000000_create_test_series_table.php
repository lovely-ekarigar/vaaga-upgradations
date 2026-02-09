<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('test_series')) {
            Schema::create('test_series', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('detail')->nullable();
                $table->unsignedBigInteger('course_id')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->decimal('price', 10, 2)->default(0);
                $table->decimal('offer_price', 10, 2)->default(0);
                $table->integer('total_test')->default(0);
                $table->string('validity')->nullable();
                $table->string('difficulty')->nullable();
                $table->timestamps();

                $table->index(['course_id']);
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
        Schema::dropIfExists('test_series');
    }
}
