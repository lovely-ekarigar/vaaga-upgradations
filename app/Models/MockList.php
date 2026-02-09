<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockList extends Model
{
 

    protected $table = 'mock_list';

    protected $fillable = [
        'mock_series_id',
        'name',
        'description',
        'total_questions',
        'duration',
        'sections',
        'section_questions',
        'status',
        'sort_order',
        'is_prev_year'
    ];

    protected $casts = [
        'sections' => 'array',
        'section_questions' => 'array'
    ];

    /**
     * Get the mock series that owns the mock.
     */
    public function mockSeries()
    {
        return $this->belongsTo(MockSeries::class);
    }
    
    
     public function sections()
    {
        return $this->hasMany(Subject::class,'mock_id');
    }

    /**
     * Scope a query to only include active mocks.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Check if this mock test is available for a specific batch
     * based on completed lessons/chapters in batch progress.
     *
     * @param int $batchId
     * @return bool
     */
    public function isAvailableForBatch($batchId)
    {
        // Get completed lesson/chapter IDs for this batch
        $completedLessonIds = \App\Models\LessionComplete::where('batch_id', $batchId)
            ->where('status', 'completed')
            ->pluck('lession_id')
            ->toArray();
        
        // Get section_questions from this mock
        $sectionQuestions = $this->section_questions;
        
        if (empty($sectionQuestions) || !is_array($sectionQuestions)) {
            // If no section_questions defined, mock test is available
            return true;
        }
        
        // Extract all chapter IDs from section_questions
        // Structure: {"752":{"792":"5","793":"3","794":"2"}}
        // Where 752 is section_id and 792, 793, 794 are chapter_ids
        $requiredChapterIds = [];
        foreach ($sectionQuestions as $sectionId => $chapters) {
            if (is_array($chapters)) {
                foreach ($chapters as $chapterId => $questionCount) {
                    $requiredChapterIds[] = (int)$chapterId;
                }
            }
        }
        
        // Check if all required chapters are completed
        if (empty($requiredChapterIds)) {
            // If no chapters defined, mock test is available
            return true;
        }
        
        // Only show mock test if ALL required chapters are completed
        foreach ($requiredChapterIds as $requiredChapterId) {
            if (!in_array($requiredChapterId, $completedLessonIds)) {
                // At least one required chapter is not completed
                return false;
            }
        }
        
        // All required chapters are completed
        return true;
    }
    
    /**
     * Get the chapter IDs required for this mock test.
     *
     * @return array
     */
    public function getRequiredChapterIds()
    {
        $sectionQuestions = $this->section_questions;
        
        if (empty($sectionQuestions) || !is_array($sectionQuestions)) {
            return [];
        }
        
        $requiredChapterIds = [];
        foreach ($sectionQuestions as $sectionId => $chapters) {
            if (is_array($chapters)) {
                foreach ($chapters as $chapterId => $questionCount) {
                    $requiredChapterIds[] = (int)$chapterId;
                }
            }
        }
        
        return array_unique($requiredChapterIds);
    }
}
