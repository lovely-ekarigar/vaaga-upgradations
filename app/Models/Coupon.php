<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function useByUser()
    {
        if (auth()->user()) {
            $count = Order::where('coupon_id', '=', $this->id)->where('user_id', '=', auth()->user()->id)->get()->count();

            return $count;
        } else {
            return 0;
        }
    }



    public function applyCoupon($id, $amount)
    {

        $finalAmount = 0;
        $coupon = Coupon::find($id);

        if ($coupon) {

            if ($coupon->status == '1') {
                if ($coupon->min_price <= $amount) {

                    if ($coupon->expires_at >= date('Y-m-d')) {

                        if ($coupon->type == '2') {
                            $finalAmount = $coupon->amount;
                        } else {
                            $finalAmount = $amount * $coupon->amount / 100;;
                        }
                    }
                }
            }
        }

        return floor($amount - $finalAmount);
    }

    public function corders()
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }
}
