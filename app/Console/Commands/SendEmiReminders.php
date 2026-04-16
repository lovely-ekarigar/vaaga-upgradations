<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Course;
use App\Models\AiSensy;
use Carbon\Carbon;

class SendEmiReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emi:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send EMI reminder notifications 3 days before end_date at 11 AM';

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
        $this->info('Checking for EMI reminders to send...');
        
        try {
            // Calculate date range: today to 3 days from now
            $today = Carbon::now()->format('Y-m-d');
            $threeDaysFromNow = Carbon::now()->addDays(3)->format('Y-m-d');
            
            // Find orders where:
            // 1. end_date is between today and 3 days from now (sends reminders for next 3 days including today)
            // 2. total_cycle is greater than paid_cycle (still has pending EMIs)
            // 3. Order status is 1 (paid/active)
            $orders = Order::whereBetween('end_date', [$today, $threeDaysFromNow])
                ->whereColumn('total_cycle', '>', 'paid_cycle')
                ->where('status', '1')
                ->whereNotNull('total_cycle')
                ->whereNotNull('paid_cycle')
                ->whereNotNull('end_date')
                ->with(['user', 'items.item'])
                ->get();
            
            if ($orders->isEmpty()) {
                $this->info('No EMI reminders to send (checked from ' . $today . ' to ' . $threeDaysFromNow . ')');
                return 0;
            }
            
            $this->info('Found ' . $orders->count() . ' orders requiring EMI reminders');
            \Log::info('============================================');
            \Log::info('EMI REMINDER NOTIFICATION JOB STARTED');
            \Log::info('Date Range: ' . $today . ' to ' . $threeDaysFromNow);
            \Log::info('Orders to process: ' . $orders->count());
            \Log::info('============================================');
            
            $sentCount = 0;
            $skippedCount = 0;
            
            foreach ($orders as $index => $order) {
                $this->info('Processing Order #' . ($index + 1) . ' (ID: ' . $order->id . ')');
                
                $user = $order->user;
                
                if (!$user) {
                    $this->warn('  ✗ User not found for order ID: ' . $order->id);
                    \Log::warning('User not found for order ID: ' . $order->id);
                    $skippedCount++;
                    continue;
                }
                
                if (!$user->phone) {
                    $this->warn('  ✗ Phone number not found for user: ' . $user->name);
                    \Log::warning('Phone number not found for user: ' . $user->name . ' (ID: ' . $user->id . ')');
                    $skippedCount++;
                    continue;
                }
                
                // Get course names from order items
                $courseNames = [];
                foreach ($order->items as $item) {
                    if ($item->item_type === Course::class && $item->item) {
                        $course = $item->item;
                        $courseNames[] = $course->title;
                    }
                }
                
                if (empty($courseNames)) {
                    $this->warn('  ✗ No courses found for order ID: ' . $order->id);
                    \Log::warning('No courses found for order ID: ' . $order->id);
                    $skippedCount++;
                    continue;
                }
                
                $courseName = implode(', ', $courseNames);
                
                // Extract month and year from end_date
                $endDate = Carbon::parse($order->end_date);
                $month = $endDate->format('F'); // Full month name (e.g., January)
                $year = $endDate->format('Y'); // Year (e.g., 2026)
                $monthYear = $month . ' ' . $year; // e.g., "January 2026"
                
                // Calculate days remaining
                $daysRemaining = Carbon::now()->startOfDay()->diffInDays($endDate->startOfDay(), false);
                $daysRemainingText = $daysRemaining > 0 ? $daysRemaining . ' days remaining' : 'Due today';
                
                // Get subscription amount
                $amount = ceil($order->amount);
                
                $this->info('  Student: ' . $user->name . ' (ID: ' . $user->id . ')');
                $this->info('  Phone: +91' . $user->phone);
                $this->info('  Course: ' . $courseName);
                $this->info('  EMI Due Date: ' . $endDate->format('d M Y') . ' (' . $daysRemainingText . ')');
                $this->info('  Amount: ₹' . $amount);
                $this->info('  Pending Cycles: ' . ($order->total_cycle - $order->paid_cycle) . ' of ' . $order->total_cycle);
                
                // Prepare WhatsApp notification payload
                $whatsappPayload = [
                    'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                    'campaignName' => 'emi_reminder_v2',
                    'destination' => '+91' . $user->phone,
                    'userName' => ucwords(trim($user->name)),
                    'source' => 'emi_reminder',
                    'templateParams' => [
                        trim($courseName),      // Param 1: Course name
                        $monthYear,             // Param 2: Month and Year from end_date
                        (string)$amount,        // Param 3: Amount of subscription (converted to string)
                        $month                  // Param 4: Month from end_date
                    ],
                    'tags' => ['emi_reminder', 'subscription'],
                    'attributes' => [
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'end_date' => $order->end_date,
                        'amount' => $amount
                    ],
                ];
                
                \Log::info('Sending EMI reminder to: ' . $user->name . ' (ID: ' . $user->id . ')', [
                    'order_id' => $order->id,
                    'phone' => '+91' . $user->phone,
                    'course' => $courseName,
                    'end_date' => $order->end_date,
                    'days_remaining' => $daysRemaining,
                    'amount' => $amount,
                    'pending_cycles' => ($order->total_cycle - $order->paid_cycle)
                ]);
                
                $this->info('  Sending WhatsApp notification...');
                
                // Send WhatsApp notification
                $result = AiSensy::send($whatsappPayload);
                
                \Log::info('AiSensy API response', [
                    'result' => $result,
                    'payload' => $whatsappPayload
                ]);
                
                if ($result) {
                    $sentCount++;
                    $this->info('  ✓ WhatsApp notification SENT');
                    \Log::info('✓ EMI reminder sent successfully');
                } else {
                    $skippedCount++;
                    $this->warn('  ✗ Failed to send WhatsApp notification - Check logs for details');
                    \Log::error('✗ Failed to send EMI reminder - API returned false or error');
                }
                
                $this->info('');
            }
            
            \Log::info('============================================');
            \Log::info('EMI REMINDER JOB COMPLETED');
            \Log::info('Total Orders Processed: ' . $orders->count());
            \Log::info('Notifications Sent: ' . $sentCount);
            \Log::info('Notifications Skipped: ' . $skippedCount);
            \Log::info('============================================');
            
            $this->info('EMI Reminder Summary:');
            $this->info('  Total Orders: ' . $orders->count());
            $this->info('  Sent: ' . $sentCount);
            $this->info('  Skipped: ' . $skippedCount);
            $this->info('EMI reminder job completed successfully!');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error in EMI reminder job: ' . $e->getMessage());
            \Log::error('EMI Reminder Job Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
