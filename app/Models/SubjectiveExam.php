<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectiveExam extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        'total_marks',
        'passing_marks',
        'is_active',
        'created_by',
        'instructions',
        'duration',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function uploads()
    {
        return $this->hasMany(SubjectiveExamUpload::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
