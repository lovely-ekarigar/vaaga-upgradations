<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMockListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mock_list', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mock_series_id');
            $table->string('name', 550);
            $table->text('description')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('duration')->default(0);
            $table->text('sections')->nullable();
            $table->text('section_questions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->enum('is_prev_year', ['0', '1'])->default('0');
            $table->timestamps();
            
            $table->foreign('mock_series_id')->references('id')->on('mock_series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mock_list');
    }
}
