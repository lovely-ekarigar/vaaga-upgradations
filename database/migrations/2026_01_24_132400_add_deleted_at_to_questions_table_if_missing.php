<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToQuestionsTableIfMissing extends Migration
{
    /**
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
     * @return void
     */
    public function down()
    {
        // no-op
    }
}

