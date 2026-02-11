<?php

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Str;

?>
@extends('backend.layouts.app')

@section('title')
Question Bank | {{ env('APP_NAME') }}
@stop

@section('content')

<style>
    .form-select option{
        color:#000 !important;
    }
    .verification-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .btn-verify {
        background: linear-gradient(45deg, #ff6b35, #f7931e);
        border: none;
        color: white;
    }
    .verification-modal .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .verification-modal .modal-header {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        border-radius: 10px 10px 0 0;
    }
    .question-preview {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        background: #fafafa;
    }
    .verification-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .verification-action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
    }
    .course-filter {
        border-left: 3px solid #007bff;
        padding-left: 10px;
        margin-bottom: 15px;
    }
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0 text-black card-title">Questions Bank</h5>

                <!-- Totals -->
                <div class="d-flex gap-4" style="gap: 8px;">
                    <span class="badge bg-primary p-2">
                        Total Questions: <strong>{{ $totalQuestions }}</strong>
                    </span>
                    <span class="badge bg-success p-2">
                        Total Marks: <strong>{{ $totalMarks }}</strong>
                    </span>
                    <span class="badge bg-warning p-2">
                        Pending Verification: <strong id="pendingCount">{{ $pendingVerificationCount ?? 0 }}</strong>
                    </span>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2" style="gap: 8px;">
                    <!-- Verify Questions Button -->
                    <button type="button" class="btn btn-sm btn-verify" id="verifyQuestionsBtn" 
                            {{ ($pendingVerificationCount ?? 0) > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-check-circle"></i> Verify Questions ({{ $pendingVerificationCount ?? 0 }})
                    </button>
                    
                    <a href="{{ route('admin.exams.questions.import') }}" class="btn btn-sm btn-primary me-2">Import Questions</a>
                    <a href="{{ route('admin.exams.questions.add') }}" class="btn btn-sm btn-info me-2">Add Question</a>
                    <!--<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#generateQuestionModal">-->
                    <!--    <i class="bx bx-bulb"></i> AI Generate Questions-->
                    <!--</button>-->
                </div>
            </div>

            <div class="card-body">
                <!-- Course Filter for Verification -->
            

                <!-- Search and Filter Form -->
                <form method="GET" id="filterForm" class="row g-2 mb-3">
                   
                    <!-- Subject -->
                    <div class="col-md-3">
                        <select name="subject_id" id="filterSubject" class="form-control form-select">
                            <option value="">-- Filter by Subject --</option>
                            @foreach($subjects ?? [] as $sub)
                                <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chapter -->
                    <div class="col-md-3">
                        <select name="chapter_id" id="filterChapter" class="form-control form-select">
                            <option value="">-- Filter by Chapter --</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select">
                            <option value="">-- Select Difficulty --</option>
                            <option value="easy" @if(request('difficulty')=='easy') selected @endif>Easy</option>
                            <option value="medium" @if(request('difficulty')=='medium') selected @endif>Medium</option>
                            <option value="hard" @if(request('difficulty')=='hard') selected @endif>Hard</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="verification_status" id="verificationStatus" class="form-control form-select">
                            <option value="">-- Verification Status --</option>
                            <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('verification_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    
                </form>

                <!-- Quick Jump -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="number" min="1" placeholder="Enter Question ID" class="form-control" id="qid" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" id="jump">
                                    <i class="fas fa-arrow-right"></i> JUMP QID
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if($questions->count() > 0)
                <div style="width:50%;" class="mb-4">
                    <div class="input-group">
                            <input type="text"  placeholder="Enter " class="form-control" id="key" value="{{request('key')}}" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" id="search">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle dtable">
                            <thead class="table-light">
                                <tr>
                                   
                                    <th width="60">QID</th>
                                    <th>Question Text</th>
                                    <th width="100">Difficulty</th>
                                  
                                    <th width="150">Chapter</th>
                                    <th width="120">Verification</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $k=>$question)
                                <tr>
                                   
                                    <td><strong>#{{ $question->id }}</strong></td>
                                    <td>{{ Str::limit(strip_tags(json_decode($question->question_text,true)['en'] ?? 'No question text'), 60) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($question->difficulty == 'easy') badge-success
                                            @elseif($question->difficulty == 'medium') badge-warning
                                            @else badge-danger @endif">
                                            {{ ucwords($question->difficulty) }}
                                        </span>
                                    </td>
                                  
                                    
                                    <td>
                                        <select class="form-control form-select selectChapter" data-id="{{$question->id}}" style="width:100%">
                                            @php $chapters = Lesson::where("course_id",$question->course_id)->get(); @endphp
                                            <option value="">Select Chapter</option>
                                            @foreach($chapters as $c)
                                                <option value="{{$c->id}}" @if($c->id==$question->chapter_id) selected @endif>
                                                    {{ Str::limit($c->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                    <td>
                                        @if($question->verification_status == 'approved')
                                            <span class="badge badge-success verification-badge">
                                                <i class="fas fa-check"></i> Approved
                                            </span>
                                            @if($question->verified_at)
                                                <br><small class="text-muted">{{ date("d M Y h:i A",strtotime($question->verified_at)) }}</small>
                                            @endif
                                        @elseif($question->verification_status == 'rejected')
                                            <span class="badge badge-danger verification-badge">
                                                <i class="fas fa-times"></i> Rejected
                                            </span>
                                            @if($question->verified_at)
                                                <br><small class="text-muted">{{ date("d M Y h:i A",strtotime($question->verified_at)) }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning verification-badge">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="table-actions">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.exams.questions.edit', ['question' => $question->id]) }}" 
                                               class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a> 

                                         

                                         
                                            <!-- Delete Form -->
                                            <form action="{{ route('admin.exams.questions.destroy', ['question' => $question->id]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this question?');" 
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($questions->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $questions->firstItem() }} to {{ $questions->lastItem() }} of {{ $questions->total() }} entries
                        </div>
                        <nav>
                         {{ $questions->appends(request()->all())->links() }}

                        </nav>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <h4 class="text-danger">No Questions Found</h4>
                        <p class="text-muted">No questions match your search criteria. Please try different filters.</p>
                        <a href="{{ url()->current() }}" class="btn btn-primary">
                            <i class="fas fa-refresh"></i> Reset Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Question Verification Modal -->
<div class="modal fade verification-modal" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Question Verification
                    <span class="badge badge-light ml-2" id="currentCourseBadge"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="verificationForm">
                @csrf
                <div class="modal-body">
                    <div class="verification-stats">
                        <span id="currentQuestionInfo">Question 1 of 0</span>
                        <span id="verificationProgress">0% Complete</span>
                    </div>
                    
                    <input type="hidden" name="question_id" id="verificationQuestionId">
                    <input type="hidden" name="course_id" id="verificationCourseId" value="{{request('subject_id')}}">
                    
                    <div class="question-preview" id="questionPreview">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="mt-2">Loading question...</p>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="verificationRemarks" class="font-weight-bold">Remarks (Optional)</label>
                        <textarea class="form-control" id="verificationRemarks" name="remarks" rows="3" 
                                  placeholder="Add any remarks or comments about this question..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="verification-action-buttons">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Close
                        </button>
                        <button type="button" class="btn btn-warning" id="skipQuestionBtn">
                            <i class="fas fa-forward mr-1"></i> Skip
                        </button>
                        <button type="button" class="btn btn-danger" id="rejectQuestionBtn">
                            <i class="fas fa-times-circle mr-1"></i> Reject
                        </button>
                        <button type="button" class="btn btn-success" id="approveQuestionBtn">
                            <i class="fas fa-check-circle mr-1"></i> Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- AI Generate Questions Modal -->
<div class="modal fade" id="generateQuestionModal" tabindex="-1" role="dialog" aria-labelledby="generateQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="generateQuestionForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AI Generate Questions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Course Selection -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Select Course *</label>
                            <select name="course_id" class="form-control form-select" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses ?? [] as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Selection -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Select Subject *</label>
                            <select name="subject_id" class="form-control form-select" required>
                                <option value="">-- Select Subject --</option>
                                @foreach($subjects ?? [] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prompt -->
                        <div class="col-12">
                            <label class="form-label font-weight-bold">Prompt</label>
                            <textarea name="prompt" class="form-control" rows="3"  
                                      placeholder="e.g. Generate MCQs for Class 10 Science - Physics"></textarea>
                        </div>

                        <!-- Number of Questions -->
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Number of Questions</label>
                            <input type="number" name="count" class="form-control" value="5" min="1" max="300" required>
                        </div>

                        <!-- Marks per Question -->
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Marks per Question</label>
                            <input type="number" name="marks" class="form-control" value="1" min="1" max="100" required>
                        </div>

                        <!-- Difficulty -->
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Difficulty Level</label>
                            <select name="difficulty" class="form-control form-select" required>
                                <option value="">-- Select Difficulty --</option>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-magic-wand"></i> Generate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@stop

@push('after-scripts')
<script>
    $(document).ready(function () {
    // Initialize DataTable
    // $(".dtable").DataTable({ 
    //     pageLength: 25,
    //     "order": [],
    //     "language": {
    //         "search": "Search questions:",
    //         "paginate": {
    //             "previous": "&laquo;",
    //             "next": "&raquo;"
    //         }
    //     }
    // });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Question Verification System
    let pendingQuestions = [];
    let currentQuestionIndex = 0;
    let currentCourseId = null;
    let currentCourseName = '';

    // Auto-submit form when any filter changes
    function setupFilterAutoSubmit() {
        // Get all filter elements
        const filterElements = [
            '#filterSubject',
            '#filterChapter', 
            '#selectDifficulty',
            '#verificationStatus'
        ];

        // Add change event listener to each filter
        filterElements.forEach(selector => {
            $(selector).on('change', function() {
                // Add a small delay to allow multiple selections to complete
                setTimeout(() => {
                    $('#filterForm').submit();
                }, 300);
            });
        });

        // Also submit when pressing Enter in search field
        $('input[name="search"]').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $('#filterForm').submit();
            }
        });
    }

    // Initialize filter auto-submit
    setupFilterAutoSubmit();

    // Load pending questions for verification based on course
    function loadPendingQuestions(courseId = null) {
        let url = "{{ route('admin.exams.questions.pending') }}";
     
            url += '?course_id={{request("subject_id")}}' ;
        

        $.get(url, function(response) {
            pendingQuestions = response.questions || [];
            $('#pendingCount').text(pendingQuestions.length);
            
            if (pendingQuestions.length > 0) {
                $('#verifyQuestionsBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Verify Questions (' + pendingQuestions.length + ')');
                currentCourseId = courseId;
                currentCourseName = response.course_name || 'All Courses';
                
                // Show success message
                showAlert('success', 'Loaded ' + pendingQuestions.length + ' pending questions for verification.');
            } else {
                $('#verifyQuestionsBtn').prop('disabled', true).html('<i class="fas fa-check-circle"></i> Verify Questions (0)');
                if (courseId) {
                    showAlert('info', 'No pending questions found for the selected course.');
                } else {
                    showAlert('info', 'No pending questions found.');
                }
            }
        }).fail(function() {
            showAlert('danger', 'Error loading pending questions.');
        });
    }

    // Load chapters based on selected subject and auto-submit
    function setupChapterLoading() {
        $('#filterSubject').on("change", function () {
            let subjectId = $(this).val();
            let filterChapter = $("#filterChapter");
            
            filterChapter.html('<option value="">Loading...</option>');

            if (subjectId) {
                $.get("{{ url('user/chapters/by-subject') }}/" + subjectId, function (data) {
                    filterChapter.empty();
                    if (data.length > 0) {
                        filterChapter.append('<option value="">-- All Chapters --</option>');
                        $.each(data, function (i, ch) {
                            let selected = ch.id == "{{ request('chapter_id') }}" ? 'selected' : '';
                            filterChapter.append(
                                `<option value="${ch.id}" ${selected}>${ch.title}</option>`
                            );
                        });
                    } else {
                        filterChapter.html('<option value="">-- No Chapters --</option>');
                    }
                    
                    // Auto-submit form after chapters are loaded
                    setTimeout(() => {
                        $('#filterForm').submit();
                    }, 500);
                });
            } else {
                filterChapter.html('<option value="">-- Filter by Chapter --</option>');
                // Auto-submit when subject is cleared
                setTimeout(() => {
                    $('#filterForm').submit();
                }, 300);
            }
        });
    }

    // Initialize chapter loading
    setupChapterLoading();

    // Load pending questions when course is selected
    $('#loadPendingBtn').click(function() {
        let courseId = $('#verificationCourse').val();
        if (!courseId) {
            showAlert('warning', 'Please select a course first.');
            return;
        }
        loadPendingQuestions(courseId);
    });

    // Reset filter
    $('#resetFilterBtn').click(function() {
        $('#verificationCourse').val('');
        pendingQuestions = [];
        currentCourseId = null;
        currentCourseName = '';
        $('#pendingCount').text('0');
        $('#verifyQuestionsBtn').prop('disabled', true).html('<i class="fas fa-check-circle"></i> Verify Questions (0)');
        showAlert('info', 'Verification filter reset.');
    });

    // Start verification process
    $('#verifyQuestionsBtn').click(function() {
        if (pendingQuestions.length === 0) {
            showAlert('warning', 'No questions pending verification for the selected course.');
            return;
        }
        
        currentQuestionIndex = 0;
        loadQuestionForVerification();
        $('#currentCourseBadge').text(currentCourseName);
        $('#verificationCourseId').val(currentCourseId);
        $('#verificationModal').modal('show');
    });

    // Verify single question
    $('.verify-single').click(function() {
        let questionId = $(this).data('id');
        pendingQuestions = [{id: questionId}];
        currentQuestionIndex = 0;
        currentCourseId = null;
        currentCourseName = 'Single Question';
        loadQuestionForVerification();
        $('#currentCourseBadge').text(currentCourseName);
        $('#verificationCourseId').val('');
        $('#verificationModal').modal('show');
    });

    // Quick view question
    $('.view-question').click(function() {
        let questionId = $(this).data('id');
        
        $.get("{{ url('/user/questions-bank/questions') }}/" + questionId + "/preview", function(response) {
            $('#quickViewContent').html(response.html);
            $('#quickViewModal').modal('show');
        }).fail(function() {
            $('#quickViewContent').html('<div class="alert alert-danger">Error loading question preview.</div>');
            $('#quickViewModal').modal('show');
        });
    });

    // Load question for verification
    function loadQuestionForVerification() {
        if (currentQuestionIndex >= pendingQuestions.length) {
            $('#verificationModal').modal('hide');
            showAlert('success', 'All questions have been processed!');
            loadPendingQuestions({{request('subject_id')}}); // Refresh counts
            return;
        }

        let questionId = pendingQuestions[currentQuestionIndex].id;
        
        $.get("{{ url('/user/questions-bank/questions') }}/" + questionId + "/preview", function(response) {
            $('#verificationQuestionId').val(questionId);
            $('#questionPreview').html(response.html);
            $('#currentQuestionInfo').text('Question ' + (currentQuestionIndex + 1) + ' of ' + pendingQuestions.length);
            $('#verificationProgress').text(Math.round(((currentQuestionIndex + 1) / pendingQuestions.length) * 100) + '% Complete');
            $('#verificationRemarks').val('');
        }).fail(function() {
            $('#questionPreview').html(
                '<div class="alert alert-danger">' +
                '<i class="fas fa-exclamation-triangle"></i> Error loading question. Please try again.' +
                '</div>'
            );
        });
    }

    // Verification actions
    $('#approveQuestionBtn').click(function() {
        submitVerification('approved');
    });

    $('#rejectQuestionBtn').click(function() {
        submitVerification('rejected');
    });

    $('#skipQuestionBtn').click(function() {
        currentQuestionIndex++;
        loadQuestionForVerification();
    });

    // Submit verification
    function submitVerification(status) {
        let formData = {
            _token: "{{ csrf_token() }}",
            question_id: $('#verificationQuestionId').val(),
            chapter_id: $('#currentCid').val(),
            status: status,
            remarks: $('#verificationRemarks').val()
        };

        // Disable buttons during submission
        $('#approveQuestionBtn, #rejectQuestionBtn, #skipQuestionBtn').prop('disabled', true);

        $.post("{{ route('admin.exams.questions.verify') }}", formData, function(response) {
            if (response.success) {
                showAlert('success', 'Question ' + status + ' successfully!');
                currentQuestionIndex++;
                loadQuestionForVerification();
                
                // Update the table row if visible
                let $badge = $(`tr:has(button[data-id="${formData.question_id}"])`).find('.verification-badge');
                if ($badge.length) {
                    $badge.removeClass('badge-warning badge-success badge-danger')
                          .addClass(status === 'approved' ? 'badge-success' : 'badge-danger')
                          .html('<i class="fas fa-' + (status === 'approved' ? 'check' : 'times') + '"></i> ' + 
                                status.charAt(0).toUpperCase() + status.slice(1));
                }
            } else {
                showAlert('danger', 'Error: ' + response.message);
            }
        }).fail(function() {
            showAlert('danger', 'Error submitting verification. Please try again.');
        }).always(function() {
            $('#approveQuestionBtn, #rejectQuestionBtn, #skipQuestionBtn').prop('disabled', false);
        });
    }

    // Utility function to show alerts
    function showAlert(type, message) {
        let alertClass = 'alert-' + type;
        let icon = type === 'success' ? 'check-circle' : 
                  type === 'warning' ? 'exclamation-triangle' : 
                  type === 'info' ? 'info-circle' : 'exclamation-triangle';
        
        let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon}"></i> ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `;
        
        $('.page-content').prepend(alertHtml);
        
        // Auto remove alert after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Chapter update functionality
    $('.selectChapter').on('change', function() {
        let questionId = $(this).data('id');
        let chapterId = $(this).val();

        $.post("{{ route('admin.exams.questions.updateChapter') }}", {
            _token: "{{ csrf_token() }}",
            question_id: questionId,
            chapter_id: chapterId
        }).done(function() {
            showAlert('success', 'Chapter updated successfully!');
        }).fail(function() {
            showAlert('danger', 'Error updating chapter.');
        });
    });

    // Jump to question functionality
    $(document).on("click","#jump",function(){
        let qid = $("#qid").val();
        if(qid.trim() == ""){
            showAlert('warning', 'Please enter a Question ID');
        } else {
            window.location.href = "/user/questions-bank/" + qid + "/edit";
        }
    });
    
    
    $(document).on("click", "#search", function () {
    let key = $("#key").val().trim();

    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    if (key) {
        params.set("key", key); // add or update
    } else {
        params.delete("key"); // remove if empty
    }

    url.search = params.toString();
    window.location.href = url.toString();
});


    // Generate questions form
    $("#generateQuestionForm").on("submit", function(e){
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.exams.questions.generate') }}",
            method: "POST",
            data: formData,
            beforeSend: function(){
                $('#generateQuestionModal').modal('hide');
            },
            success: function(res){
                showAlert('success', res.message || "Questions are being generated in background. You can continue working.");
            },
            error: function(xhr){
                showAlert('danger', "Failed to start question generation. Please try again.");
            }
        });
    });

    // Load chapters if subject is preselected on page load
    @if(request('subject_id'))
        $.get("{{ url('user/chapters/by-subject') }}/" + "{{ request('subject_id') }}", function (data) {
            $('#filterChapter').empty();
            if (data.length > 0) {
                $('#filterChapter').append('<option value="">-- All Chapters --</option>');
                $.each(data, function (i, ch) {
                    let selected = ch.id == "{{ request('chapter_id') }}" ? 'selected' : '';
                    $('#filterChapter').append(
                        `<option value="${ch.id}" ${selected}>${ch.title}</option>`
                    );
                });
            }
        });
    @endif

    // Auto-load pending questions count on page load
    loadPendingQuestions();

    // Prevent form submission on Enter key in filter form (except search field)
    $('#filterForm').on('keypress', function(e) {
        if (e.which === 13 && !$(e.target).is('input[name="search"]')) {
            e.preventDefault();
        }
    });

    // Add loading indicator during form submission
    $('#filterForm').on('submit', function() {
        // Show loading indicator
        $('body').addClass('wait');
    });
});
</script>

@endpush