<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTestResponse extends Model
{
    protected $fillable = [
        'mock_test_schedule_id',
        'student_id',
        'question_id',
        'response_option_id',
        'is_correct',
        'time_taken'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'time_taken' => 'integer',
    ];

    public function schedule()
    {
        return $this->belongsTo(MockTestSchedule::class, 'mock_test_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(Auth\User::class, 'student_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function responseOption()
    {
        return $this->belongsTo(QuestionsOption::class, 'response_option_id');
    }

    /**
     * Check if the response is correct based on the selected option
     */
    public function checkCorrectness()
    {
        if (!$this->response_option_id) {
            return false; // Unattempted
        }

        $option = $this->responseOption;
        return $option ? $option->correct : false;
    }
}
