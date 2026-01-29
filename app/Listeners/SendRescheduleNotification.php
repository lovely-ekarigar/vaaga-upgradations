<?php

namespace App\Listeners;

use App\Events\Backend\MockTestRescheduled;
use App\Services\NotificationService;

class SendRescheduleNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event: notify students (DB + AISensy); if rescheduled by Tutor, also notify admins.
     */
    public function handle(MockTestRescheduled $event)
    {
        $this->notificationService->sendMockTestRescheduled(
            $event->schedule,
            $event->reason,
            $event->rescheduledByTutor
        );
    }
}
