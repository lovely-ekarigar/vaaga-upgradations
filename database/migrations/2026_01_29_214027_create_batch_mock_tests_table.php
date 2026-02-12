<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_mock_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('mock_series_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Remove foreign key constraints for now to avoid issues
            // $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            // $table->foreign('mock_series_id')->references('id')->on('mock_series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_mock_tests');
    }
}
