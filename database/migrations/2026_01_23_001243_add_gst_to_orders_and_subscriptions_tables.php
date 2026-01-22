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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'gst')) {
                $table->decimal('gst', 12, 2)->default(0)->after('amount');
            }
        });

        if (Schema::hasTable('subscriptions') && !Schema::hasColumn('subscriptions', 'gst')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->decimal('gst', 12, 2)->default(0)->after('amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'gst')) {
                $table->dropColumn('gst');
            }
        });

        if (Schema::hasTable('subscriptions') && Schema::hasColumn('subscriptions', 'gst')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('gst');
            });
        }
    }
};
