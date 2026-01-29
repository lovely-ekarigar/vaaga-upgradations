<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStatusToMockTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('mock_tests') && !Schema::hasColumn('mock_tests', 'status')) {
            Schema::table('mock_tests', function (Blueprint $table) {
                $table->string('status', 20)->default('draft')->after('published');
                $table->index(['status']);
            });
            // Backfill: published=1 -> published, else draft
            DB::table('mock_tests')->where('published', 1)->update(['status' => 'published']);
            DB::table('mock_tests')->where('published', 0)->update(['status' => 'draft']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('mock_tests') && Schema::hasColumn('mock_tests', 'status')) {
            Schema::table('mock_tests', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            });
        }
    }
}
