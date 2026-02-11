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

    protected $fillable = ['question', 'question_json', 'question_image', 'score'];

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

}
