<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class StudentJoin extends Model
{
    protected $guarded = [];
    
    /**
     * Get the user associated with this join record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the batch associated with this join record.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
 