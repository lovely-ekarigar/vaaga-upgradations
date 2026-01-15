<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ExamUser extends Model
{
   

    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'users';        // Replace with actual table name
}
