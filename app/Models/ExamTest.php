<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTest extends Model
{
 
    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'tests';        // Replace with actual table name
}
