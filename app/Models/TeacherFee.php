<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherFee extends Model
{
    protected  $guarded = [];

    public function user(){
        return $this->belongsTo('App\Models\Auth\User');
    }
    
    public function course(){
        return $this->belongsTo(Course::class);
    }

}
