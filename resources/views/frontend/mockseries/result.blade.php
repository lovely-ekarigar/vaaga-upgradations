@extends('frontend.layout.sub-master')
@section('title')
<title>Mock Exam Result | {{ env('APP_NAME') }}</title>
@stop

@section('content')
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
</style>

@auth
@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
@else
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
@endauth
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            @include('includes.partials.messages')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Mock Exam Result</h3>
                    <p class="text-muted mb-0">Your performance summary</p>
                </div>
                {{-- <a href="{{ route('myMockSeries.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Mock Tests
                </a> --}}
            </div>

            <div class="result-card">
                <div class="text-center mb-4">
                    <div class="percentage-circle">
                        <h2>{{ number_format($percentage, 1) }}%</h2>
                        <p>Score</p>
                    </div>
                    <h4 class="mt-3">{{ $score }} / {{ $totalQuestions }}</h4>
                    <p class="text-muted">Total Marks</p>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="result-stat stat-correct">
                            <h3>{{ $correctAnswers }}</h3>
                            <p><i class="bi bi-check-circle me-1"></i> Correct Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-stat stat-wrong">
                            <h3>{{ $wrongAnswers }}</h3>
                            <p><i class="bi bi-x-circle me-1"></i> Wrong Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-stat stat-unattempted">
                            <h3>{{ $unattempted }}</h3>
                            <p><i class="bi bi-dash-circle me-1"></i> Unattempted</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Exam Date:</strong> {{ \Carbon\Carbon::parse($exam->exam_date_time)->format('d M Y, h:i A') }}</p>
                            <p class="mb-2"><strong>Duration:</strong> {{ $exam->duration }} minutes</p>
                        </div>
                        <div class="col-md-6">
                            @if($exam->time_spent)
                                <p class="mb-2"><strong>Time Spent:</strong> {{ gmdate('H:i:s', $exam->time_spent) }}</p>
                            @endif
                            <p class="mb-2"><strong>Status:</strong> 
                                <span class="badge bg-success">Completed</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    @if($percentage >= 75)
                        <div class="alert alert-success">
                            <i class="bi bi-trophy me-2"></i> Excellent performance! Keep up the great work! 🎉
                        </div>
                    @elseif($percentage >= 50)
                        <div class="alert alert-info">
                            <i class="bi bi-hand-thumbs-up me-2"></i> Good job! There's room for improvement. 💪
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-lightbulb me-2"></i> Keep practicing! You'll do better next time. 📚
                        </div>
                    @endif
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('myMockSeries.answerKey', $exam->id) }}" class="btn btn-primary btn-lg" target="_blank">
                        <i class="bi bi-clipboard-check me-2"></i> View Answer Key
                    </a>
                </div>
            </div>
        </div>
    </div>
@auth
</div>
</div></div>
@else
        </div>
    </div>
</div>
@endauth
@stop

@section('page_js')
<script type="text/javascript">
    $(document).ready(function() {
        console.log('Result page loaded');
    });
</script>
@stop
