<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class StudentJoin extends Model
{
    protected $guarded = [];
    
    // Map old column names to new for compatibility
    protected $appends = ['user_id', 'batch_id'];
    
    // Use old column names for database operations
    public function setUserIdAttribute($value)
    {
        $this->attributes['uid'] = $value;
    }
    
    public function getUserIdAttribute()
    {
        return $this->attributes['uid'] ?? null;
    }
    
    public function setBatchIdAttribute($value)
    {
        $this->attributes['bid'] = $value;
    }
    
    public function getBatchIdAttribute()
    {
        return $this->attributes['bid'] ?? null;
    }
    
    /**
     * Get the user associated with this join record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
    
    /**
     * Get the batch associated with this join record.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'bid', 'id');
    }
}
 