<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Course;
use App\Models\Auth\User;
class DemoRequest extends Model
{
    use SoftDeletes;

    
 

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
        'course_id',
        'status',
        'teacher_id',
        'demo_date_time',
        'demo_status',
        'instructions',
        'remarks',
        'api_class_id',
        'student_join_at',
        'ip',
        'link',
        'meet_link' // ✅ ADD THIS
    ];

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
