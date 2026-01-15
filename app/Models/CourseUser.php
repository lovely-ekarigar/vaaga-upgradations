<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    protected  $guarded = [];
    protected $table ='course_user';

    public function course(){
        return $this->belongsTo(Course::class);
    }

}
