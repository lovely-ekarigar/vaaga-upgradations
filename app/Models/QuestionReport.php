<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\User;
class QuestionReport extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'question_id',
        'question_no',
        'message'
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
