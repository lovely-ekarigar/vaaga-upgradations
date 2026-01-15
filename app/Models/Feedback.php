<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected  $guarded = [];
    protected $table ='feedback';

    public function user(){
        return $this->belongsTo('App\Models\Auth\User');
    }


    public function batch(){
        return $this->belongsTo(Batch::class);
    }

}
