@extends('frontend.layout.sub-master')
@section('title')
<title>Test Series | {{ env('APP_NAME') }}</title>
@stop

@section('content')
<style>
    .test-series-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    
    .test-series-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    
    .test-series-content {
        padding: 1.25rem;
    }
    
    .course-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .test-series-name {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .test-count {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    
    .validity-info {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .test-series-card {
            margin-bottom: 1.25rem;
        }
        
        .test-series-content {
            padding: 1rem;
        }
        
        .course-title {
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
        
        .status-badge {
            align-self: flex-start;
        }
    }
    
    @media (max-width: 576px) {
        .profile-content-area {
            padding: 1rem;
        }
        
        .test-series-content {
            padding: 0.875rem;
        }
        
        .course-title {
            font-size: 0.95rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-sm {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            @include('includes.partials.messages')

            <h3 class="mb-4">My Test Series</h3>
            

            @if($testSeries->count() > 0)
                <div class="test-series-list">
                    @foreach($testSeries as $ts)
                        <div class="test-series-card">
                            <div class="test-series-content">
                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                    <div class="flex-grow-1 mb-3 mb-md-0">
                                        <h5 class="course-title">{{ $ts->course->title ?? 'Course Name' }}</h5>
                                        <p class="test-series-name mb-2">
                                            {{ $ts->testSeries->name ?? 'N/A' }}
                                        </p>
                                        <p class="test-count mb-2">
                                            <strong>Number of Tests:</strong> {{ $ts->testSeries->total_test ?? 0 }}
                                        </p>
                                        @php
                                            $number = preg_replace('/\D/', '', $ts->testSeries->validity);
                                            $validTill = \Carbon\Carbon::parse($ts->created_at->addMonths($number));
                                            $isActive = $validTill->isFuture();
                                            $status = $isActive ? 'Active' : 'Expired';
                                            $badgeClass = $isActive ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <p class="validity-info mb-0">Valid Till: {{ $validTill->format('d M Y') }}</p>
                                    </div>
                                    <div class="action-buttons">
                                        @if($isActive)
                                            <a href="{{ route('myTestSeries.list', ['id' => $ts->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i> View Tests
                                            </a>
                                        @endif
                                        <span class="badge {{ $badgeClass }} status-badge">{{ $status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> You have no active test series.
                </div>
            @endif
        </div>
    </div>
</div>
</div></div>
@stop

@section('page_js')
<script type="text/javascript">
    // Your existing JavaScript code here
</script>

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
@stop