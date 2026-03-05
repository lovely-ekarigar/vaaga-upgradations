<?php
// Quick check script - place in public folder and access via browser
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "<h2>Student Joins Check</h2>";

// Check ALL records (not just today)
$all = DB::table('student_joins')->orderBy('id', 'desc')->limit(20)->get();
echo "<h3>Last 20 Records (All Dates):</h3>";
echo "Count: " . count($all) . "<br>";
if (count($all) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Batch ID</th><th>Status</th><th>Created At</th></tr>";
    foreach ($all as $r) {
        echo "<tr>";
        echo "<td>" . ($r->id ?? 'NULL') . "</td>";
        echo "<td>" . ($r->user_id ?? 'NULL') . "</td>";
        echo "<td>" . ($r->batch_id ?? 'NULL') . "</td>";
        echo "<td>" . ($r->status ?? 'NULL') . "</td>";
        echo "<td>" . ($r->created_at ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>NO RECORDS FOUND IN TABLE!</p>";
}

// Check today's records
echo "<h3>Today's Records (2026-03-06):</h3>";
$today = DB::table('student_joins')->whereDate('created_at', '2026-03-06')->get();
echo "Count: " . count($today);

// Check yesterday
echo "<h3>Yesterday's Records (2026-03-05):</h3>";
$yesterday = DB::table('student_joins')->whereDate('created_at', '2026-03-05')->get();
echo "Count: " . count($yesterday);

// Show table structure
echo "<h3>Table Columns:</h3>";
$cols = DB::select("DESCRIBE student_joins");
foreach ($cols as $c) {
    echo $c->Field . " - " . $c->Type . "<br>";
}
