<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherFeedbackQuestion extends Model
{
    protected $fillable = [
        'question',
        'question_type',
        'options',
        'is_active',
        'sort_order',
        'category',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function responses()
    {
        return $this->hasMany(Feedback::class);
    }
}
