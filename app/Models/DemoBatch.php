<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;
use App\Models\DemoBatchStudent;
class DemoBatch extends Model
{
    protected  $guarded = [];
   
   
      public function teacher()
    {
        return $this->hasOne(User::class,'id','teacher_id');
    }
    
      public function students()
    {
        return $this->hasMany(DemoBatchStudent::class,'batch_id','id');
    }
    
}
 