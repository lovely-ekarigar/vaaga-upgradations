<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MockTestSchedule extends Model
{
    protected $fillable = [
        'mock_test_id',
        'batch_id',
        'assigned_by',
        'scheduled_date',
        'timezone',
        'status',
        'rescheduled_at',
        'rescheduled_by',
        'reschedule_reason'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'rescheduled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($schedule) {
            if (auth()->check() && !$schedule->assigned_by) {
                $schedule->assigned_by = auth()->id();
            }
        });
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(Auth\User::class, 'assigned_by');
    }

    public function rescheduledBy()
    {
        return $this->belongsTo(Auth\User::class, 'rescheduled_by');
    }

    public function responses()
    {
        return $this->hasMany(MockTestResponse::class);
    }

    public function results()
    {
        return $this->hasMany(MockTestResult::class);
    }

    /**
     * Check if the test is currently active (can be attempted today)
     */
    public function isActive()
    {
        $now = Carbon::now($this->timezone);
        $scheduledDate = Carbon::parse($this->scheduled_date, $this->timezone);
        
        return $now->isSameDay($scheduledDate) && 
               $this->status === 'scheduled' &&
               $now->hour < 24;
    }

    /**
     * Check if a specific user can attempt this test
     */
    public function canAttempt($userId)
    {
        // Check if date is valid
        if (!$this->isActive()) {
            return false;
        }

        // Check if user has already attempted
        if ($this->hasAttempted($userId)) {
            return false;
        }

        return true;
    }

    /**
     * Check if user has already attempted this test
     */
    public function hasAttempted($userId)
    {
        return $this->results()->where('student_id', $userId)->exists();
    }

    /**
     * Get all students in the batch who haven't attempted the test
     */
    public function getPendingStudents()
    {
        $attemptedStudentIds = $this->results()->pluck('student_id')->toArray();
        
        return StudentTeacherBatch::where('bid', $this->batch_id)
            ->whereNotIn('uid', $attemptedStudentIds)
            ->with('student')
            ->get()
            ->pluck('student');
    }
}
