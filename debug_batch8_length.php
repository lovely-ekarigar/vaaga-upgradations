<?php
/**
 * Debug script to check the actual length values for Batch 8 recordings
 * Run this script with: php debug_batch8_length.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Batch;
use App\Models\Recording;

echo "=================================================================\n";
echo "DEBUG: Batch 8 Recordings - Length Column Investigation\n";
echo "=================================================================\n\n";

// 1. Check the actual database schema for recordings table
echo "1. DATABASE SCHEMA FOR recordings TABLE\n";
echo "-----------------------------------------------------------------\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM recordings");
    echo "Columns in recordings table:\n";
    foreach ($columns as $col) {
        echo sprintf("  - %-20s Type: %-20s Nullable: %-3s Default: %s\n", 
            $col->Field, 
            $col->Type, 
            $col->Null,
            $col->Default ?? 'NULL'
        );
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 2. Get Batch 8 details
echo "2. BATCH 8 DETAILS\n";
echo "-----------------------------------------------------------------\n";
$batch = Batch::find(8);
if (!$batch) {
    echo "ERROR: Batch 8 not found!\n";
    exit(1);
}

echo "Batch ID: " . $batch->id . "\n";
echo "Batch Name: " . ($batch->bname ?? 'N/A') . "\n";
echo "parent_api_class_id: " . ($batch->parent_api_class_id ?? 'NULL') . "\n";
echo "Course ID (cid): " . ($batch->cid ?? 'NULL') . "\n\n";

$parentId = $batch->parent_api_class_id;
if (!$parentId) {
    echo "ERROR: Batch 8 has no parent_api_class_id!\n";
    exit(1);
}

// 3. Get recordings for this batch
echo "3. RECORDINGS FOR BATCH 8 (parent_api_class_id = {$parentId})\n";
echo "-----------------------------------------------------------------\n";

$recordings = Recording::where('parent', $parentId)->get();
echo "Total recordings found: " . $recordings->count() . "\n\n";

if ($recordings->count() === 0) {
    echo "No recordings found for this batch.\n";
    exit(0);
}

// 4. Display detailed information for each recording
echo "4. DETAILED RECORDING INFORMATION\n";
echo "-----------------------------------------------------------------\n";
echo sprintf("%-5s | %-20s | %-15s | %-15s | %-15s | %-10s | %-10s\n", 
    "ID", "Recording Date", "Length", "Start Time", "End Time", "Duration", "Lesson ID");
echo str_repeat("-", 110) . "\n";

foreach ($recordings as $rec) {
    // Check what properties exist
    $lengthValue = $rec->length ?? 'NULL';
    $startTime = $rec->start_time ?? 'NULL';
    $endTime = $rec->end_time ?? 'NULL';
    
    // Calculate duration if start and end times exist
    $calculatedDuration = 'N/A';
    if ($startTime && $endTime && is_numeric($startTime) && is_numeric($endTime)) {
        $durationMs = $endTime - $startTime;
        $calculatedDuration = round($durationMs / (1000 * 60), 2) . ' mins'; // Convert ms to minutes
    }
    
    echo sprintf("%-5s | %-20s | %-15s | %-15s | %-15s | %-10s | %-10s\n",
        $rec->id,
        $rec->recording_date ?? 'NULL',
        $lengthValue,
        $startTime,
        $endTime,
        $calculatedDuration,
        $rec->lesson_id ?? 'NULL'
    );
}
echo str_repeat("-", 110) . "\n\n";

// 5. Summary of length values
echo "5. SUMMARY OF 'length' VALUES\n";
echo "-----------------------------------------------------------------\n";
$lengths = $recordings->pluck('length');
echo "Raw length values from database:\n";
foreach ($lengths as $index => $length) {
    $type = gettype($length);
    $value = $length === null ? 'NULL' : var_export($length, true);
    echo "  Recording " . ($index + 1) . ": {$value} (type: {$type})\n";
}
echo "\n";

// 6. Check if length column exists in model's attributes
echo "6. MODEL ATTRIBUTES CHECK\n";
echo "-----------------------------------------------------------------\n";
if ($recordings->count() > 0) {
    $firstRecording = $recordings->first();
    echo "First recording ID: " . $firstRecording->id . "\n";
    echo "Model attributes:\n";
    $attributes = $firstRecording->getAttributes();
    foreach ($attributes as $key => $value) {
        $type = gettype($value);
        $displayValue = $value === null ? 'NULL' : (is_string($value) ? "'{$value}'" : $value);
        echo "  - {$key}: {$displayValue} (type: {$type})\n";
    }
}
echo "\n";

// 7. Raw SQL to get the actual column data
echo "7. RAW SQL QUERY RESULTS\n";
echo "-----------------------------------------------------------------\n";
try {
    $rawResults = DB::select("SELECT id, recording_date, length, start_time, end_time, lesson_id, created_at FROM recordings WHERE parent = ?", [$parentId]);
    echo "Raw database results:\n";
    foreach ($rawResults as $row) {
        echo "  ID: {$row->id}\n";
        echo "    - recording_date: " . ($row->recording_date ?? 'NULL') . "\n";
        echo "    - length: " . ($row->length === null ? 'NULL' : $row->length) . " (type: " . gettype($row->length) . ")\n";
        echo "    - start_time: " . ($row->start_time ?? 'NULL') . "\n";
        echo "    - end_time: " . ($row->end_time ?? 'NULL') . "\n";
        echo "    - lesson_id: " . ($row->lesson_id ?? 'NULL') . "\n";
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 8. Recommendations
echo "8. ANALYSIS & RECOMMENDATIONS\n";
echo "-----------------------------------------------------------------\n";
echo "Issue: The duration shows 'N/A' because:\n";
echo "  - The code checks: isset(\$class->length) && is_numeric(\$class->length) && \$class->length > 0\n";
echo "  - If 'length' column is NULL or doesn't exist, the condition fails\n\n";

echo "Possible solutions:\n";
echo "  1. If 'length' column doesn't exist in DB - Add it via migration:\n";
echo "     ALTER TABLE recordings ADD COLUMN length INT NULL AFTER end_time;\n\n";
echo "  2. Calculate length from start_time and end_time (in ms):\n";
echo "     length = (end_time - start_time) / (1000 * 60) // gives minutes\n\n";
echo "  3. Update the view to calculate duration on-the-fly from start_time/end_time\n\n";

echo "=================================================================\n";
echo "End of debug report\n";
echo "=================================================================\n";
