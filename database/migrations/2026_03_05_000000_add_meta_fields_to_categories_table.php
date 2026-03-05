<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->text('meta_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'meta_keyword')) {
                $table->text('meta_keyword')->nullable()->after('meta_description');
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
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('categories', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('categories', 'meta_keyword')) {
                $table->dropColumn('meta_keyword');
            }
        });
    }
};
