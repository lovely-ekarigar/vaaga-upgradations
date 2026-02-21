<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'training_for',
        'title',
        'file_path',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
