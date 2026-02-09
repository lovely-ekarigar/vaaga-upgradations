<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBatch extends Model
{
    use HasFactory;

    protected $connection = 'mysql_exam'; // Uses the second MySQL connection
    protected $table = 'batches';        // Replace with actual table name

    /**
     * Get the users associated with this exam batch.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'exam_batch_user', 'batch_id', 'user_id');
    }
}
