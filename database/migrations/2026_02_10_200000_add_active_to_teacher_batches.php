<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveToTeacherBatches extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_batches', 'active')) {
                $table->tinyInteger('active')->default(1)->after('bid');
            }
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_batches', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
