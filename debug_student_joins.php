<?php
// Debug script to check student_joins table
// Place this in public folder and access via browser

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h2>Debug Student Joins</h2>";

// 1. Check table structure
echo "<h3>1. Table Structure:</h3>";
try {
    $columns = DB::select("DESCRIBE student_joins");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col->Field}</td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>{$col->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// 2. Check today's records
echo "<h3>2. Today's Records:</h3>";
$today = date('Y-m-d');
$records = DB::table('student_joins')->whereDate('created_at', $today)->get();
echo "Found: " . count($records) . " records<br>";
if (count($records) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Batch ID</th><th>Status</th><th>Created At</th></tr>";
    foreach ($records as $rec) {
        echo "<tr>";
        echo "<td>{$rec->id}</td>";
        echo "<td>{$rec->user_id}</td>";
        echo "<td>{$rec->batch_id}</td>";
        echo "<td>{$rec->status}</td>";
        echo "<td>{$rec->created_at}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 3. Check all records (last 10)
echo "<h3>3. Last 10 Records (any date):</h3>";
$allRecords = DB::table('student_joins')->orderBy('id', 'desc')->limit(10)->get();
echo "Found: " . count($allRecords) . " records<br>";
if (count($allRecords) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Batch ID</th><th>Status</th><th>Created At</th></tr>";
    foreach ($allRecords as $rec) {
        echo "<tr>";
        echo "<td>{$rec->id}</td>";
        echo "<td>{$rec->user_id}</td>";
        echo "<td>{$rec->batch_id}</td>";
        echo "<td>{$rec->status}</td>";
        echo "<td>{$rec->created_at}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 4. Test insert
echo "<h3>4. Test Insert:</h3>";
try {
    $testId = DB::table('student_joins')->insertGetId([
        'user_id' => 1,
        'batch_id' => 1,
        'status' => 'test',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Test insert successful! ID: " . $testId . "<br>";
    // Delete test record
    DB::table('student_joins')->where('id', $testId)->delete();
    echo "Test record deleted.<br>";
} catch (Exception $e) {
    echo "Insert Error: " . $e->getMessage();
}
