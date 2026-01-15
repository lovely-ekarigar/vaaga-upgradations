<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
  
 public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Check if subscription is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        if (isset($this->end_date) && $this->end_date) {
            return Carbon::parse($this->end_date)->isPast();
        }
        // If no end_date, check status
        return $this->status == 2; // Assuming 2 means expired
    }

    /**
     * Check if subscription is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status == 0;
    }

    /**
     * Get next renewal date accessor
     *
     * @return string|null
     */
    public function getNextRenewalDateAttribute()
    {
        if (isset($this->renew_date) && $this->renew_date) {
            return Carbon::parse($this->renew_date)->addMonth()->format('Y-m-d');
        }
        if (isset($this->end_date) && $this->end_date) {
            return Carbon::parse($this->end_date)->addMonth()->format('Y-m-d');
        }
        return null;
    }
}
