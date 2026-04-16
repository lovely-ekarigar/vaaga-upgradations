<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Course;
use App\Models\AiSensy;
use Carbon\Carbon;

class SendAccountSuspendedNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emi:send-suspended-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send account suspended notification one time after end_date if payment not made';

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
        $this->info('Checking for account suspended notifications to send...');
        
        try {
            // Get yesterday's date - this ensures notification is sent only once, the day after end_date
            $yesterday = Carbon::yesterday()->format('Y-m-d');
            
            // Find orders where:
            // 1. end_date was yesterday (payment due date has passed)
            // 2. total_cycle is greater than paid_cycle (still has pending EMIs - payment not made)
            // 3. Order status is 1 (paid/active)
            $orders = Order::where('end_date', '=', $yesterday)
                ->whereColumn('total_cycle', '>', 'paid_cycle')
                ->where('status', '1')
                ->whereNotNull('total_cycle')
                ->whereNotNull('paid_cycle')
                ->whereNotNull('end_date')
                ->with(['user', 'items.item'])
                ->get();
            
            if ($orders->isEmpty()) {
                $this->info('No account suspended notifications to send (checked for end_date: ' . $yesterday . ')');
                return 0;
            }
            
            $this->info('Found ' . $orders->count() . ' orders requiring account suspended notifications');
            \Log::info('============================================');
            \Log::info('ACCOUNT SUSPENDED NOTIFICATION JOB STARTED');
            \Log::info('End Date (Yesterday): ' . $yesterday);
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
                
                // Extract month from end_date
                $endDate = Carbon::parse($order->end_date);
                $month = $endDate->format('F'); // Full month name (e.g., March)
                
                // Get subscription amount
                $amount = ceil($order->amount);
                
                $this->info('  Student: ' . $user->name . ' (ID: ' . $user->id . ')');
                $this->info('  Phone: +91' . $user->phone);
                $this->info('  Course: ' . $courseName);
                $this->info('  Payment Due Date: ' . $endDate->format('d M Y') . ' (OVERDUE - was yesterday)');
                $this->info('  Amount: ₹' . $amount);
                $this->info('  Pending Cycles: ' . ($order->total_cycle - $order->paid_cycle) . ' of ' . $order->total_cycle);
                
                // Prepare WhatsApp notification payload
                $whatsappPayload = [
                    'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                    'campaignName' => 'emi_reminder_account_suspended_v1',
                    'destination' => '+91' . $user->phone,
                    'userName' => ucwords(trim($user->name)),
                    'source' => 'account_suspended',
                    'templateParams' => [
                        trim($courseName),      // Param 1: Course name
                        (string)$amount,        // Param 2: Amount of subscription
                        $month                  // Param 3: Month from end_date
                    ],
                    'tags' => ['account_suspended', 'subscription', 'overdue'],
                    'attributes' => [
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'end_date' => $order->end_date,
                        'amount' => $amount,
                        'notification_type' => 'account_suspended'
                    ],
                ];
                
                \Log::info('Sending ACCOUNT SUSPENDED notification to: ' . $user->name . ' (ID: ' . $user->id . ')', [
                    'order_id' => $order->id,
                    'phone' => '+91' . $user->phone,
                    'course' => $courseName,
                    'end_date' => $order->end_date,
                    'amount' => $amount,
                    'pending_cycles' => ($order->total_cycle - $order->paid_cycle)
                ]);
                
                $this->info('  Sending ACCOUNT SUSPENDED WhatsApp notification...');
                
                // Send WhatsApp notification
                $result = AiSensy::send($whatsappPayload);
                
                \Log::info('AiSensy API response', [
                    'result' => $result,
                    'notification_type' => 'account_suspended'
                ]);
                
                if ($result) {
                    $sentCount++;
                    $this->info('  ✓ Account suspended notification SENT');
                    \Log::info('✓ Account suspended notification sent successfully');
                } else {
                    $skippedCount++;
                    $this->warn('  ✗ Failed to send account suspended notification - Check logs for details');
                    \Log::error('✗ Failed to send account suspended notification - API returned false or error');
                }
                
                $this->info('');
            }
            
            \Log::info('============================================');
            \Log::info('ACCOUNT SUSPENDED NOTIFICATION JOB COMPLETED');
            \Log::info('Total Orders Processed: ' . $orders->count());
            \Log::info('Notifications Sent: ' . $sentCount);
            \Log::info('Notifications Skipped: ' . $skippedCount);
            \Log::info('============================================');
            
            $this->info('Account Suspended Notification Summary:');
            $this->info('  Total Orders: ' . $orders->count());
            $this->info('  Sent: ' . $sentCount);
            $this->info('  Skipped: ' . $skippedCount);
            $this->info('Account suspended notification job completed successfully!');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error in account suspended notification job: ' . $e->getMessage());
            \Log::error('Account Suspended Notification Job Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
