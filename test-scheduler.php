<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Mock Test Scheduler Testing Tool ===\n\n";

// Check current scheduled mocks
echo "1. Checking for scheduled mocks...\n";
$scheduledMocks = DB::table('batch_mock_tests')
    ->join('mock_list', 'batch_mock_tests.mock_list_id', '=', 'mock_list.id')
    ->select(
        'batch_mock_tests.batch_id',
        'batch_mock_tests.mock_list_id',
        'mock_list.name as mock_name',
        'batch_mock_tests.scheduled_at',
        'batch_mock_tests.is_active'
    )
    ->whereNotNull('batch_mock_tests.scheduled_at')
    ->get();

if ($scheduledMocks->count() > 0) {
    echo "Found " . $scheduledMocks->count() . " scheduled mock(s):\n";
    foreach ($scheduledMocks as $mock) {
        echo "  - Mock: {$mock->mock_name}\n";
        echo "    Batch ID: {$mock->batch_id}\n";
        echo "    Scheduled: {$mock->scheduled_at}\n";
        echo "    Active: " . ($mock->is_active ? 'Yes' : 'No') . "\n";
        echo "    Time until activation: " . \Carbon\Carbon::parse($mock->scheduled_at)->diffForHumans() . "\n\n";
    }
} else {
    echo "No scheduled mocks found.\n\n";
}

// Check what would be activated now
echo "2. Checking mocks ready for activation (scheduled_at <= now)...\n";
$readyToActivate = DB::table('batch_mock_tests')
    ->join('mock_list', 'batch_mock_tests.mock_list_id', '=', 'mock_list.id')
    ->select(
        'batch_mock_tests.batch_id',
        'batch_mock_tests.mock_list_id',
        'mock_list.name as mock_name',
        'batch_mock_tests.scheduled_at',
        'batch_mock_tests.is_active'
    )
    ->where('batch_mock_tests.scheduled_at', '<=', \Carbon\Carbon::now())
    ->where('batch_mock_tests.is_active', 0)
    ->whereNotNull('batch_mock_tests.scheduled_at')
    ->get();

if ($readyToActivate->count() > 0) {
    echo "Found " . $readyToActivate->count() . " mock(s) ready to activate:\n";
    foreach ($readyToActivate as $mock) {
        echo "  - {$mock->mock_name} (Scheduled: {$mock->scheduled_at})\n";
    }
    echo "\n";
} else {
    echo "No mocks ready for activation.\n\n";
}

// Test activation
echo "3. Running activation command...\n";
$updated = DB::table('batch_mock_tests')
    ->where('scheduled_at', '<=', \Carbon\Carbon::now())
    ->where('is_active', 0)
    ->whereNotNull('scheduled_at')
    ->update([
        'is_active' => 1,
        'scheduled_at' => null
    ]);

if ($updated > 0) {
    echo "✓ Successfully activated {$updated} mock test(s)!\n";
} else {
    echo "✗ No mock tests were activated.\n";
}

echo "\n=== Instructions ===\n";
echo "To test the scheduler:\n";
echo "1. Go to: http://127.0.0.1:8000/user/myclass/mock-tests/32\n";
echo "2. Schedule a mock test for 2 minutes from now\n";
echo "3. Current time: " . \Carbon\Carbon::now()->format('Y-m-d H:i:s') . "\n";
echo "4. Example schedule time: " . \Carbon\Carbon::now()->addMinutes(2)->format('Y-m-d H:i') . "\n";
echo "5. Run this command every minute to check: php artisan schedule:run\n";
echo "6. Or run manually: php artisan mock:activate-scheduled\n";
echo "\nFor automatic activation, the cron job is already set up and will run every minute.\n";
