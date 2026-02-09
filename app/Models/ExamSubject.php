<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
 
    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'subjects';        // Replace with actual table name
    
    
    public function questions()
    {
        return $this->hasMany(ExamQuestion::class, 'subject_id', 'id');
    }
}
