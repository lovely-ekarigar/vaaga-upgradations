<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Course;
use App\Models\Auth\User;
class DemoRequest extends Model
{
    use SoftDeletes;

    

    public function coursed()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
