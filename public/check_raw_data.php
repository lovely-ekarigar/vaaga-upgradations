<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "<h2>Raw Student Joins Data</h2>";

// Get raw data
$records = DB::table('student_joins')->orderBy('id', 'desc')->limit(10)->get();

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>user_id</th><th>batch_id</th><th>status</th><th>created_at</th></tr>";
foreach ($records as $r) {
    echo "<tr>";
    echo "<td>" . ($r->id ?? 'NULL') . "</td>";
    echo "<td>" . ($r->user_id === null ? '<span style="color:red">NULL</span>' : $r->user_id) . "</td>";
    echo "<td>" . ($r->batch_id === null ? '<span style="color:red">NULL</span>' : $r->batch_id) . "</td>";
    echo "<td>" . ($r->status ?? 'NULL') . "</td>";
    echo "<td>" . ($r->created_at ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check all columns
echo "<h3>Table Columns:</h3>";
$cols = DB::select("DESCRIBE student_joins");
foreach ($cols as $c) {
    echo $c->Field . " | " . $c->Type . " | " . ($c->Null == 'YES' ? 'NULL OK' : 'NOT NULL') . "<br>";
}
