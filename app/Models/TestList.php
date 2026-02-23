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
        'status',
        'test_type'
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

    /**
     * Scope a query to only include regular tests (not mock tests).
     */
    public function scopeRegular($query)
    {
        // Check if test_type column exists (for backward compatibility)
        if (\Schema::hasColumn('test_list', 'test_type')) {
            return $query->where(function($q) {
                $q->where('test_type', 'regular')
                  ->orWhereNull('test_type');
            });
        }
        
        // Fallback: filter by name if column doesn't exist yet
        return $query->whereRaw('LOWER(name) NOT LIKE ?', ['%mock test%']);
    }

    /**
     * Scope a query to only include mock tests.
     */
    public function scopeMock($query)
    {
        return $query->where('test_type', 'mock');
    }
}