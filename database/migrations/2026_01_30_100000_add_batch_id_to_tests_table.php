<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchIdToTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tests') && !Schema::hasColumn('tests', 'batch_id')) {
            Schema::table('tests', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('lesson_id');
                $table->index(['batch_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('tests') && Schema::hasColumn('tests', 'batch_id')) {
            Schema::table('tests', function (Blueprint $table) {
                $table->dropIndex(['batch_id']);
                $table->dropColumn('batch_id');
            });
        }
    }
}
