@extends('frontend.layout.sub-master')
@section('title')
<title>Answer Key | {{ env('APP_NAME') }}</title>
@stop

@section('content')
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
    
    .sticky-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
        padding: 1rem 0;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            @include('includes.partials.messages')

            <div class="sticky-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">Mock Exam Answer Key</h3>
                        <p class="text-muted mb-0">Review all questions with correct answers</p>
                    </div>
                    <div>
                        <!-- <button onclick="window.print()" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-printer"></i> Print
                        </button> -->
                        <a href="{{ route('myMockSeries.result', $exam->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Result
                        </a>
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
                            <i class="bi bi-folder2-open me-2"></i>{{ $sectionName }}
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
                                                    <i class="bi bi-check-circle"></i> Correct Answer
                                                </span>
                                            @endif
                                            
                                            @if($showYourAnswerLabel)
                                                <span class="badge bg-danger ms-2">
                                                    <i class="bi bi-x-circle"></i> Your Answer
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3">
                                    @if($item['is_attempted'])
                                        @if($item['is_correct'])
                                            <div class="answer-status correct">
                                                <i class="bi bi-check-circle-fill"></i>
                                                You answered correctly!
                                            </div>
                                        @else
                                            <div class="answer-status wrong">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Your answer was incorrect. Correct answer is option {{ chr(65 + $item['correct_answer']) }}.
                                            </div>
                                        @endif
                                    @else
                                        <div class="answer-status unattempted">
                                            <i class="bi bi-dash-circle-fill"></i>
                                            You didn't attempt this question. Correct answer is option {{ chr(65 + $item['correct_answer']) }}.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('myMockSeries.result', $exam->id) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i> Back to Result
                </a>
            </div>
        </div>
    </div>
</div>
</div></div>
@stop

@section('page_js')
<script type="text/javascript">
    $(document).ready(function() {
        console.log('Answer key page loaded');
        
        // Smooth scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

<style media="print">
    .sticky-header button,
    .sticky-header a,
    .sidebar,
    .navbar,
    footer {
        display: none !important;
    }
    
    .question-card {
        page-break-inside: avoid;
    }
    
    @page {
        margin: 2cm;
    }
</style>
@stop
