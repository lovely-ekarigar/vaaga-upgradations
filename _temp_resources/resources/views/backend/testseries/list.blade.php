@extends('frontend.layout.sub-master')
@section('title')
<title>Test List | {{ env('APP_NAME') }}</title>
@stop

@section('content')
<style>
    .test-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    
    .test-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    
    .test-content {
        padding: 1.25rem;
    }
    
    .test-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .test-description {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .test-questions {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }
    
    .btn-group-mobile {
        display: flex;
        gap: 0.5rem;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .test-card {
            margin-bottom: 1.25rem;
        }
        
        .test-content {
            padding: 1rem;
        }
        
        .test-title {
            font-size: 1rem;
        }
        
        .action-buttons {
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn-group-mobile {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group-mobile .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .profile-content-area {
            padding: 1rem;
        }
        
        .test-content {
            padding: 0.875rem;
        }
        
        .test-title {
            font-size: 0.95rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group-mobile {
            flex-direction: column;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            @include('includes.partials.messages')

            <h3 class="mb-4">Available Tests</h3>

            @if($testList->count() > 0)
                <div class="test-list">
                    @foreach($testList as $test)
                        <div class="test-card">
                            <div class="test-content">
                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                    <div class="flex-grow-1 mb-3 mb-md-0">
                                        <h5 class="test-title">{{ $test->name ?? 'Test Name' }}</h5>
                                        <p class="test-description mb-2">
                                            {{ $test->description ?? 'No description available' }}
                                        </p>
                                        <p class="test-questions mb-0">
                                            <strong>Total Questions:</strong> {{ $test->total_questions ?? $test->total_questions ?? 0 }}
                                        </p>
                                    </div>
                                    <div class="action-buttons">
                                        @if($test->myExam)
                                            <div class="btn-group-mobile">
                                                <a class="btn btn-sm btn-secondary" target="_blank" href="{{ route('myTestSeries.examResult', ['id' => $test->myExam->id]) }}">
                                                    View Result
                                                </a>
                                                <a href="{{ route('myTestSeries.myAttempt', ['id' => $test->id, 'tsid' => $tp->id]) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-play-circle"></i> Retake
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('myTestSeries.myAttempt', ['id' => $test->id, 'tsid' => $tp->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-play-circle"></i> Start Test
                                                
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> No active tests found for this series.
                </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
@stop

@section('page_js')
<script type="text/javascript">
    // Add JS if needed
</script>

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
@stop