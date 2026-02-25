@extends('backend.layouts.app')
 
@section('title', 'Mock Answer Key | ' . app_name())

@push('after-styles')
<style>
    .answer-key-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .question-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: white;
        transition: all 0.3s ease;
    }
    
    .question-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .question-card.correct {
        border-color: #28a745;
        background: #f8fff9;
    }
    
    .question-card.wrong {
        border-color: #dc3545;
        background: #fff8f8;
    }
    
    .question-card.unattempted {
        border-color: #ffc107;
        background: #fffef8;
    }
    
    .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 1rem;
    }
    
    .question-card.correct .question-number {
        background: #28a745;
    }
    
    .question-card.wrong .question-number {
        background: #dc3545;
    }
    
    .question-card.unattempted .question-number {
        background: #ffc107;
    }
    
    .section-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        background: #e9ecef;
        color: #495057;
        font-size: 0.85rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
    
    .option-item {
        padding: 1rem;
        margin: 0.5rem 0;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .option-item.correct-answer {
        border-color: #28a745;
        background: #d4edda;
    }
    
    .option-item.user-wrong-answer {
        border-color: #dc3545;
        background: #f8d7da;
    }
    
    .option-label {
        font-weight: 600;
        margin-right: 0.5rem;
        display: inline-block;
        min-width: 30px;
    }
    
    .answer-status {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 1rem;
    }
    
    .answer-status.correct {
        background: #d4edda;
        color: #28a745;
    }
    
    .answer-status.wrong {
        background: #f8d7da;
        color: #dc3545;
    }
    
    .answer-status.unattempted {
        background: #fff3cd;
        color: #856404;
    }
    
    .answer-status i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    .student-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .student-info-card h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .student-info-card p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
    }
    
    /* Ensure base64 images in questions and options display properly */
    .question-text img,
    .option-item img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px 0;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="page-title mb-1">Mock Exam Answer Key</h3>
                <p class="text-muted mb-0">Review all questions with correct answers</p>
            </div>
            <button onclick="window.history.back()" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="student-info-card">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fa fa-user me-2"></i>Student: {{ $student->name }}</h5>
                    <p>{{ $student->email }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5><i class="fa fa-file-text me-2"></i>Mock Test: {{ $mockTest->name }}</h5>
                    <p>Exam Date: {{ \Carbon\Carbon::parse($exam->exam_date_time)->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="answer-key-card">
            @php
                $questionsBySection = [];
                foreach($answerKeyData as $item) {
                    $questionsBySection[$item['section_name']][] = $item;
                }
            @endphp

            @foreach($questionsBySection as $sectionName => $questions)
                <div class="mb-4">
                    <h4 class="mb-3">
                        <i class="fa fa-folder-open me-2"></i>{{ $sectionName }}
                    </h4>

                    @foreach($questions as $item)
                        @php
                            $statusClass = 'unattempted';
                            if($item['is_attempted']) {
                                $statusClass = $item['is_correct'] ? 'correct' : 'wrong';
                            }
                        @endphp

                        <div class="question-card {{ $statusClass }}">
                            <div class="d-flex align-items-start mb-3">
                                <span class="question-number">{{ $item['question_number'] }}</span>
                                <div class="flex-grow-1">
                                    <div class="question-text">
                                        {!! $item['question_text'] !!}
                                    </div>
                                </div>
                            </div>

                            <div class="options-list mt-3">
                                @foreach($item['options'] as $key => $option)
                                    @php
                                        $optionClass = '';
                                        $showCorrectLabel = false;
                                        $showYourAnswerLabel = false;
                                        
                                        // Check if this is the correct answer
                                        if($key == $item['correct_answer']) {
                                            $optionClass = 'correct-answer';
                                            $showCorrectLabel = true;
                                        }
                                        
                                        // Check if this is the user's wrong answer
                                        if($item['is_attempted'] && !$item['is_correct'] && $key == $item['user_answer']) {
                                            $optionClass = 'user-wrong-answer';
                                            $showYourAnswerLabel = true;
                                        }
                                    @endphp

                                    <div class="option-item {{ $optionClass }}">
                                        <span class="option-label">{{ chr(65 + $key) }}.</span>
                                        <span>{!! $option !!}</span>
                                        
                                        @if($showCorrectLabel)
                                            <span class="badge bg-success ms-2">
                                                <i class="fa fa-check-circle"></i> Correct Answer
                                            </span>
                                        @endif
                                        
                                        @if($showYourAnswerLabel)
                                            <span class="badge bg-danger ms-2">
                                                <i class="fa fa-times-circle"></i> Student's Answer
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                @if($item['is_attempted'])
                                    @if($item['is_correct'])
                                        <div class="answer-status correct">
                                            <i class="fa fa-check-circle"></i>
                                            Student answered correctly!
                                        </div>
                                    @else
                                        <div class="answer-status wrong">
                                            <i class="fa fa-times-circle"></i>
                                            Student's answer was incorrect. Correct answer is option {{ chr(65 + $item['correct_answer']) }}.
                                        </div>
                                    @endif
                                @else
                                    <div class="answer-status unattempted">
                                        <i class="fa fa-minus-circle"></i>
                                        Student didn't attempt this question. Correct answer is option {{ chr(65 + $item['correct_answer']) }}.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button onclick="window.history.back()" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left me-2"></i> Back to Results
            </button>
        </div>
    </div>
</div>
@endsection
