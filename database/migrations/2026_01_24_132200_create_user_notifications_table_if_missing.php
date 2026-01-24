<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_notifications')) {
            return;
        }

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('notification_id')->nullable();
            $table->tinyInteger('status')->default(0); // 0 unread, 1 read
            $table->timestamps();

            $table->index(['user_id']);
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

