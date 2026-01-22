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
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 12, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('orders', 'course_mode')) {
                $table->string('course_mode')->nullable()->after('coupon_id');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('gst');
            }
            if (!Schema::hasColumn('orders', 'aff_code')) {
                $table->string('aff_code')->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('orders', 'total_cycle')) {
                $table->integer('total_cycle')->nullable()->after('aff_code');
            }
            if (!Schema::hasColumn('orders', 'paid_cycle')) {
                $table->integer('paid_cycle')->default(0)->after('total_cycle');
            }
            if (!Schema::hasColumn('orders', 'end_date')) {
                $table->date('end_date')->nullable()->after('paid_cycle');
            }
            if (!Schema::hasColumn('orders', 'order_id')) {
                $table->string('order_id')->nullable()->after('reference_no')->comment('Razorpay Order ID');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('orders', 'course_mode')) {
                $table->dropColumn('course_mode');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('orders', 'aff_code')) {
                $table->dropColumn('aff_code');
            }
            if (Schema::hasColumn('orders', 'total_cycle')) {
                $table->dropColumn('total_cycle');
            }
            if (Schema::hasColumn('orders', 'paid_cycle')) {
                $table->dropColumn('paid_cycle');
            }
            if (Schema::hasColumn('orders', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('orders', 'order_id')) {
                $table->dropColumn('order_id');
            }
        });
    }
};
