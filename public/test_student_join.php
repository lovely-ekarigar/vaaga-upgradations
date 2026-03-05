<?php
// Direct test of StudentJoin insert
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "<h2>Direct StudentJoin Test</h2>";

// Test 1: Direct DB insert
echo "<h3>Test 1: Direct DB Insert</h3>";
try {
    $id = DB::table('student_joins')->insertGetId([
        'user_id' => 1,
        'batch_id' => 8,
        'status' => 'test_direct',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "<p style='color:green'>SUCCESS! Inserted ID: $id</p>";
    
    // Delete test record
    DB::table('student_joins')->where('id', $id)->delete();
    echo "<p>Test record deleted</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>FAILED: " . $e->getMessage() . "</p>";
}

// Test 2: Model insert
echo "<h3>Test 2: Model Insert</h3>";
try {
    $sj = new \App\Models\StudentJoin();
    $sj->user_id = 1;
    $sj->batch_id = 8;
    $sj->status = 'test_model';
    $sj->created_at = now();
    $sj->updated_at = now();
    
    if ($sj->save()) {
        echo "<p style='color:green'>SUCCESS! Model saved. ID: $sj->id</p>";
        DB::table('student_joins')->where('id', $sj->id)->delete();
        echo "<p>Test record deleted</p>";
    } else {
        echo "<p style='color:red'>FAILED: Model save() returned false</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>EXCEPTION: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Show recent records
echo "<h3>Recent Records</h3>";
$records = DB::table('student_joins')->orderBy('id', 'desc')->limit(5)->get();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>User</th><th>Batch</th><th>Status</th><th>Date</th></tr>";
foreach ($records as $r) {
    echo "<tr>";
    echo "<td>{$r->id}</td>";
    echo "<td>{$r->user_id}</td>";
    echo "<td>{$r->batch_id}</td>";
    echo "<td>{$r->status}</td>";
    echo "<td>{$r->created_at}</td>";
    echo "</tr>";
}
echo "</table>";
