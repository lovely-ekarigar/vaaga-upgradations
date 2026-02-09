<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSeriesPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('test_series_purchases')) {
            Schema::create('test_series_purchases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('test_series_id')->nullable();
                $table->unsignedBigInteger('course_id')->nullable();
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('status')->nullable();
                $table->string('payment_status')->default('pending');
                $table->string('rzp_order_id')->nullable();
                $table->string('rzp_payment_id')->nullable();
                $table->timestamps();

                $table->index(['user_id']);
                $table->index(['test_series_id']);
                $table->index(['course_id']);
                $table->index(['payment_status']);
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
        Schema::dropIfExists('test_series_purchases');
    }
}
