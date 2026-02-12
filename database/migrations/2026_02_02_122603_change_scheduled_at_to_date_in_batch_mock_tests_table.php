<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeScheduledAtToDateInBatchMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_mock_tests', function (Blueprint $table) {
            // Change scheduled_at from timestamp to date
            $table->date('scheduled_at')->nullable()->change();
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
            // Revert back to timestamp
            $table->timestamp('scheduled_at')->nullable()->change();
        });
    }
}
