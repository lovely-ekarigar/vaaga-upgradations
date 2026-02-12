<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchMockTestIdToMyExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_exams', function (Blueprint $table) {
            if (!Schema::hasColumn('my_exams', 'batch_mock_test_id')) {
                $table->unsignedBigInteger('batch_mock_test_id')->nullable()->after('batch_exam_id');
            }
            if (!Schema::hasColumn('my_exams', 'duration')) {
                $table->integer('duration')->nullable()->after('exam_date_time');
            }
            if (!Schema::hasColumn('my_exams', 'time_spent')) {
                $table->integer('time_spent')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('my_exams', 'photo')) {
                $table->string('photo')->nullable()->after('time_spent');
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
            if (Schema::hasColumn('my_exams', 'batch_mock_test_id')) {
                $table->dropColumn('batch_mock_test_id');
            }
            if (Schema::hasColumn('my_exams', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('my_exams', 'time_spent')) {
                $table->dropColumn('time_spent');
            }
            if (Schema::hasColumn('my_exams', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
}
