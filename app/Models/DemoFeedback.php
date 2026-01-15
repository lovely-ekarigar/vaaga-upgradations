<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoFeedback extends Model
{
    protected  $guarded = [];
    protected $table ='demo_feedbacks';

    public function user(){
        return $this->belongsTo('App\Models\Auth\User');
    }


    public function batch(){
        return $this->belongsTo(Batch::class);
    }
    public function demo(){
        return $this->belongsTo(DemoRequest::class);
    }

}
