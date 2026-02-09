<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('test_list')) {
            Schema::create('test_list', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('test_series_id')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('total_marks')->default(0);
                $table->integer('duration')->default(0)->comment('Duration in minutes');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                $table->index(['test_series_id']);
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
        Schema::dropIfExists('test_list');
    }
}
