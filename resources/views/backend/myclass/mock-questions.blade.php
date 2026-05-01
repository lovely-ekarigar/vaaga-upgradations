@extends('backend.layouts.app')

@section('title', app_name() . ' | Mock Test Questions')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0"> 
                    Mock Test Questions

                    @if(isset($mockTest))
                        <small class="text-muted">| {{ $mockTest->name }}</small>
                    @endif
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    @if(auth()->user()->hasRole('administrator'))
                        <a href="{{ route('admin.batch') }}" class="btn btn-warning ml-1" data-toggle="tooltip" title="Back to Batches">
                            <i class="fas fa-arrow-left"></i> Back to Batches
                        </a>
                    @else
                        <a href="{{ route('admin.myclass') }}" class="btn btn-warning ml-1" data-toggle="tooltip" title="Back to Classes">
                            <i class="fas fa-arrow-left"></i> Back to Classes
                        </a>
                    @endif
                </div><!--btn-toolbar-->
            </div><!--col-->
        </div><!--row-->

        <!-- Schedule Date Section -->
        <div class="row mt-4">
            <div class="col">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-calendar"></i> Schedule Mock Test</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="scheduled_date" class="col-sm-3 col-form-label">
                                <strong>Set Test Date:</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="date" 
                                       class="form-control" 
                                       id="scheduled_date" 
                                       name="scheduled_date"
                                       min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> 
                                    <strong>Today's date:</strong> Test activates immediately when you submit. 
                                    <strong>Future date:</strong> Test will be scheduled and auto-activate on that date.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                @if(isset($sectionsData) && count($sectionsData) > 0)
                    @foreach($sectionsData as $section)
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-folder"></i> {{ $section['name'] }}
                                    <span class="badge badge-light float-right">{{ count($section['questions']) }} Questions</span>
                                    @if(isset($section['difficulty']) && $section['difficulty'])
                                        <span class="badge badge-warning ml-2">
                                            <i class="fas fa-signal"></i> {{ ucfirst($section['difficulty']) }}
                                        </span>
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(count($section['questions']) > 0)
                                    @foreach($section['questions'] as $index => $question)
                                        @php
                                            $uniqueIndex = $section['id'] . '_' . $index;
                                        @endphp
                                        <div class="question-item mb-4 p-3 border rounded" 
                                             data-question-container="{{ $uniqueIndex }}"
                                             data-question-id="{{ $question->id }}"
                                             data-section-id="{{ $section['id'] }}">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0">
                                                    <span class="badge badge-secondary">Question {{ $index + 1 }}</span>
                                                </h6>
                                                <div>
                                                    @if($question->marks)
                                                        <span class="badge badge-info">{{ $question->marks }} Marks</span>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline-primary ml-2 refresh-question-btn" 
                                                            data-chapter-id="{{ $question->chapter_id }}" 
                                                            data-section-id="{{ $section['id'] }}"
                                                            data-unique-index="{{ $uniqueIndex }}" 
                                                            data-display-index="{{ $index + 1 }}">
                                                        <i class="fas fa-sync-alt"></i> Replace
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger ml-2 report-question-btn" 
                                                            data-question-id="{{ $question->id }}"
                                                            title="Report this question">
                                                        <i class="fas fa-flag"></i> Report
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="question-text mb-3">
                                                <strong>Q:</strong> 
                                                @php
                                                    $questionText = $question->question_text;
                                                    // Parse if JSON
                                                    if (is_string($questionText)) {
                                                        $decoded = json_decode($questionText, true);
                                                        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                                                            $questionText = $decoded['en'];
                                                        }
                                                    }
                                                @endphp
                                                {!! $questionText !!}
                                            </div>
                                            
                                            @if($question->options)
                                                <div class="options mb-2">
                                                    <strong>Options:</strong>
                                                    <div class="mt-2">
                                                        @php
                                                            $opts = $question->options;
                                                            // Parse if string
                                                            if (is_string($opts)) {
                                                                $opts = json_decode($opts, true);
                                                            }
                                                            // Handle nested structure like {"1":{"en":"text"}}
                                                            $optionsArray = [];
                                                            if (is_array($opts)) {
                                                                foreach ($opts as $key => $value) {
                                                                    if (is_array($value) && isset($value['en'])) {
                                                                        $optionsArray[] = $value['en'];
                                                                    } elseif (is_string($value)) {
                                                                        $optionsArray[] = $value;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @if(count($optionsArray) > 0)
                                                            @foreach($optionsArray as $idx => $opt)
                                                                <div class="mb-1">
                                                                    <strong>{{ chr(65 + $idx) }}.</strong> {!! $opt !!}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($question->correct_answer)
                                                <div class="mt-2">
                                                    <strong>Correct Answer:</strong> 
                                                    <span class="badge badge-success">{{ $question->correct_answer }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- @if($question->solution)
                                                <div class="solution mt-2">
                                                    <strong>Solution:</strong>
                                                    <div class="p-2 bg-light rounded mt-1">
                                                        @php
                                                            $solutionText = $question->solution;
                                                            // Parse if JSON
                                                            if (is_string($solutionText)) {
                                                                $decoded = json_decode($solutionText, true);
                                                                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                                                                    $solutionText = $decoded['en'];
                                                                }
                                                            }
                                                        @endphp
                                                        {!! $solutionText !!}
                                                    </div>
                                                </div>
                                            @endif -->
                                            
                                            @if($question->difficulty)
                                                <div class="mt-2">
                                                    <strong>Difficulty:</strong> 
                                                    <span class="badge badge-warning">{{ ucfirst($question->difficulty) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No questions found for this section.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Submit Button at Bottom -->
                    <div class="row mt-4 mb-4">
                        <div class="col text-center">
                            <form method="POST" action="{{ route('admin.myclass.submitMock', $mockTest->id) }}" id="submitMockForm">
                                @csrf
                                <input type="hidden" name="batch_id" value="{{ $batchId }}">
                                <input type="hidden" name="questions_data" id="questionsData" value="">
                                <input type="hidden" name="scheduled_at" id="scheduledAtInput" value="">
                                <button type="button" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-check"></i> Submit & Schedule Mock Test
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> No sections or questions configured for this mock test.
                    </div>
                @endif
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitConfirmModal" tabindex="-1" role="dialog" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog submit-confirm-modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="submitConfirmModalLabel">
                    <i class="fas fa-check-circle"></i> Confirm Mock Test Submission
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> Please review the details below before confirming.
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <strong>Test Name:</strong>
                    </div>
                    <div class="col-sm-8">
                        <span id="confirmTestName" class="badge badge-primary confirm-test-name-badge"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <strong>Scheduled Date:</strong>
                    </div>
                    <div class="col-sm-8">
                        <span id="confirmTestDate" class="badge badge-success" style="font-size: 1em; padding: 8px 12px;"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                    <i class="fas fa-check"></i> Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Report Question Modal -->
<div class="modal fade" id="reportQuestionModal" tabindex="-1" role="dialog" aria-labelledby="reportQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportQuestionModalLabel">Report Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportQuestionForm">
                    <input type="hidden" id="report_question_id" name="question_id">
                    <div class="form-group">
                        <label for="report_message">What's wrong with this question?</label>
                        <textarea class="form-control" id="report_message" name="message" rows="4" 
                                  placeholder="Please describe the issue (e.g., wrong answer, unclear question, typo...)" 
                                  required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="submitReportBtn">
                    <i class="fas fa-flag"></i> Submit Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<style>
    .question-item {
        background-color: #f8f9fa;
    }
    .question-item:hover {
        background-color: #e9ecef;
    }
    /* Ensure base64 images in questions display properly */
    .question-text img,
    .options img,
    .solution img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px 0;
    }

    .submit-confirm-modal-dialog {
        width: min(92vw, 720px);
        max-width: 92vw;
    }

    .confirm-test-name-badge {
        display: inline-block;
        max-width: 100%;
        font-size: 1em;
        padding: 8px 12px;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.4;
        text-align: left;
    }
</style>

<script>
console.log('Mock questions script loaded');

$(document).ready(function() {
    console.log('Document ready, jQuery version:', $.fn.jquery);
    console.log('Number of refresh buttons found:', $('.refresh-question-btn').length);
    
    // Use event delegation so it works for dynamically added buttons too
    $(document).on('click', '.refresh-question-btn', function(e) {
        e.preventDefault();
        console.log('Click handler triggered');
        
        var button = $(this);
        var chapterId = button.data('chapter-id');
        var sectionId = button.data('section-id');
        var uniqueIndex = button.data('unique-index');
        var displayIndex = button.data('display-index');
        var container = $('[data-question-container="' + uniqueIndex + '"]');
        
        // Preserve the original section ID (mock test section, not chapter)
        var originalSectionId = container.attr('data-section-id');
        
        // Collect all currently displayed question IDs to exclude them
        var excludeQuestionIds = [];
        $('.question-item').each(function() {
            var qId = $(this).attr('data-question-id');
            if (qId) {
                excludeQuestionIds.push(parseInt(qId));
            }
        });
        
        console.log('Refresh clicked for chapter:', chapterId, 'section:', sectionId, 'unique index:', uniqueIndex);
        console.log('Container found:', container.length);
        console.log('Original section ID:', originalSectionId);
        console.log('Excluding question IDs:', excludeQuestionIds);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
        $.ajax({
            url: '{{ route("admin.myclass.refreshQuestion") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                chapter_id: chapterId,
                section_id: sectionId,
                exclude_question_ids: excludeQuestionIds
            },
            success: function(response) {
                console.log('Response received:', response);
                if(response.success) {
                    var q = response.question;
                    
                    // Debug: Log the question text to see if image is present
                    console.log('Question text length:', q.question_text ? q.question_text.length : 0);
                    console.log('Question text preview:', q.question_text ? q.question_text.substring(0, 200) : 'empty');
                    console.log('Has image tag:', q.question_text ? q.question_text.includes('<img') : false);
                    
                    // Check if base64 image data looks complete
                    if(q.question_text && q.question_text.includes('data:image')) {
                        var base64Match = q.question_text.match(/data:image\/[^;]+;base64,([^"']+)/);
                        if(base64Match) {
                            var base64Length = base64Match[1].length;
                            console.log('Base64 data length:', base64Length);
                            if(base64Length < 100) {
                                console.warn('⚠️ WARNING: Base64 data seems too short, image may be truncated!');
                            }
                        }
                    }
                    
                    // Build options HTML structure (without content to avoid truncation)
                    var optionsHtml = '';
                    var options = q.options;
                    
                    // Backend already parsed options to flat array
                    if(options && Array.isArray(options) && options.length > 0) {
                        optionsHtml = '<div class="options mb-2"><strong>Options:</strong><div class="mt-2 options-container">';
                        options.forEach(function(opt, idx) {
                            var optionLetter = String.fromCharCode(65 + idx);
                            optionsHtml += '<div class="mb-1 option-item" data-option-index="' + idx + '"><strong>' + optionLetter + '.</strong> <span class="option-content-' + idx + '"></span></div>';
                        });
                        optionsHtml += '</div></div>';
                    }
                    
                    // Build correct answer HTML
                    var answerHtml = q.correct_answer 
                        ? '<div class="mt-2"><strong>Correct Answer:</strong> <span class="badge badge-success">' + q.correct_answer + '</span></div>'
                        : '';
                    
                    // Build solution HTML structure (without content)
                    var solutionHtml = q.solution 
                        ? '<div class="solution mt-2"><strong>Solution:</strong><div class="p-2 bg-light rounded mt-1 solution-content"></div></div>'
                        : '';
                    
                    // Build difficulty HTML
                    var difficultyHtml = q.difficulty 
                        ? '<div class="mt-2"><strong>Difficulty:</strong> <span class="badge badge-warning">' + q.difficulty.charAt(0).toUpperCase() + q.difficulty.slice(1) + '</span></div>'
                        : '';
                    
                    // Build the structure first with placeholders
                    var structureHtml = 
                        '<div class="d-flex justify-content-between align-items-start mb-2">' +
                            '<h6 class="mb-0">' +
                                '<span class="badge badge-secondary">Question ' + displayIndex + '</span>' +
                            '</h6>' +
                            '<div>' +
                                (q.marks ? '<span class="badge badge-info">' + q.marks + ' Marks</span>' : '') +
                                '<button class="btn btn-sm btn-outline-primary ml-2 refresh-question-btn" ' +
                                        'data-chapter-id="' + q.chapter_id + '" ' +
                                        'data-section-id="' + sectionId + '" ' +
                                        'data-unique-index="' + uniqueIndex + '" ' +
                                        'data-display-index="' + displayIndex + '">' +
                                    '<i class="fas fa-sync-alt"></i> Replace' +
                                '</button>' +
                                '<button class="btn btn-sm btn-outline-danger ml-2 report-question-btn" ' +
                                        'data-question-id="' + q.id + '" ' +
                                        'title="Report this question">' +
                                    '<i class="fas fa-flag"></i> Report' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                        '<div class="question-text mb-3">' +
                            '<strong>Q:</strong> <span class="question-content-placeholder"></span>' +
                        '</div>' +
                        optionsHtml +
                        answerHtml +
                        solutionHtml +
                        difficultyHtml;
                    
                    // Replace the container content
                    container.html(structureHtml);
                    
                    // Immediately inject content using DOM manipulation (more reliable than setTimeout)
                    var $questionContent = container.find('.question-content-placeholder');
                    if($questionContent.length > 0) {
                        // Use jQuery to properly inject HTML with images
                        $questionContent.replaceWith($('<span class="question-content">').html(q.question_text));
                        console.log('✓ Question text injected');
                    } else {
                        console.error('✗ Could not find question content placeholder');
                    }
                    
                    // Inject options content
                    if(options && Array.isArray(options) && options.length > 0) {
                        options.forEach(function(opt, idx) {
                            var $optEl = container.find('.option-content-' + idx);
                            if($optEl.length > 0) {
                                $optEl.html(opt);
                                console.log('✓ Option ' + idx + ' injected');
                            } else {
                                console.error('✗ Could not find option element ' + idx);
                            }
                        });
                    }
                    
                    // Inject solution content
                    if(q.solution) {
                        var $solEl = container.find('.solution-content');
                        if($solEl.length > 0) {
                            $solEl.html(q.solution);
                            console.log('✓ Solution injected');
                        }
                    }
                    
                    // Update data attributes with new question
                    container.attr('data-question-id', q.id);
                    // Keep the original section ID (don't replace with chapter_id)
                    container.attr('data-section-id', originalSectionId);
                    
                    // Add visual indicator that question was refreshed
                    container.addClass('border-success');
                    container.find('.badge-secondary').removeClass('badge-secondary').addClass('badge-success');
                    
                    console.log('✅ Question refreshed successfully!');
                    console.log('   - New Question ID:', q.id, '(This will be saved when you submit)');
                    console.log('   - Section ID:', originalSectionId);
                    console.log('   - Container attr data-question-id:', container.attr('data-question-id'));
                    
                    // Success: question refreshed (no blocking alert shown)
                } else {
                    console.error('Error in response:', response);
                    alert(response.message || 'No other questions found');
                    button.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Replace');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.error('Response text:', xhr.responseText);
                alert('Error refreshing question: ' + error + '. Please check console for details.');
                button.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Replace');
            }
        });
    });
    
    // Handle submit button click - show confirmation modal
    $(document).on('click', '#submitBtn', function(e) {
        e.preventDefault();
        
        console.log('🚀 SUBMITTING MOCK TEST - Collecting all question IDs...');
        
        var questionsData = {};
        var questionCounter = 0;
        
        // Loop through all question items (including refreshed ones)
        $('.question-item').each(function() {
            questionCounter++;
            var questionId = $(this).attr('data-question-id'); // Use attr to get the actual DOM attribute
            var sectionId = $(this).attr('data-section-id');
            
            console.log(`  Question ${questionCounter}:`, {
                questionId: questionId,
                sectionId: sectionId,
                containerIndex: $(this).attr('data-question-container')
            });
            
            if (questionId && sectionId) {
                if (!questionsData[sectionId]) {
                    questionsData[sectionId] = [];
                }
                questionsData[sectionId].push(parseInt(questionId));
            }
        });
        
        // Get the scheduled date, default to today if empty
        var scheduledDate = $('#scheduled_date').val();
        if (!scheduledDate) {
            // Automatically set to current date
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');
            scheduledDate = year + '-' + month + '-' + day;
            console.log('No date selected, using current date:', scheduledDate);
        }
        
        // Check if we have questions data
        if (Object.keys(questionsData).length === 0) {
            alert('No questions found. Please ensure questions are loaded on the page.');
            return;
        }
        
        // Set the hidden fields
        var questionsJson = JSON.stringify(questionsData);
        $('#questionsData').val(questionsJson);
        $('#scheduledAtInput').val(scheduledDate);
        
        console.log('📦 Final questions data to be saved in batch_mock_questions table:');
        console.log(questionsData);
        console.log('📅 Scheduled date:', scheduledDate);
        console.log('📝 JSON format:', questionsJson);
        console.log('🔍 Hidden field #questionsData value:', $('#questionsData').val());
        console.log('🔍 Hidden field #scheduledAtInput value:', $('#scheduledAtInput').val());
        console.log('✅ These question IDs (including any refreshed ones) will be saved!');
        
        // Format the date for display
        var dateObj = new Date(scheduledDate);
        var formattedDate = dateObj.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Show confirmation modal
        $('#confirmTestName').text('{{ $mockTest->name ?? "Mock Test" }}');
        $('#confirmTestDate').text(formattedDate);
        $('#submitConfirmModal').modal('show');
    });
    
    // Handle confirmation button click
    $('#confirmSubmitBtn').on('click', function() {
        $('#submitConfirmModal').modal('hide');
        // Submit the form directly
        document.getElementById('submitMockForm').submit();
    });
    
    
    // Report Question functionality
    $(document).on('click', '.report-question-btn', function(e) {
        e.preventDefault();
        var questionId = $(this).data('question-id');
        console.log('Report button clicked for question:', questionId);
        
        $('#report_question_id').val(questionId);
        $('#report_message').val('');
        $('#reportQuestionModal').modal('show');
    });
    
    $('#submitReportBtn').on('click', function() {
        var questionId = $('#report_question_id').val();
        var message = $('#report_message').val().trim();
        
        if (!message) {
            alert('Please describe the issue with this question.');
            $('#report_message').focus();
            return;
        }
        
        var button = $(this);
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');
        
        $.ajax({
            url: '{{ route("admin.myclass.reportQuestion") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                question_id: questionId,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    $('#reportQuestionModal').modal('hide');
                    alert('Thank you! Your report has been submitted successfully.');
                    console.log('✅ Question reported successfully');
                } else {
                    alert('Error: ' + (response.message || 'Failed to submit report'));
                }
                button.prop('disabled', false).html('<i class="fas fa-flag"></i> Submit Report');
            },
            error: function(xhr, status, error) {
                console.error('Report Error:', xhr.responseText);
                alert('Error submitting report. Please try again.');
                button.prop('disabled', false).html('<i class="fas fa-flag"></i> Submit Report');
            }
        });
    });
});
</script>
@endpush
