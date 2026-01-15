<?php

namespace App\Models;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Model;

class AssessmentUser extends Model
{
    protected  $guarded = [];
    
    
   public function user()
      {
        // return $this->belongsTo(User::class,'id','user_id');
        
      return $this->belongsTo(User::class,'user_id');
     }
    
}
 