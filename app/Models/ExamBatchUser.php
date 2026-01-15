<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ExamBatchUser extends Model
{
  

    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'batch_users';        // Replace with actual table name
}
