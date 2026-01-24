<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateSubscriptionsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subscriptions')) {
            return;
        }

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->tinyInteger('status')->default(0); // 0 inactive, 1 active
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('gst_number')->nullable();
            $table->decimal('gst_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['course_id']);
            $table->index(['status']);
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

