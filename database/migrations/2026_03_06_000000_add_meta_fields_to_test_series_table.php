<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaFieldsToTestSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_series', function (Blueprint $table) {
            // Add meta fields if they don't exist
            if (!Schema::hasColumn('test_series', 'meta_title')) {
                $table->text('meta_title')->nullable()->after('detail');
            }
            if (!Schema::hasColumn('test_series', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('test_series', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('test_series', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
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
        Schema::table('test_series', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'slug']);
        });
    }
}
