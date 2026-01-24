<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateSponsorsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sponsors')) {
            Schema::create('sponsors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('logo')->nullable();
                $table->text('link')->nullable();
                $table->integer('status')->default(1)->comment('0 - disabled, 1 - enabled');
                $table->timestamps();
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        // Intentionally no-op: this is a safety net for broken local DBs.
    }
}

