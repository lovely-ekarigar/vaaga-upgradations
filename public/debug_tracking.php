<?php
/**
 * Debug script for Live Class Tracking
 * Access: http://your-site/debug_tracking.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Elearn;
use App\Models\Recording;
use App\Models\Batch;

echo "<h1>Live Class Tracking Debug</h1>";

// Check BBB API
echo "<h2>1. BBB API Response</h2>";
$el = new Elearn();
$meetings = $el->eClass("getMeetings", []);
echo "<pre>";
print_r($meetings);
echo "</pre>";

// Check today's recordings
echo "<h2>2. Today's Recordings</h2>";
$today = date('Y-m-d');
$recordings = Recording::whereDate('created_at', $today)
    ->whereNotNull('api_class_id')
    ->orderBy('id', 'desc')
    ->get();

if($recordings->isEmpty()){
    echo "<p>No recordings found for today ({$today})</p>";
} else {
    echo "<p>Found {$recordings->count()} recording(s) for today ({$today})</p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Batch ID</th><th>API Class ID</th><th>Parent</th><th>Created At</th></tr>";
    foreach($recordings as $rec){
        $batch = Batch::where('parent_api_class_id', $rec->parent)->first();
        echo "<tr>";
        echo "<td>{$rec->id}</td>";
        echo "<td>{$rec->batch_id}</td>";
        echo "<td>{$rec->api_class_id}</td>";
        echo "<td>{$rec->parent}</td>";
        echo "<td>{$rec->created_at}</td>";
        echo "</tr>";
        
        // Check meeting info for this recording
        if($rec->api_class_id){
            $meetingInfo = $el->eClass("getMeetingInfo", ["meetingID" => $rec->api_class_id]);
            echo "<tr><td colspan='5'>";
            echo "Meeting Info for {$rec->api_class_id}: ";
            if(isset($meetingInfo['returncode'])){
                echo "Return Code: {$meetingInfo['returncode']}";
                if($meetingInfo['returncode'] == 'SUCCESS'){
                    echo " - Participants: " . ($meetingInfo['participantCount'] ?? 0);
                }
            } else {
                echo "No response or error";
            }
            echo "</td></tr>";
        }
    }
    echo "</table>";
}

// Summary
echo "<h2>3. Summary</h2>";
$bbbMeetings = $meetings['meeting'] ?? [];
if(isset($bbbMeetings['startTime'])){
    $bbbMeetings = [$meetings['meeting']];
}

echo "<p>BBB Meetings found: " . count($bbbMeetings) . "</p>";
echo "<p>Today's Recordings found: " . $recordings->count() . "</p>";

if(empty($bbbMeetings) && $recordings->isEmpty()){
    echo "<p style='color:red'><strong>No active classes detected!</strong></p>";
    echo "<p>Possible reasons:</p>";
    echo "<ul>";
    echo "<li>No class has been started today</li>";
    echo "<li>The recording entry was not created when the class started</li>";
    echo "<li>BBB API credentials are incorrect</li>";
    echo "</ul>";
} elseif(empty($bbbMeetings) && !$recordings->isEmpty()){
    echo "<p style='color:orange'>BBB API not returning meetings, but recordings exist. Fallback should work.</p>";
}

echo "<hr><p><a href='/user/track/batch'>Go to Live Class Tracking</a></p>";
