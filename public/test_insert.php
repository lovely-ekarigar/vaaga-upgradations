<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "<h2>StudentJoin Insert Test</h2>";

// Simulate the exact code from controller
$userId = auth()->check() ? auth()->user()->id : null;
$batchId = 8; // Test batch ID

echo "Auth User ID: " . ($userId ?? 'NULL') . "<br>";
echo "Batch ID: $batchId<br>";

try {
    $sj = new \App\Models\StudentJoin();
    $sj->user_id = $userId;
    $sj->batch_id = $batchId;
    $sj->status = 'test_joined';
    $sj->created_at = now();
    $sj->updated_at = now();
    
    echo "<pre>Before save:\n";
    print_r($sj->toArray());
    echo "</pre>";
    
    $saved = $sj->save();
    
    echo "Save result: " . ($saved ? 'TRUE' : 'FALSE') . "<br>";
    echo "New ID: " . ($sj->id ?? 'NULL') . "<br>";
    
    // Verify by fetching
    $verify = DB::table('student_joins')->where('id', $sj->id)->first();
    echo "<pre>From DB:\n";
    print_r($verify);
    echo "</pre>";
    
    // Clean up
    if ($sj->id) {
        DB::table('student_joins')->where('id', $sj->id)->delete();
        echo "Test record deleted.<br>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
