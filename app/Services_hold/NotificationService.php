<?php

namespace App\Services;

use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestResult;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\StudentTeacherBatch;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $aisensyService;

    public function __construct()
    {
        $this->aisensyService = new AISensyService();
    }

    /**
     * Send notification when mock test is assigned to a batch
     */
    public function sendMockTestAssignment(MockTest $mockTest, $batchId)
    {
        $batch = Batch::find($batchId);
        if (!$batch) {
            return false;
        }

        $message = "Dear Student,<br><b>" . $mockTest->title . "</b> Mock Test has been assigned to your batch. Your tutor will schedule the test date soon.";
        
        // Create platform notification
        $notification = new Notification();
        $notification->title = $mockTest->title . " Mock Test Assigned";
        $notification->message = $message;
        $notification->batch_type = 'selected';
        $notification->user_type = 'student';
        $notification->batch_list = json_encode([$batchId]);
        $notification->created_by = auth()->id();
        $notification->save();

        // Get all students in the batch
        $students = StudentTeacherBatch::where('bid', $batchId)->get();

        foreach ($students as $st) {
            // Platform notification
            $un = new UserNotification();
            $un->notification_id = $notification->id;
            $un->user_id = $st->uid;
            $un->status = '0';
            $un->save();

            // WhatsApp notification
            $this->aisensyService->sendMockTestAssignment($st->uid, $mockTest);
        }

        return true;
    }

    /**
     * Send notification when tutor schedules a mock test
     */
    public function sendMockTestScheduled(MockTestSchedule $schedule)
    {
        $mockTest = $schedule->mockTest;
        $batch = $schedule->batch;
        
        $formattedDate = $schedule->scheduled_date->format('d M Y');
        $message = "Dear Student,<br><b>" . $mockTest->title . "</b> Mock Test has been scheduled for <b>" . $formattedDate . "</b>. Please be prepared to attempt the test on the scheduled date.";

        // Create platform notification
        $notification = new Notification();
        $notification->title = $mockTest->title . " - Test Scheduled";
        $notification->message = $message;
        $notification->batch_type = 'selected';
        $notification->user_type = 'student';
        $notification->batch_list = json_encode([$schedule->batch_id]);
        $notification->created_by = auth()->id();
        $notification->save();

        // Get all students in the batch
        $students = StudentTeacherBatch::where('bid', $schedule->batch_id)->get();

        foreach ($students as $st) {
            // Platform notification
            $un = new UserNotification();
            $un->notification_id = $notification->id;
            $un->user_id = $st->uid;
            $un->status = '0';
            $un->save();

            // WhatsApp notification
            $this->aisensyService->sendMockTestScheduled($st->uid, $schedule);
        }

        return true;
    }

    /**
     * Send notification when test is rescheduled.
     *
     * @param MockTestSchedule $schedule
     * @param string $reason
     * @param bool|null $notifyAdmin If true, notify admins (Tutor reschedule). If false, skip. If null, infer from auth user role.
     */
    public function sendMockTestRescheduled(MockTestSchedule $schedule, $reason, $notifyAdmin = null)
    {
        if ($notifyAdmin === null && auth()->check()) {
            $notifyAdmin = auth()->user()->hasRole('teacher');
        }
        $mockTest = $schedule->mockTest;
        $formattedDate = $schedule->scheduled_date->format('d M Y');
        
        // Notify students
        $studentMessage = "Dear Student,<br><b>" . $mockTest->title . "</b> Mock Test has been rescheduled to <b>" . $formattedDate . "</b>.<br>Reason: " . $reason;

        $studentNotification = new Notification();
        $studentNotification->title = $mockTest->title . " - Test Rescheduled";
        $studentNotification->message = $studentMessage;
        $studentNotification->batch_type = 'selected';
        $studentNotification->user_type = 'student';
        $studentNotification->batch_list = json_encode([$schedule->batch_id]);
        $studentNotification->created_by = $schedule->rescheduled_by;
        $studentNotification->save();

        $students = StudentTeacherBatch::where('bid', $schedule->batch_id)->get();

        foreach ($students as $st) {
            $un = new UserNotification();
            $un->notification_id = $studentNotification->id;
            $un->user_id = $st->uid;
            $un->status = '0';
            $un->save();

            $this->aisensyService->sendMockTestRescheduled($st->uid, $schedule, $reason);
        }

        // Notify admin only when rescheduled by tutor
        if ($notifyAdmin) {
            $this->notifyAdminOfReschedule($schedule, $reason);
        }

        return true;
    }

    /**
     * Notify admin when tutor reschedules a test
     */
    protected function notifyAdminOfReschedule(MockTestSchedule $schedule, $reason)
    {
        $mockTest = $schedule->mockTest;
        $tutor = $schedule->rescheduledBy;
        $formattedDate = $schedule->scheduled_date->format('d M Y');

        $adminMessage = "Tutor <b>" . $tutor->name . "</b> has rescheduled <b>" . $mockTest->title . "</b> Mock Test to <b>" . $formattedDate . "</b>.<br>Batch: " . $schedule->batch->name . "<br>Reason: " . $reason;

        $adminNotification = new Notification();
        $adminNotification->title = "Mock Test Rescheduled by Tutor";
        $adminNotification->message = $adminMessage;
        $adminNotification->batch_type = 'all';
        $adminNotification->user_type = 'admin';
        $adminNotification->created_by = $schedule->rescheduled_by;
        $adminNotification->save();

        // Get all admins
        $admins = \App\Models\Auth\User::role('administrator')->get();
        
        foreach ($admins as $admin) {
            $un = new UserNotification();
            $un->notification_id = $adminNotification->id;
            $un->user_id = $admin->id;
            $un->status = '0';
            $un->save();
        }

        return true;
    }

    /**
     * Send notification when result is generated
     */
    public function sendMockTestResultGenerated(MockTestResult $result)
    {
        $schedule = $result->schedule;
        $mockTest = $schedule->mockTest;
        
        $message = "Dear Student,<br>Your result for <b>" . $mockTest->title . "</b> Mock Test is now available.<br>Score: " . $result->score . "/" . $result->total_questions . " (" . $result->percentage . "%)";

        // Create platform notification
        $notification = new Notification();
        $notification->title = $mockTest->title . " - Result Available";
        $notification->message = $message;
        $notification->batch_type = 'selected';
        $notification->user_type = 'student';
        $notification->created_by = 1; // System
        $notification->save();

        // Notify student
        $un = new UserNotification();
        $un->notification_id = $notification->id;
        $un->user_id = $result->student_id;
        $un->status = '0';
        $un->save();

        // WhatsApp notification
        $this->aisensyService->sendMockTestResult($result->student_id, $result);

        return true;
    }
}
