<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('questions')) {
            return;
        }

        if (!Schema::hasColumn('questions', 'deleted_at')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->softDeletes();
                $table->index(['deleted_at']);
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
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'deleted_at')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
