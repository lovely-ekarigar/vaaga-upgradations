<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\User;
class TestSeriesPurchase extends Model
{
  

    // Table name (optional if it follows Laravel naming conventions)
    protected $table = 'test_series_purchases';

    // Primary key (optional if 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'test_series_id',
        'amount',
        'status',
        'payment_status',
        'rzp_order_id',
        'rzp_payment_id',
        'course_id',
        'coupon_id',
        'discount'
    ];

    // Casts
    protected $casts = [
        'amount' => 'double',
    ];

    // Timestamps (enabled by default)
    public $timestamps = true;

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function testSeries()
    {
        return $this->belongsTo(TestSeries::class, 'test_series_id');
    }
}
