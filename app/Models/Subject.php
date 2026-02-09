<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'test_id',
        'mock_id',
        'name',
        'status',
        'difficulty'
    ];

    // Define relationship with Course
    public function test()
    {
        return $this->belongsTo(TestList::class);
    }
    
    // Define relationship with Mock
    public function mock()
    {
        return $this->belongsTo(MockList::class, 'mock_id');
    }
    
    public function chapterlist()
    {
        return $this->hasMany(SubjectChapter::class,'subject_id');
    }
    
}
