<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectChapter extends Model
{
    protected $fillable = [
        'subject_id',
        'lesson_id',
        'test_id',
        'question_count'
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class); // assumes you have Lesson model
    }
}
