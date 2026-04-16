<?php

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Str;

?>
@extends('backend.layouts.app')

@section('title', "Edit Question | " . env('APP_NAME'))

@section('page_css')
<style>
    /* Modern Compact Design */
    svg{
        height: 30px;
    }

    /* Hide CKEditor notifications */
    .cke_notifications_area { display: none !important; }
</style>
@stop

@section('content')
<div class="">
    <div class="max-w-6xl mx-auto px-4">
        @include('admin.includes.message')

        <div class="card bg-white mb-6">
            <div class="card-header">
                <h4 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Edit Question
                </h4>
            </div>

            <div class="card-body">
                  <form method="POST" action="" id="questionForm">
            @csrf
            @method('PUT')

            @php
                $questionText = json_decode($question->question_text, true);
                $solutionText = json_decode($question->solution, true);
                $savedOptions = json_decode($question->options, true) ?? [];
                $savedAnswers = is_array($question->correct_answer) 
                                ? $question->correct_answer 
                                : explode(',', $question->correct_answer);
            @endphp

            <div class="row">
                
                 <div class="mb-3 col-md-4 ">
                         <label class="form-label small text-muted fw-bold">Select Chapter</label>
                         <select class="form-control form-select selectChapter" name="chapter_id" data-id="{{$question->id}}" style="width:100%">
                                            @php $chapters = Lesson::where("course_id",$question->course_id)->get(); @endphp
                                            <option value="">Select Chapter</option>
                                            @foreach($chapters as $c)
                                                <option value="{{$c->id}}" @if($c->id==$question->chapter_id) selected @endif>
                                                    {{ Str::limit($c->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                    </div>
                {{-- Left Column --}}
                <div class="col-lg-12">
                   
                    {{-- Question Text --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-question-mark me-2"></i>Question Text</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small text-muted fw-bold">English</label>
                                    <textarea id="question_text_en" name="question_text[en]" class="form-control" rows="3">{{ old('question_text.en', $questionText['en'] ?? '') }}</textarea>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    

                    {{-- Options --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2"></i>Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="options-container">
                                @foreach($savedOptions as $key => $option)
                                    <div class="col-md-6 option-item">
                                        <div class="border rounded-3 p-3 option-card" data-option-id="{{ $key }}">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light text-dark">Option {{ $key }}</span>
                                                <div class="form-check form-switch">
                                                    <input type="radio" name="correct_answer[]" value="{{ $key }}"
                                                        class="form-check-input correct-answer-switch"
                                                        {{ in_array($key, old('correct_answer', $savedAnswers)) ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Correct</label>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small text-muted">English</label>
                                                <textarea id="option_{{ $key }}_en" name="options[{{ $key }}][en]" class="form-control" rows="2">{{ old("options.$key.en", $option['en'] ?? '') }}</textarea>
                                            </div>
                                           
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-12">
                    {{-- Solution --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-check-circle me-2"></i>Solution</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">English</label>
                                <textarea id="solution_en" name="solution[en]" class="form-control" rows="3">{{ old('solution.en', $solutionText['en'] ?? '') }}</textarea>
                            </div>
                            
                        </div>
                    </div>

                    {{-- Marks & Settings --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-cog me-2"></i>Question Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Marks</label>
                                <input type="number" name="marks" min="1" class="form-control marks-input" 
                                    value="{{ old('marks', $question->marks) }}">
                            </div>
                             <div class="">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select" required>
                              <option value="">-- Select Difficulty --</option>
                            <option value="easy" @if($question->difficulty=='easy') selected @endif>Easy</option>
                            <option value="medium" @if($question->difficulty=='medium') selected @endif>Medium</option>
                            <option value="hard" @if($question->difficulty=='hard') selected @endif>Hard</option>
                        </select>
                        </div>
                        
                          <div class="mt-3">
                            <label class="form-label fw-semibold">Is Previous Year Question?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                            <option value="0" @if($question->is_prev_year==0) selected @endif>No</option>
                            <option value="1" @if($question->is_prev_year==1) selected @endif>Yes</option>
                        </select>
                        </div>
                           
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-2 sticky-bottom py-3 bg-light mt-4 rounded-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bx bx-save me-1"></i> Update Question
                </button>
               
                <a href="{{ url()->previous() }}" class="btn btn-light px-4 ms-auto">
    Cancel
</a>

            </div>

        </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('after-scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor for question text
        CKEDITOR.replace('question_text_en', {
            height: 200,
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' }
            ]
        });

        // Initialize CKEditor for all options (dynamic)
        document.querySelectorAll('textarea[id^="option_"][id$="_en"]').forEach(function(textarea) {
            if (textarea.id) {
                CKEDITOR.replace(textarea.id, {
                    height: 150,
                    toolbarGroups: [
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                        { name: 'links' },
                        { name: 'insert' },
                        '/',
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
                        { name: 'styles' },
                        { name: 'colors' }
                    ]
                });
            }
        });

        // Initialize CKEditor for solution
        CKEDITOR.replace('solution_en', {
            height: 200,
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' }
            ]
        });
    });
</script>
@endpush
