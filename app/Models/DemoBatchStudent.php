<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DemoBatch;
class DemoBatchStudent extends Model
{
    protected  $guarded = [];
   
   
      public function batch()
    {
        return $this->hasOne(DemoBatch::class,'id','batch_id');
    }
    
}
 