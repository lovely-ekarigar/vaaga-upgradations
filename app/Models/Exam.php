<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name', 'description','course_id','subjects'];

    public function batches()
    {
        return $this->belongsToMany(Batch::class)->withPivot('exam_date', 'start_time', 'end_time')->withTimestamps();
    }
    protected $casts = [
        'subjects' => 'array', // auto decode JSON into array
    ];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
      public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function subjects()
    {
        return $this->belongsToMany(
            Course::class,    // related model
            'courses',        // same courses table
            'id',             // Exam.subjects stores course IDs
            'id'              // match with Course.id
        )->whereIn('id', $this->subjects ?? []);
    }
}
