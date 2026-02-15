@extends('backend.layouts.app')

@section('title', "Add Question | " . env('APP_NAME'))

@section('page_css')
<style>
    svg { height: 30px; }

    /* Hide CKEditor notifications */
    .cke_notifications_area { display: none !important; }
</style>
@stop

@section('content')
<div class="max-w-6xl mx-auto px-4">
    @include('admin.includes.message')

    <div class="card bg-white mb-6">
        <div class="card-header">
            <h4 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Add Question
            </h4>
        </div>

        <div class="card-body">
            <form method="POST" action="" id="questionForm">
                @csrf

                <div class="row">
                    {{-- Select Course --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select form-control" required>
                            <option value="">-- Choose Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Chapter --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Select Chapter</label>
                        <select name="chapter_id" id="chapter_id" class="form-select  form-control" required>
                            <option value="">-- Choose Chapter --</option>
                            {{-- Filled dynamically via AJAX when course is selected --}}
                        </select>
                    </div>

                    {{-- Question Text --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Question Text</label>
                        <textarea id="question_text_en" name="question_text[en]" class="form-control" rows="3">{{ old('question_text.en') }}</textarea>
                    </div>

                    {{-- Options --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Options</label>
                        <div class="row g-3">
                            @for($i=1; $i<=4; $i++)
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-light text-dark">Option {{ $i }}</span>
                                            <div class="form-check">
                                                <input type="radio" name="correct_answer[]" value="{{ $i }}" class="form-check-input"
                                                    {{ (old('correct_answer') == $i) ? 'checked' : '' }}>
                                                <label class="form-check-label small">Correct</label>
                                            </div>
                                        </div>
                                        <textarea id="option_{{ $i }}_en" name="options[{{ $i }}][en]" class="form-control" rows="2">{{ old("options.$i.en") }}</textarea>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Solution --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Solution</label>
                        <textarea id="solution_en" name="solution[en]" class="form-control" rows="3">{{ old('solution.en') }}</textarea>
                    </div>

                    {{-- Marks --}}
                    <div class="col-lg-4 mb-4">
                        <label class="form-label fw-semibold">Marks</label>
                        <input type="number" name="marks" min="1" class="form-control" value="{{ old('marks', 1) }}">
                    </div>
                       <div class="col-md-4">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select" required>
                            <option value="">-- Select Difficulty --</option>
                            <option value="easy" selected >Easy</option>
                            <option value="medium" >Medium</option>
                            <option value="hard" >Hard</option>
                        </select>
                    </div>
                    
                     <div class="col-md-4">
                            <label class="form-label fw-semibold">Is Previuos Year Question?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                         
                            <option value="0" selected >No</option>
                            <option value="1" >Yes</option>
                        </select>
                    </div>
                    
                    
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> Save Question
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
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

        // Initialize CKEditor for all options (4 options)
        for(let i = 1; i <= 4; i++) {
            CKEDITOR.replace('option_' + i + '_en', {
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

<script>
// load chapters dynamically
document.getElementById('course_id').addEventListener('change', function() {
    let courseId = this.value;
    let chapterSelect = document.getElementById('chapter_id');
    chapterSelect.innerHTML = '<option value="">Loading...</option>';

    fetch(`/user/chapters/by-subject/${courseId}`)
        .then(response => response.json())
        .then(data => {
            chapterSelect.innerHTML = '<option value="">-- Choose Chapter --</option>';
            data.forEach(chapter => {
                chapterSelect.innerHTML += `<option value="${chapter.id}">${chapter.title}</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading chapters:', error);
            chapterSelect.innerHTML = '<option value="">-- Choose Chapter --</option>';
        });
});
</script>
@endpush
