<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
class Recording extends Model
{
    protected  $guarded = [];


    public function lesson(){

        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
