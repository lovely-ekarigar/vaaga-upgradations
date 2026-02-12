<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToBatchMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_mock_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('batch_mock_tests', 'is_active')) {
                $table->tinyInteger('is_active')->default(0)->after('sort_order');
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
            if (Schema::hasColumn('batch_mock_tests', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
}
