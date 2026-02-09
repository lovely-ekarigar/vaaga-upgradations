<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyExam extends Model
{
   


    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'batch_id',
        'exam_id',
        'questions',
        'answers',
        'total_marks',
        'marks_obtained',
        'status',
        'submitted_by',
        'submitted_at',
        'batch_exam_id',
        'batch_mock_test_id',
        'exam_date_time',
        'is_final',
        'duration',
        'time_spent',
        'photo',
        'last_ping',
        'current_question'
    ];

    // protected $casts = [
    //     'questions' => 'array',
    //     'answers' => 'array',
    //     'total_marks' => 'double',
    //     'marks_obtained' => 'double',
    // ];
    
    
     public function user()
    {

    return $this->hasOne(Auth\User::class,'id','user_id');
    }
    
     public function exam()
    {

    return $this->hasOne(TestList::class,'id','exam_id');
    }
      public function batch()
    {

    return $this->hasOne(Batch::class,'id','batch_id');
    }
    
     public function batch_exam()
    {

    return $this->hasOne(BatchExam::class,'id','batch_exam_id');
    }
    
    
}
