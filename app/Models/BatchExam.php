<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchExam extends Model
{
    use HasFactory;

    protected $table = 'batch_exam'; // pivot table name

    protected $primaryKey = 'id'; // assuming pivot has its own ID column

    public $timestamps = true; // set false if pivot table has no timestamps

    protected $fillable = [
        'batch_id',
        'exam_id',
        'exam_date',
        'start_time',
        'end_time',
        'exam_type',
    ];
    
     public function exam()
    {

    return $this->hasOne(Exam::class,'id','exam_id');
    }
     public function batch()
    {

    return $this->hasOne(Batch::class,'id','batch_id');
    }
}
