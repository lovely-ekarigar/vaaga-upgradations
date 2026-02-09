<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestList extends Model
{
 

    protected $table = 'test_list';

    protected $fillable = [
        'test_series_id',
        'name',
        'description',
        'total_questions',
        'duration',
        'sections',
        'section_questions',
        'status'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    /**
     * Get the test series that owns the test.
     */
    public function testSeries()
    {
        return $this->belongsTo(TestSeries::class);
    }
    
    
     public function sections()
    {
        return $this->hasMany(Subject::class,'test_id');
    }

    /**
     * Scope a query to only include active tests.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}