<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTeacherBatch extends Model
{
    protected  $guarded = [];
    
    /**
     * Get the user (student) for this batch enrollment.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Auth\User::class, 'uid');
    }
    
    /**
     * Get the teacher for this batch enrollment.
     */
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Auth\User::class, 'tid');
    }
    
    /**
     * Get the batch.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'bid');
    }
}
