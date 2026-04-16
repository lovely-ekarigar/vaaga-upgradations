<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // Removed - deleted_at column doesn't exist in live database
use Illuminate\Support\Facades\File;
use App\Models\MockTest;

/**
 * Class Question
 *
 * @package App
 * @property text $question
 * @property string $question_image
 * @property integer $score
 */
class Question extends Model
{
    // use SoftDeletes; // Removed - deleted_at column doesn't exist in live database

    protected $fillable = [
        'question', 
        'question_json', 
        'question_image', 
        'score',
         'status',  // <-- add this
        'exam_id',
        'question_text',
        'options',
        'correct_answer',
        'marks',
        'solution',
        'subject_id',
        'chapter_id',
        'course_id',
        'difficulty',
        'verification_status',
        'verification_remarks',
        'verified_by',
        'verified_at',
        'is_prev_year',
        'old'
    ];

    protected $casts = [
        'options' => 'array',
        'verified_at' => 'datetime',
    ];

    protected $attributes = [
        'verification_status' => 'pending',
    ];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $courses = auth()->user()->courses->pluck('id');
                    $builder->where(function ($q) use ($courses) {
                        // Allow questions that are part of a Test in teacher's courses
                        $q->whereHas('tests', function ($t) use ($courses) {
                            $t->whereIn('tests.course_id', $courses);
                        });
                        
                        // OR questions that are linked to a MockTest assigned to teacher's courses
                        // Only if mock_tests table exists
                        try {
                            if (\Schema::hasTable('mock_tests')) {
                                $q->orWhereHas('mockTests', function ($mt) use ($courses) {
                                    $mt->whereHas('courses', function ($c) use ($courses) {
                                        $c->whereIn('courses.id', $courses);
                                    });
                                });
                            }
                        } catch (\Exception $e) {
                            // Ignore if mock_tests table doesn't exist
                        }
                    });
                });
            }
        }

        static::deleting(function ($question) { // before delete() method call this
            // Hard delete - remove file when question is deleted
            if (File::exists(public_path('/storage/uploads/' . $question->question_image))) {
                File::delete(public_path('/storage/uploads/' . $question->question_image));
            }
        });

    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setScoreAttribute($input)
    {
        $this->attributes['score'] = $input ? $input : null;
    }

    public function options()
    {
        return $this->hasMany('App\Models\QuestionsOption');
    }

    public function isAttempted($result_id){
        $result = TestsResultsAnswer::where('tests_result_id', '=', $result_id)
            ->where('question_id', '=', $this->id)
            ->first();
        if($result != null){
            return true;
        }
        return false;
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'question_test');
    }

    public function mockTests()
    {
        return $this->belongsToMany(MockTest::class, 'mock_test_question')
            ->withPivot('sequence')
            ->withTimestamps();
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->hasOne(Course::class,'id','subject_id');
    }

    /**
     * Get translated question attribute
     */
    public function getTranslatedQuestionAttribute()
    {
        $questionText = json_decode($this->question_text, true);
        return $questionText['en'] ?? $this->question_text;
    }

    /**
     * Get translated solution attribute
     */
    public function getTranslatedSolutionAttribute()
    {
        $solution = json_decode($this->solution, true);
        return $solution['en'] ?? $this->solution;
    }
}
