<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
 
    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'questions';        // Replace with actual table name
}
