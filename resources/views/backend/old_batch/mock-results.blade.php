@extends('backend.layouts.app')

@section('title', 'Mock Results - ' . $batch->name . ' | ' . app_name())

@push('after-styles')
<style>
    .result-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .result-stat {
        text-align: center;
        padding: 1.5rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        margin-bottom: 1rem;
    }
    
    .result-stat h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .result-stat p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .stat-correct {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
    }
    
    .stat-correct h3 {
        color: #28a745;
    }
    
    .stat-wrong {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 2px solid #dc3545;
    }
    
    .stat-wrong h3 {
        color: #dc3545;
    }
    
    .stat-unattempted {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
    }
    
    .stat-unattempted h3 {
        color: #e67e22;
    }
    
    .percentage-circle {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 2rem auto;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .percentage-circle h2 {
        font-size: 3rem;
        font-weight: 700;
        margin: 0;
    }
    
    .percentage-circle p {
        font-size: 1rem;
        margin: 0;
    }
    
    .select-wrapper {
        margin-bottom: 1.5rem;
    }
    
    .select-wrapper label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    #result-container {
        display: none;
    }
    
    #no-result-message {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="page-title mb-1">Mock Results</h3>
                <p class="text-muted mb-0">Batch: <strong>{{ $batch->name }}</strong></p>
                <p class="text-muted mb-0">Course: <strong>{{ $course->title }}</strong></p>
            </div>
            <a href="{{ route('admin.batch') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Batches
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="select-wrapper">
                    <label for="student-select">Select Student</label>
                    <select id="student-select" class="form-control">
                        <option value="">-- Select Student --</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="select-wrapper">
                    <label for="mock-test-select">Select Mock Test</label>
                    <select id="mock-test-select" class="form-control">
                        <option value="">-- Select Mock Test --</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div id="loading-message" class="text-center mt-4" style="display: none;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Loading result...</p>
        </div>
        
        <div id="no-result-message" class="alert alert-warning mt-4">
            <i class="fa fa-exclamation-triangle"></i> 
            <strong>No result found.</strong> This student hasn't completed this mock test yet.
        </div>
        
        <div id="result-container" class="mt-4">
            <div class="result-card">
                <div class="text-center mb-4">
                    <div class="percentage-circle">
                        <h2 id="result-percentage">0%</h2>
                        <p>Score</p>
                    </div>
                    <h4 class="mt-3"><span id="result-score">0</span> / <span id="result-total">0</span></h4>
                    <p class="text-muted">Total Marks</p>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="result-stat stat-correct">
                            <h3 id="result-correct">0</h3>
                            <p><i class="bi bi-check-circle me-1"></i> Correct Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-stat stat-wrong">
                            <h3 id="result-wrong">0</h3>
                            <p><i class="bi bi-x-circle me-1"></i> Wrong Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-stat stat-unattempted">
                            <h3 id="result-unattempted">0</h3>
                            <p><i class="bi bi-dash-circle me-1"></i> Unattempted</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Exam Date:</strong> <span id="result-exam-date">N/A</span></p>
                            <p class="mb-2"><strong>Duration:</strong> <span id="result-duration">0</span> minutes</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Time Spent:</strong> <span id="result-time-spent">N/A</span></p>
                            <p class="mb-2"><strong>Status:</strong> 
                                <span class="badge bg-success" id="result-status">Completed</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4" id="result-message">
                    <!-- Performance message will be inserted here -->
                </div>

                <!-- <div class="text-center mt-4">
                    <a href="#" id="view-answer-key-btn" class="btn btn-primary btn-lg" target="_blank" style="display: none;">
                        <i class="bi bi-clipboard-check me-2"></i> View Answer Key
                    </a>
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
$(document).ready(function() {
    const batchId = {{ $batch->id }};
    let selectedStudentId = null;
    let selectedMockId = null;
    let currentExamId = null;
    
    // Load students list
    $.ajax({
        url: '/user/batch/' + batchId + '/students-list',
        type: 'GET',
        success: function(response) {
            if (response.success && response.students.length > 0) {
                response.students.forEach(function(student) {
                    $('#student-select').append(
                        $('<option></option>')
                            .attr('value', student.id)
                            .text(student.name + ' (' + student.email + ')')
                    );
                });
            } else {
                $('#student-select').append(
                    $('<option></option>').text('No students found in this batch')
                );
            }
        },
        error: function() {
            alert('Error loading students list');
        }
    });
    
    // Load mock tests list
    $.ajax({
        url: '/user/batch/' + batchId + '/mock-tests-list',
        type: 'GET',
        success: function(response) {
            if (response.success && response.mockTests.length > 0) {
                response.mockTests.forEach(function(mockTest) {
                    $('#mock-test-select').append(
                        $('<option></option>')
                            .attr('value', mockTest.mock_list_id)
                            .text(mockTest.name + ' (' + mockTest.series_name + ')')
                    );
                });
            } else {
                $('#mock-test-select').append(
                    $('<option></option>').text('No mock tests assigned to this batch')
                );
            }
        },
        error: function() {
            alert('Error loading mock tests list');
        }
    });
    
    // When both student and mock test are selected, fetch result
    function fetchResult() {
        if (selectedStudentId && selectedMockId) {
            $('#loading-message').show();
            $('#result-container').hide();
            $('#no-result-message').hide();
            
            $.ajax({
                url: '/user/batch/student-mock-result/' + selectedStudentId + '/' + selectedMockId,
                type: 'GET',
                success: function(response) {
                    $('#loading-message').hide();
                    
                    if (response.success) {
                        const result = response.result;
                        currentExamId = result.exam_id;
                        
                        // Populate result data
                        $('#result-percentage').text(result.percentage + '%');
                        $('#result-score').text(result.score);
                        $('#result-total').text(result.total_questions);
                        $('#result-correct').text(result.correct_answers);
                        $('#result-wrong').text(result.wrong_answers);
                        $('#result-unattempted').text(result.unattempted);
                        $('#result-exam-date').text(result.exam_date);
                        $('#result-duration').text(result.duration);
                        $('#result-time-spent').text(result.time_spent);
                        $('#result-status').text(result.status);
                        
                        // Show performance message
                        const percentage = parseFloat(result.percentage);
                        let messageHtml = '';
                        if (percentage >= 75) {
                            messageHtml = '<div class="alert alert-success"><i class="bi bi-trophy me-2"></i> Excellent performance! Keep up the great work! 🎉</div>';
                        } else if (percentage >= 50) {
                            messageHtml = '<div class="alert alert-info"><i class="bi bi-hand-thumbs-up me-2"></i> Good job! There\'s room for improvement. 💪</div>';
                        } else {
                            messageHtml = '<div class="alert alert-warning"><i class="bi bi-lightbulb me-2"></i> Keep practicing! You\'ll do better next time. 📚</div>';
                        }
                        $('#result-message').html(messageHtml);
                        
                        // Show answer key button
                        $('#view-answer-key-btn').attr('href', '/user/mock-exam-answer-key/' + currentExamId).show();
                        
                        $('#result-container').show();
                        $('#no-result-message').hide();
                    } else {
                        $('#no-result-message').show();
                        $('#result-container').hide();
                    }
                },
                error: function() {
                    $('#loading-message').hide();
                    alert('Error fetching result');
                }
            });
        }
    }
    
    // Student selection change
    $('#student-select').on('change', function() {
        selectedStudentId = $(this).val();
        fetchResult();
    });
    
    // Mock test selection change
    $('#mock-test-select').on('change', function() {
        selectedMockId = $(this).val();
        fetchResult();
    });
});
</script>
@endpush
