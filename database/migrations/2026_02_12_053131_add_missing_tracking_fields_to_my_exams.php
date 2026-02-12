<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingTrackingFieldsToMyExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_exams', function (Blueprint $table) {
            if (!Schema::hasColumn('my_exams', 'last_ping')) {
                $table->timestamp('last_ping')->nullable();
            }
            if (!Schema::hasColumn('my_exams', 'start_time')) {
                $table->timestamp('start_time')->nullable();
            }
            if (!Schema::hasColumn('my_exams', 'end_time')) {
                $table->timestamp('end_time')->nullable();
            }
            if (!Schema::hasColumn('my_exams', 'device_info')) {
                $table->text('device_info')->nullable();
            }
            if (!Schema::hasColumn('my_exams', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            if (!Schema::hasColumn('my_exams', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
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
            if (Schema::hasColumn('my_exams', 'last_ping')) {
                $table->dropColumn('last_ping');
            }
            if (Schema::hasColumn('my_exams', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('my_exams', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('my_exams', 'device_info')) {
                $table->dropColumn('device_info');
            }
            if (Schema::hasColumn('my_exams', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
            if (Schema::hasColumn('my_exams', 'user_agent')) {
                $table->dropColumn('user_agent');
            }
        });
    }
}
