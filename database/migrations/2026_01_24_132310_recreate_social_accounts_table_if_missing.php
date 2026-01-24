<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateSocialAccountsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unsigned();
                $table->string('provider', 32);
                $table->string('provider_id');
                $table->text('token')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamps();

                $table->index(['user_id']);
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        // no-op
    }
}

