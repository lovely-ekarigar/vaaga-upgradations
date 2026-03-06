<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Recording;
use App\Models\Batch;

echo "<h2>Recordings Debug</h2>";

$batchId = isset($_GET['batch_id']) ? $_GET['batch_id'] : 8;

$batch = Batch::find($batchId);
if (!$batch) {
    echo "<p>Batch not found</p>";
    exit;
}

echo "<h3>Batch: {$batch->name} (ID: {$batch->id})</h3>";
echo "<p>Parent API Class ID: {$batch->parent_api_class_id}</p>";

echo "<h3>All Recordings for this Batch:</h3>";

$recordings = Recording::where("parent", $batch->parent_api_class_id)
    ->orderBy("id", "desc")
    ->get();

echo "<p>Total recordings found: " . count($recordings) . "</p>";

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'>
    <th>ID</th>
    <th>API Class ID</th>
    <th>Recording Date</th>
    <th>Created At</th>
    <th>Length</th>
    <th>Internal ID</th>
    <th>Status</th>
</tr>";

foreach ($recordings as $rec) {
    $dateStatus = '';
    if (empty($rec->recording_date)) {
        $dateStatus = ' <span style="color:red">(NULL)</span>';
    } elseif (strpos($rec->recording_date, '1970') !== false) {
        $dateStatus = ' <span style="color:red">(INVALID)</span>';
    }
    
    $lengthStatus = ($rec->length >= 10) ? ' <span style="color:green">(VALID)</span>' : ' <span style="color:orange">(SHORT)</span>';
    
    echo "<tr>
        <td>{$rec->id}</td>
        <td>{$rec->api_class_id}</td>
        <td>{$rec->recording_date}{$dateStatus}</td>
        <td>{$rec->created_at}</td>
        <td>{$rec->length}{$lengthStatus}</td>
        <td>" . (empty($rec->internal_id) ? '<span style="color:red">NULL</span>' : substr($rec->internal_id, 0, 30) . '...') . "</td>
        <td>" . ($rec->status ?? 'N/A') . "</td>
    </tr>";
}
echo "</table>";

echo "<h3>Filtered Recordings (length >= 10):</h3>";
$filtered = $recordings->filter(function ($class) {
    return isset($class->length) && is_numeric($class->length) && $class->length >= 10;
});
echo "<p>Count after filtering: " . count($filtered) . "</p>";

echo "<h3>Grouped by Date:</h3>";
$grouped = $filtered->groupBy(function($item) {
    return \Carbon\Carbon::parse($item->recording_date)->format('Y-m-d');
});

foreach ($grouped as $date => $classes) {
    echo "<p><strong>$date:</strong> " . count($classes) . " session(s)</p>";
}
