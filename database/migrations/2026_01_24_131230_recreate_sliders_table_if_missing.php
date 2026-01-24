<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateSlidersTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sliders')) {
            Schema::create('sliders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->longText('content')->nullable();
                $table->text('bg_image')->nullable();
                $table->integer('overlay')->default(0)->nullable();
                $table->integer('sequence');
                $table->integer('status')->default(1)->comment('1 - enabled, 0 - disabled');
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

