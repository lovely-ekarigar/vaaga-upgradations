<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndDateToBatchMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_mock_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('batch_mock_tests', 'end_date')) {
                $table->dateTime('end_date')->nullable()->after('scheduled_at');
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
        Schema::table('batch_mock_tests', function (Blueprint $table) {
            if (Schema::hasColumn('batch_mock_tests', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
}
