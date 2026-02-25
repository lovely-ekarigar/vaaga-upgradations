<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivateScheduledMockTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock:activate-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate mock tests that are scheduled to be activated';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking for scheduled mock tests to activate...');
        
        try {
            // Find all mock tests that are scheduled to be activated (scheduled_at <= now and is_active = 0)
            $scheduledTests = DB::table('batch_mock_tests')
                ->where('scheduled_at', '<=', Carbon::now())
                ->where('is_active', 0)
                ->whereNotNull('scheduled_at')
                ->get();
            
            if ($scheduledTests->count() > 0) {
                foreach ($scheduledTests as $test) {
                    // Update the mock test status
                    DB::table('batch_mock_tests')
                        ->where('id', $test->id)
                        ->update([
                            'is_active' => 1,
                            'scheduled_at' => null
                        ]);
                    
                    // Send notifications to students
                    $this->sendNotifications($test->mock_list_id, $test->batch_id);
                    
                    $this->info("Activated mock test ID: {$test->mock_list_id} for batch ID: {$test->batch_id}");
                }
                
                $count = $scheduledTests->count();
                $this->info("Successfully activated {$count} scheduled mock test(s).");
                \Log::info("Activated {$count} scheduled mock test(s) at " . Carbon::now());
            } else {
                $this->info('No scheduled mock tests to activate at this time.');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error activating scheduled mock tests: ' . $e->getMessage());
            \Log::error('Error in ActivateScheduledMockTests command: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Send notifications to students when a mock test is activated
     */
    private function sendNotifications($mockId, $batchId)
    {
        try {
            \Log::info('============================================');
            \Log::info('SCHEDULED MOCK TEST NOTIFICATION PROCESS');
            \Log::info('============================================');
            
            $mockTest = \App\Models\MockList::find($mockId);
            if (!$mockTest) {
                \Log::error('Mock test not found: ' . $mockId);
                return;
            }
            
            $batch = \App\Models\Batch::find($batchId);
            if (!$batch) {
                \Log::error('Batch not found: ' . $batchId);
                return;
            }
            
            \Log::info('Mock Test: ' . $mockTest->name . ' (ID: ' . $mockId . ')');
            \Log::info('Batch: ' . $batch->name . ' (ID: ' . $batchId . ')');
            
            // Create in-platform notification
            $notificationMessage = "Dear Student,<br><b>" . $mockTest->name . "</b> Mock Test has been assigned to your batch <b>" . $batch->name . "</b>. Kindly visit the classes module to attempt the test.";
            
            $notification = new \App\Models\Notification;
            $notification->title = $mockTest->name . " Mock Test Assigned";
            $notification->message = $notificationMessage;
            $notification->batch_type = 'selected';
            $notification->user_type = 'student';
            $notification->batch_list = json_encode([$batchId]);
            $notification->created_by = 1; // System user for scheduled activations
            $notification->save();
            $notificationId = $notification->id;
            
            \Log::info('In-platform notification created (ID: ' . $notificationId . ')');
            
            // Get all students in the batch
            $studentBatches = \App\Models\StudentTeacherBatch::where('bid', $batchId)->get();
            $totalStudents = $studentBatches->count();
            $whatsappSentCount = 0;
            $whatsappSkippedCount = 0;
            $alreadyCompletedCount = 0;
            
            \Log::info('Total students in batch: ' . $totalStudents);
            \Log::info('--------------------------------------------');
            
            foreach ($studentBatches as $index => $stb) {
                // Check if student has already completed this mock test
                $alreadyCompleted = \App\Models\MyExam::where('user_id', $stb->uid)
                    ->where('exam_id', $mockId)
                    ->where('status', 'completed')
                    ->exists();
                
                if ($alreadyCompleted) {
                    $alreadyCompletedCount++;
                    $user = \App\Models\Auth\User::find($stb->uid);
                    \Log::info('Student #' . ($index + 1) . ': ' . ($user ? $user->name : 'ID: ' . $stb->uid));
                    \Log::info('  ⊗ SKIPPED - Already completed this mock test');
                    continue; // Skip notification for students who already completed
                }
                
                // Create user notification
                $userNotification = new \App\Models\UserNotification;
                $userNotification->notification_id = $notificationId;
                $userNotification->user_id = $stb->uid;
                $userNotification->status = '0';
                $userNotification->save();
                
                // Send WhatsApp notification via AISensy
                $user = \App\Models\Auth\User::find($stb->uid);
                if ($user && $user->phone) {
                    \Log::info('Student #' . ($index + 1) . ': ' . $user->name . ' (ID: ' . $user->id . ')');
                    \Log::info('  Phone: +91' . $user->phone);
                    
                    // Check student commitment
                    $commit = \App\Models\StudentCommitment::where("batch_id", $batchId)
                        ->where("student_id", $stb->uid)
                        ->first();
                    
                    // Send WhatsApp if:
                    // 1. No commitment record exists (student is in batch, send notification)
                    // 2. OR commitment exists AND (no completion date OR completion date is in future)
                    $shouldSendWhatsApp = !$commit || ($commit->completion_date == null || date("Y-m-d") <= $commit->completion_date);
                    
                    if ($shouldSendWhatsApp) {
                        $whatsappPayload = [
                            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                            'campaignName' => 'mock_test',
                            'destination' => '+91' . $user->phone,
                            'userName' => ucwords(trim($user->name)),
                            'source' => 'mock_test',
                            'templateParams' => [
                                ucwords(trim($user->name)),
                                trim($mockTest->name),
                                'for',
                                date('d M Y'),
                                date('d M Y')
                            ],
                            'tags' => ['mock_test', 'mock_test_assigned'],
                            'attributes' => ['user_id' => $user->id, 'mock_id' => $mockId, 'batch_id' => $batchId],
                        ];
                        
                        \App\Models\AiSensy::send($whatsappPayload);
                        $whatsappSentCount++;
                        \Log::info('  ✓ WhatsApp notification SENT' . (!$commit ? ' (No commitment record)' : ''));
                    } else {
                        $whatsappSkippedCount++;
                        $reason = 'Course completed (completion_date: ' . $commit->completion_date . ')';
                        \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
                    }
                } else {
                    $whatsappSkippedCount++;
                    $reason = !$user ? 'User not found' : 'No phone number';
                    \Log::info('Student #' . ($index + 1) . ' (ID: ' . $stb->uid . ')');
                    \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
                }
            }
            
            \Log::info('--------------------------------------------');
            \Log::info('NOTIFICATION SUMMARY:');
            \Log::info('  Total Students in Batch: ' . $totalStudents);
            \Log::info('  Already Completed Mock: ' . $alreadyCompletedCount);
            \Log::info('  Notifications Sent: ' . ($totalStudents - $alreadyCompletedCount));
            \Log::info('  WhatsApp Sent: ' . $whatsappSentCount);
            \Log::info('  WhatsApp Skipped: ' . $whatsappSkippedCount);
            \Log::info('============================================');
            \Log::info('SCHEDULED NOTIFICATION PROCESS COMPLETED');
            \Log::info('============================================');
        } catch (\Exception $e) {
            \Log::error('============================================');
            \Log::error('ERROR IN SCHEDULED NOTIFICATION PROCESS');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('============================================');
        }
    }
}
