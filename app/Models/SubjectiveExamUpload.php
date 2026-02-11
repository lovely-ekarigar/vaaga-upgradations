<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectiveExamUpload extends Model
{
    protected $fillable = [
        'subjective_exam_id',
        'user_id',
        'file',
        'file_type',
        'marks',
        'remarks',
        'uploaded_at',
        'evaluated_at',
        'evaluated_by',
        'status',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'evaluated_at' => 'datetime',
    ];

    public function subjectiveExam()
    {
        return $this->belongsTo(SubjectiveExam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
