@extends('backend.layouts.app')

@section('title', 'Edit Mock Test | ' . env('APP_NAME'))

@section('page_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    .form-control, .select2-container--default .select2-selection--multiple {
        font-size: 0.9rem;
        padding: 6px 10px;
        border-radius: 6px;
    }
    .mb-3 {
        margin-bottom: 0.8rem !important;
    }
    .card-body {
        padding: 1rem 1.25rem;
    }
    .btn {
        padding: 6px 14px;
        font-size: 0.9rem;
        border-radius: 6px;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
    }
    .select2-selection__choice {
        font-size: 0.8rem;
        padding: 1px 6px;
    }
    div#chapter-questions-container {
        border: 1px solid #ddd1d1;
        background: #efefef;
        padding: 10px;
        border-radius: 8px;
    }
</style>
@stop

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">Edit Mock Test</h6>
                <a href="{{ route('mockseries.testlist', $mock->mock_series_id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('mockseries.update-test', $mock->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <!-- Test Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Mock Test Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $mock->name) }}" required>
                    </div>

                    <!-- Total Questions -->
                    <div class="col-md-3">
                        <label for="total_questions" class="form-label">Questions <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_questions" name="total_questions" 
                               value="{{ old('total_questions', $mock->total_questions) }}" min="1" required>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-3">
                        <label for="duration" class="form-label">Duration (min) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration" 
                               value="{{ old('duration', $mock->duration) }}" min="1" required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $mock->description) }}</textarea>
                    </div>

                    <!-- Sections -->
                    <div class="col-md-12">
                        <label for="chapters" class="form-label">Sections</label>
                        <select name="chapters[]" id="chapters" class="form-control select2" multiple>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ in_array($section->id, old('chapters', $selectedChapters)) ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Chapter Questions -->
                    <div class="col-md-12 mt-2" id="chapter-questions-container"></div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="active" {{ old('status', $mock->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $mock->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Is Previous Year -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Is Previous Year Test?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                            <option value="0" @if($mock->is_prev_year=='0') selected @endif>No</option>
                            <option value="1" @if($mock->is_prev_year=='1') selected @endif>Yes</option>
                        </select>
                    </div>

                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Mock Test
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize select2
    $('#chapters').select2({
        placeholder: "Select sections",
        allowClear: true,
        width: '100%',
        dropdownParent: $('#chapters').parent()
    });

    let container = $("#chapter-questions-container");

    // PHP → JS
    let allSections = {!! json_encode($sections) !!};
    let preloadedQuestions = {!! json_encode(old('chapter_questions', $chapterQuestions ?? [])) !!};

    // Render function
    function renderSections(selectedSections) {
        container.empty();

        if (!selectedSections || selectedSections.length === 0) return;

        selectedSections.forEach(sectionId => {
            let section = allSections.find(s => s.id == sectionId);
            if (!section) return;

            container.append(`
                <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                    <h6 class="fw-semibold mb-2">${section.name}</h6>
                    <div id="section-${section.id}-lessons" class="row g-2"></div>
                </div>
            `);

            let lessonsContainer = $(`#section-${section.id}-lessons`);
            let chapterList = section.chapterlist || [];

            if (chapterList.length === 0) {
                lessonsContainer.append(`<div class="col-12"><small class="text-muted">No lessons found for this section.</small></div>`);
            } else {
                chapterList.forEach(ch => {
                    let lesson = ch.lesson;
                    if (!lesson) return;

                    let lessonId = lesson.id;
                    let val = preloadedQuestions?.[sectionId]?.[lessonId] ?? '';

                    lessonsContainer.append(`
                        <div class="col-md-6">
                            <label class="form-label mb-1">${lesson.title}</label>
                            <input type="number"
                                   class="form-control form-control-sm"
                                   name="chapter_questions[${sectionId}][${lessonId}]"
                                   value="${val}"
                                   min="0"
                                   placeholder="Enter no. of questions">
                        </div>
                    `);
                });
            }
        });
    }

    // Initial render (edit mode)
    renderSections($('#chapters').val());

    // On change
    $('#chapters').on('change', function() {
        renderSections($(this).val());
    });
});
</script>
@stop
