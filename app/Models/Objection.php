<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Objection extends Model
{
  

    protected $fillable = [
        'recording_id',
        'reason',
        'status',
        'objection_by',
        'admin_reason',
    ];
}
