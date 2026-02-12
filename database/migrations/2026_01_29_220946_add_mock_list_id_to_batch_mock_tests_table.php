<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMockListIdToBatchMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_mock_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('mock_list_id')->nullable()->after('mock_series_id');
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
            $table->dropColumn('mock_list_id');
        });
    }
}
