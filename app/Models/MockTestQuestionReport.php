<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTestQuestionReport extends Model
{
    protected $fillable = [
        'question_id',
        'reported_by',
        'report_reason',
        'status',
        'resolved_by',
        'resolved_at',
        'admin_notes'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function reporter()
    {
        return $this->belongsTo(Auth\User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(Auth\User::class, 'resolved_by');
    }

    /**
     * Mark report as resolved
     */
    public function markAsResolved($adminNotes = null)
    {
        $this->status = 'resolved';
        $this->resolved_by = auth()->id();
        $this->resolved_at = now();
        $this->admin_notes = $adminNotes;
        $this->save();
    }

    /**
     * Mark report as rejected
     */
    public function markAsRejected($adminNotes = null)
    {
        $this->status = 'rejected';
        $this->resolved_by = auth()->id();
        $this->resolved_at = now();
        $this->admin_notes = $adminNotes;
        $this->save();
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for resolved reports
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}
