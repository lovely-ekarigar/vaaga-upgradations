<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Batch;
use App\Models\Recording;

echo "=== Checking Batch ID 8 ===\n\n";

// 1. Get Batch 8
$batch = Batch::find(8);

if (!$batch) {
    echo "Batch 8 not found!\n";
    exit(1);
}

echo "Batch 8 found:\n";
echo "- id: " . $batch->id . "\n";
echo "- parent_api_class_id: " . ($batch->parent_api_class_id ?? 'NULL') . "\n";
echo "- name: " . ($batch->bname ?? 'N/A') . "\n";
echo "- cid (course_id): " . ($batch->cid ?? 'N/A') . "\n\n";

$parentId = $batch->parent_api_class_id;

if (!$parentId) {
    echo "Batch 8 has no parent_api_class_id!\n";
    exit(1);
}

// 2. Get recordings for this parent
echo "=== Recordings for parent_api_class_id = {$parentId} ===\n\n";

$recordings = Recording::where('parent', $parentId)->get();

echo "Total recordings found: " . $recordings->count() . "\n\n";

if ($recordings->count() > 0) {
    echo "Recording details:\n";
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-5s %-20s %-20s %-15s %-10s\n", "ID", "Recording Date", "Created At", "Length", "Lesson ID");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($recordings as $rec) {
        echo sprintf(
            "%-5s %-20s %-20s %-15s %-10s\n",
            $rec->id,
            $rec->recording_date ?? 'NULL',
            $rec->created_at ? $rec->created_at->format('Y-m-d H:i:s') : 'NULL',
            $rec->length ?? 'NULL',
            $rec->lesson_id ?? 'NULL'
        );
    }
    
    echo str_repeat("-", 80) . "\n\n";
    
    // Summary of recording_date values
    echo "=== Recording Date Values Summary ===\n";
    $recordingDates = $recordings->pluck('recording_date')->unique()->sort()->values();
    echo "Unique recording_date values (" . $recordingDates->count() . "):\n";
    foreach ($recordingDates as $date) {
        $count = $recordings->where('recording_date', $date)->count();
        echo "  - " . ($date ?? 'NULL') . " ({$count} recordings)\n";
    }
    echo "\n";
    
    // Summary of length values
    echo "=== Length Values Summary ===\n";
    $lengths = $recordings->pluck('length')->unique()->sort()->values();
    echo "Unique length values (" . $lengths->count() . "):\n";
    foreach ($lengths as $length) {
        $count = $recordings->where('length', $length)->count();
        echo "  - " . ($length ?? 'NULL') . " ({$count} recordings)\n";
    }
    echo "\n";
    
    // Summary of lesson_id values
    echo "=== Lesson ID Distribution ===\n";
    $lessonIds = $recordings->pluck('lesson_id')->unique()->sort()->values();
    echo "Unique lesson_id values (" . $lessonIds->count() . "):\n";
    foreach ($lessonIds as $lid) {
        $count = $recordings->where('lesson_id', $lid)->count();
        echo "  - " . ($lid ?? 'NULL') . " ({$count} recordings)\n";
    }
} else {
    echo "No recordings found for this parent_api_class_id.\n";
}

echo "\n=== Done ===\n";
