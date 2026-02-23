<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockSeries extends Model
{
    

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mock_series';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'detail',
        'course_id',
        'status',
        'total_test',
        'difficulty'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_test' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => '0',
        'total_test' => 0,
    ];

    /**
     * Get the course that owns the mock series.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the mock lists for this series.
     */
    public function mockList()
    {
        return $this->hasMany(MockList::class, 'mock_series_id');
    }

    /**
     * Alias for mockList - used in some parts of the codebase.
     */
    public function mockLists()
    {
        return $this->hasMany(MockList::class, 'mock_series_id');
    }

    /**
     * Scope a query to only include active mock series.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}
