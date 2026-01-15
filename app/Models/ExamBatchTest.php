<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamBatchTest extends Model
{
   

    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'batch_tests';        // Replace with actual table name
}
