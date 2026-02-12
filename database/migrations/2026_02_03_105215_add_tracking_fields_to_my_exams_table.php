<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingFieldsToMyExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_exams', function (Blueprint $table) {
            $table->integer('last_ping')->nullable()->after('time_spent')->comment('Unix timestamp of last activity');
            $table->integer('current_question')->nullable()->after('last_ping')->comment('Current question number being viewed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_exams', function (Blueprint $table) {
            $table->dropColumn(['last_ping', 'current_question']);
        });
    }
}
