@extends('backend.layouts.app')

@section('title', 'Add Mock | ' . env('APP_NAME'))

@section('page_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Compact Form Styling */
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
        height: 150px;
    }
    .select2-selection__choice {
        font-size: 0.8rem;
        padding: 1px 6px;
    }
    div#chapter-questions-container {
    border: 1px solid #ddd1d1;
    background: #efefef;
}
</style>
@stop

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">Add New Mock</h6>
                <a href="{{route('mockseries.testlist',$id)}}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>

            <div class="card-body">
                <form action="{{route('mockseries.save-test',$id)}}" method="POST" class="row g-3">
                    @csrf
                    <input type="hidden" name="mock_series_id" value="{{ $id }}">

                    <!-- Mock Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Mock Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required>
                    </div>

                    <!-- Total Questions -->
                    <div class="col-md-3">
                        <label for="total_questions" class="form-label">Questions <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_questions" name="total_questions" 
                               value="{{ old('total_questions') }}" min="1" required>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-3">
                        <label for="duration" class="form-label">Duration (min) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration" 
                               value="{{ old('duration') }}" min="1" required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                    </div>

                 

                    <!-- Dynamic Inputs for chapter questions -->
                    <div class="col-md-12 mt-4 mb-4" id="chapter-questions-container">
                        {{-- Dynamic inputs appear here --}}
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
 <div class="col-md-2">
                            <label class="form-label fw-semibold">Is Previous Year Mock?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                         
                            <option value="0"  >No</option>
                            <option value="1"  >Yes</option>
                        </select>
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-save"></i> Save Mock
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
            placeholder: "Select chapters",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#chapters').parent() // fixes overlap issue
        });

        let container = $("#chapter-questions-container");

        // On chapter selection change
        $('#chapters').on('change', function () {
            container.empty(); // Clear old inputs

            let selected = $(this).val(); // Get selected chapters
            if (selected) {
                selected.forEach(chapterId => {
                    let chapterName = $("#chapters option[value='"+chapterId+"']").text();

                    container.append(`
                        <div class="mb-2 row align-items-center chapter-question-row">
                            <div class="col-md-6">
                                <label class="form-label">Questions from "${chapterName}"</label>
                                <input type="number" name="chapter_questions[${chapterId}]"
                                       class="form-control"
                                       min="0" placeholder="Enter no. of questions">
                            </div>
                        </div>
                    `);
                });
            }
        });
    });
</script>
@stop
