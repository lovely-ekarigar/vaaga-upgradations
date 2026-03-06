<?php
/**
 * Debug Batch 8 Recordings
 * Access: http://localhost:8000/debug_batch8_recordings.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Batch;
use App\Models\Recording;

echo "<pre>\n";
echo "========================================\n";
echo "DEBUG BATCH 8 RECORDINGS\n";
echo "========================================\n\n";

// 1. Get Batch 8
$batch = Batch::find(8);

if (!$batch) {
    echo "ERROR: Batch 8 not found!\n";
    echo "</pre>";
    exit;
}

echo "=== BATCH 8 DETAILS ===\n";
echo "- id: " . $batch->id . "\n";
echo "- parent_api_class_id: " . ($batch->parent_api_class_id ?? 'NULL') . "\n";
echo "- name (bname): " . ($batch->bname ?? 'N/A') . "\n";
echo "- course_id (cid): " . ($batch->cid ?? 'N/A') . "\n\n";

$parentId = $batch->parent_api_class_id;

if (!$parentId) {
    echo "ERROR: Batch 8 has no parent_api_class_id!\n";
    echo "</pre>";
    exit;
}

// 2. Get recordings for this parent
echo "=== RECORDINGS FOR parent_api_class_id = {$parentId} ===\n\n";

$recordings = Recording::where('parent', $parentId)->get();

echo "Total recordings found: " . $recordings->count() . "\n\n";

if ($recordings->count() > 0) {
    echo "Recording details:\n";
    echo str_repeat("-", 110) . "\n";
    echo sprintf("%-5s %-22s %-22s %-15s %-10s %-25s\n", "ID", "Recording Date", "Created At", "Length", "Lesson ID", "Title (vname)");
    echo str_repeat("-", 110) . "\n";
    
    foreach ($recordings as $rec) {
        echo sprintf(
            "%-5s %-22s %-22s %-15s %-10s %-25s\n",
            $rec->id,
            $rec->recording_date ?? 'NULL',
            $rec->created_at ? $rec->created_at->format('Y-m-d H:i:s') : 'NULL',
            $rec->length ?? 'NULL',
            $rec->lesson_id ?? 'NULL',
            substr($rec->vname ?? 'N/A', 0, 25)
        );
    }
    
    echo str_repeat("-", 110) . "\n\n";
    
    // Summary of recording_date values
    echo "=== RECORDING_DATE VALUES SUMMARY ===\n";
    $recordingDates = $recordings->pluck('recording_date')->unique()->sort()->values();
    echo "Unique recording_date values (" . $recordingDates->count() . "):\n";
    foreach ($recordingDates as $date) {
        $count = $recordings->where('recording_date', $date)->count();
        echo "  - " . ($date ?? 'NULL') . " ({$count} recordings)\n";
    }
    echo "\n";
    
    // Summary of length values
    echo "=== LENGTH VALUES SUMMARY ===\n";
    $lengths = $recordings->pluck('length')->unique()->sort()->values();
    echo "Unique length values (" . $lengths->count() . "):\n";
    foreach ($lengths as $length) {
        $count = $recordings->where('length', $length)->count();
        echo "  - " . ($length ?? 'NULL') . " ({$count} recordings)\n";
    }
    echo "\n";
    
    // Summary of lesson_id values
    echo "=== LESSON_ID DISTRIBUTION ===\n";
    $lessonIds = $recordings->pluck('lesson_id')->unique()->sort()->values();
    echo "Unique lesson_id values (" . $lessonIds->count() . "):\n";
    foreach ($lessonIds as $lid) {
        $count = $recordings->where('lesson_id', $lid)->count();
        echo "  - " . ($lid ?? 'NULL') . " ({$count} recordings)\n";
    }
    echo "\n";
    
    // Raw data for reference
    echo "=== RAW RECORDING DATA (first 5) ===\n";
    foreach ($recordings->take(5) as $rec) {
        echo "\nRecording ID: {$rec->id}\n";
        echo "  - recording_date: " . ($rec->recording_date ?? 'NULL') . "\n";
        echo "  - length: " . ($rec->length ?? 'NULL') . "\n";
        echo "  - lesson_id: " . ($rec->lesson_id ?? 'NULL') . "\n";
        echo "  - parent: " . ($rec->parent ?? 'NULL') . "\n";
        echo "  - vname: " . ($rec->vname ?? 'NULL') . "\n";
        echo "  - created_at: " . ($rec->created_at ?? 'NULL') . "\n";
    }
} else {
    echo "No recordings found for this parent_api_class_id.\n";
}

echo "\n========================================\n";
echo "END OF DEBUG\n";
echo "========================================\n";
echo "</pre>";
