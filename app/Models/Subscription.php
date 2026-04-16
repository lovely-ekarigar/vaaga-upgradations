<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'cycle_no',
        'course_id',
        'amount',
        'discount',
        'gst',
        'coupon_id',
        'status',
        'end_date',
        'renew_date',
        'reference_no',
        'payment_ref',
        'transaction_id',
        'course_mode',
    ];

    protected $casts = [
        'status' => 'integer',
        'cycle_no' => 'integer',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'gst' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
