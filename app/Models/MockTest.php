<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MockTest extends Model
{
    use SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'status',
        'course_id',
        'created_by'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        if(auth()->check()) {
            if (auth()->user()->hasRole('teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    // Teacher can see mock tests assigned to any course they teach.
                    $builder->whereHas('courses', function ($q) {
                        $q->whereHas('teachers', function ($t) {
                            $t->where('course_user.user_id', '=', auth()->user()->id);
                        });
                    });
                });
            }
        }
        
        static::creating(function ($mockTest) {
            if (auth()->check() && !$mockTest->created_by) {
                $mockTest->created_by = auth()->id();
            }
        });
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setCourseIdAttribute($input)
    {
        $this->attributes['course_id'] = $input ? $input : null;
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }

    /**
     * Courses (Classes) this mock test is assigned to.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'mock_test_courses', 'mock_test_id', 'course_id')
            ->withTimestamps()
            ->withTrashed();
    }

    public function creator()
    {
        return $this->belongsTo(Auth\User::class, 'created_by');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'mock_test_question')
            ->withPivot('sequence')
            ->withTimestamps()
            ->orderBy('sequence')
            ->withTrashed();
    }

    public function schedules()
    {
        return $this->hasMany(MockTestSchedule::class);
    }

    public function activeSchedules()
    {
        return $this->hasMany(MockTestSchedule::class)
            ->whereIn('status', ['scheduled', 'active']);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', self::STATUS_REVIEWED);
    }

    public function isPublishable(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
