<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('test_list')) {
            Schema::table('test_list', function (Blueprint $table) {
                if (!Schema::hasColumn('test_list', 'test_type')) {
                    $table->enum('test_type', ['regular', 'mock'])->default('regular')->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('test_list')) {
            Schema::table('test_list', function (Blueprint $table) {
                if (Schema::hasColumn('test_list', 'test_type')) {
                    $table->dropColumn('test_type');
                }
            });
        }
    }
};
