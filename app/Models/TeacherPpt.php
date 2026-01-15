<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPpt extends Model
{
    protected  $guarded = [];

    protected $table = 'teacher_ppt';

    public function user(){
        return $this->belongsTo('App\Models\Auth\User');
    }
    
}
