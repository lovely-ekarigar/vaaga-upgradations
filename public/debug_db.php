<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Database Tables Check</h2>";

try {
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = "Tables_in_$dbName";
    
    echo "<h3>All Tables:</h3><ul>";
    foreach ($tables as $table) {
        $tableName = $table->$key;
        if (strpos($tableName, 'exam') !== false) {
            echo "<li><strong>$tableName</strong></li>";
        }
    }
    echo "</ul>";
    
    // Check specific tables
    $examTables = ['exam_users', 'exam_batches', 'exam_batch_users', 'exam_tests', 'exam_batch_tests', 'exam_my_tests'];
    
    echo "<h3>Exam System Tables:</h3><ul>";
    foreach ($examTables as $table) {
        $exists = Schema::hasTable($table);
        echo "<li>$table: " . ($exists ? "<span style='color:green'>EXISTS</span>" : "<span style='color:red'>MISSING</span>") . "</li>";
        
        if ($exists) {
            $columns = Schema::getColumnListing($table);
            echo "<ul><li>Columns: " . implode(', ', $columns) . "</li></ul>";
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
