<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTestResult extends Model
{
    protected $fillable = [
        'mock_test_schedule_id',
        'student_id',
        'total_questions',
        'total_correct',
        'total_incorrect',
        'total_unattempted',
        'score',
        'percentage',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function schedule()
    {
        return $this->belongsTo(MockTestSchedule::class, 'mock_test_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(Auth\User::class, 'student_id');
    }

    /**
     * Calculate percentage based on correct answers
     */
    public function calculatePercentage()
    {
        if ($this->total_questions == 0) {
            return 0;
        }

        return round(($this->total_correct / $this->total_questions) * 100, 2);
    }

    /**
     * Get grade based on percentage
     */
    public function getGrade()
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    /**
     * Get pass/fail status
     */
    public function isPassed($passingPercentage = 40)
    {
        return $this->percentage >= $passingPercentage;
    }
}
