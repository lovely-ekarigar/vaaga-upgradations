<?php

namespace App\Events\Backend;

use App\Models\MockTestSchedule;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MockTestRescheduled
{
    use Dispatchable, SerializesModels;

    /**
     * @var MockTestSchedule
     */
    public $schedule;

    /**
     * @var string
     */
    public $reason;

    /**
     * True if rescheduled by Tutor; false if by Admin.
     * When true, listener notifies students + admins. When false, only students.
     *
     * @var bool
     */
    public $rescheduledByTutor;

    public function __construct(MockTestSchedule $schedule, string $reason, bool $rescheduledByTutor = true)
    {
        $this->schedule = $schedule;
        $this->reason = $reason;
        $this->rescheduledByTutor = $rescheduledByTutor;
    }
}
