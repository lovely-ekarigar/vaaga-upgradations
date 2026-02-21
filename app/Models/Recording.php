<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\Objection;

class Recording extends Model
{
    protected  $guarded = [];


    public function lesson(){

        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    /**
     * Get the objection for this recording by a specific user
     */
    public function objection()
    {
        return $this->hasOne(Objection::class, 'recording_id')
            ->where('objection_by', auth()->id());
    }

    /**
     * Get all objections for this recording
     */
    public function objections()
    {
        return $this->hasMany(Objection::class, 'recording_id');
    }
}
