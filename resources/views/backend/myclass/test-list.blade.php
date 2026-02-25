<?php use Carbon\Carbon; ?>
@extends('frontend.layout.sub-master')
@section('title')
<title>Available Tests for {{$course->title}} | {{env('APP_NAME')}}</title> 
@stop
@section('content')
<style type="text/css">
    .test-card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        margin-bottom: 12px;
        border: 1px solid #eee;
    }
    
    .test-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .test-header {
        font-size: 1.1rem;
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .test-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 10px;
        color: #666;
        font-size: 0.9rem;
    }
    
    .test-meta-item {
        display: flex;
        align-items: center;
    }
    
    .test-meta-item .bi {
        margin-right: 4px;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .test-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 6px;
    }
    
    .status-upcoming {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-completed {
        background-color: #e2e3e5;
        color: #383d41;
    }
    
    .action-btn {
        border-radius: 4px;
        font-weight: 500;
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    
    .no-tests {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    span.test-status.status-missed {
    color: red;
    background: #ff000017;
}
    .no-tests .bi {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 10px;
    }
    
    .test-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid #eee;
        margin-top: 8px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .test-status-bar {
        margin: -1rem -1rem -1rem;
        margin-top: 12px;
        padding: 10px 16px;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-bar-completed {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-top: 2px solid #b1dfbb;
    }
    
    .status-bar-missed {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-top: 2px solid #f1b0b7;
    }
    
    .status-bar-active {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border-top: 2px solid #abdde5;
    }
    
    .status-bar-upcoming {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-top: 2px solid #ffe69c;
    }
    
    .status-bar-icon {
        font-size: 1.1rem;
        margin-right: 6px;
    }
    
    .status-bar-date {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-left: 8px;
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-4 card card-body p-3">
        <div class="mb-4">
            <h5 class="mb-2">My Tests</h5>
            @include('includes.partials.messages')
            <h6 class="text-muted mb-3">Available tests for {{$course->title}}</h6>
            
            <div class="row">
                @if(count($test_list) > 0 || (isset($mock_list) && count($mock_list) > 0))

                   
                    <!-- Mock Tests Section -->
                    @if(isset($mock_list) && count($mock_list) > 0)
                    @foreach($mock_list as $mock)
                    @php
                        $isCompleted = $mock->is_submitted ?? false;
                        $showStartLink = in_array($mock->test_status, ['available']);
                        $isUpcoming = $mock->test_status == 'upcoming' && $mock->scheduled_at;
                        $isMissed = $mock->test_status == 'missed';
                        $hasSchedule = !empty($mock->scheduled_at);
                        $showStatus = $isCompleted || $showStartLink || $isMissed || $isUpcoming;
                        
                      
                        $notStarted = !$mock->is_submitted && empty($mock->scheduled_at);
                        $upcoming = !$mock->is_submitted && !empty($mock->scheduled_at) && Carbon::parse($mock->scheduled_at)->gt(Carbon::now());
                    @endphp
                    
                    <div class="col-md-12 mb-2">
                        <div class="card test-card">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                       <span class="test-status 
                                        @if($isCompleted) status-completed
                                        @elseif($isMissed) status-missed
                                        @elseif($notStarted) status-upcoming
                                        @elseif($upcoming) status-upcoming
                                        @elseif($showStartLink) status-active
                                        @endif">
                                        @if($isCompleted) Completed
                                        @elseif($isMissed) Missed
                                     
                                        @elseif($upcoming) Upcoming
                                        @elseif($notStarted) Not Scheduled
                                        @elseif($showStartLink) Active
                                        @endif
                                    </span>
                                        <span class="badge badge-info ml-2" style="font-size: 0.7rem;">Mock Test</span>
                                        <h5 class="test-header mb-1">{{$mock->name}}</h5>
                                    </div>
                                </div>
                                
                                <div class="test-meta">
                                    <div class="test-meta-item">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $mock->duration }} mins</span>
                                    </div>
                                    <div class="test-meta-item">
                                        <i class="bi bi-question-circle"></i>
                                        <span>{{ $mock->total_questions }} Qs</span>
                                    </div>
                                    @if($mock->scheduled_at)
                                    <div class="test-meta-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($mock->scheduled_at)->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    @if($mock->status_message)
                                    <div class="test-meta-item">
                                        <i class="bi bi-info-circle"></i>
                                        <span>{{ $mock->status_message }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="test-footer">
                                    <div>
                                    </div>
                                    <div>
                                        @if($isCompleted && $mock->myExam)
                                            <a href="{{ route('myMockSeries.result', $mock->myExam->id) }}" 
                                               class="btn btn-sm btn-info action-btn">
                                                <i class="bi bi-file-text"></i> View Result
                                            </a>
                                        @elseif($showStartLink)
                                            <a href="{{ route('myMockSeries.waiting', ['mock_id' => $mock->id, 'batch_mock_test_id' => $mock->batch_mock_test_id]) }}" 
                                               class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil-square"></i> Start Test
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($showStatus)
                                <div class="test-status-bar 
                                    @if($isCompleted) status-bar-completed 
                                    @elseif($isMissed) status-bar-missed 
                                    @elseif($showStartLink) status-bar-active 
                                    @else status-bar-upcoming @endif">
                                    <div class="d-flex align-items-center">
                                        @if($isCompleted)
                                            <i class="bi bi-check-circle-fill status-bar-icon"></i>
                                            <span>Test Completed</span>
                                        @elseif($isMissed)
                                            <i class="bi bi-x-circle-fill status-bar-icon"></i>
                                            <span>Test Missed</span>
                                        @elseif($showStartLink)
                                            <i class="bi bi-play-circle-fill status-bar-icon"></i>
                                            <span>Available Now</span>
                                        @else
                                            <i class="bi bi-clock-fill status-bar-icon"></i>
                                            <span>Upcoming Test</span>
                                        @endif
                                    </div>
                                    @if($mock->scheduled_at && !$isCompleted && !$isMissed && !$showStartLink)
                                        <div class="status-bar-date">
                                            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($mock->scheduled_at)->format('M d, Y - h:i A') }}
                                        </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <!-- Regular Tests Section -->
                    @foreach($test_list as $test)
                    @php
                      
                        
                        
                         if($test->mytest){
                        $testDateTime = Carbon::createFromTimestamp($test->mytest->will_start);
                        }else{
                         $testDateTime = $test ? Carbon::parse($test->test_date_time) : null;
                        }
                        
                        
                        
                        
                        
                        $now = Carbon::now();
                        $showQuizLink = false;
                        $isCompleted = false;
                        $resultAvailable = false; // Set this based on your logic
                            if($test->mytest){
                            if($test->mytest->status=='submitted'){
                            $isCompleted = true;
                            }
                            }
                           if ($testDateTime) {
                            $testDate = $testDateTime->format('Y-m-d');
                            $currentDate = $now->format('Y-m-d');
                            
                            // If test date is today (same date), show quiz link for 20 hours
                            if ($testDate == $currentDate) {
                                $quizEndTime = $testDateTime->copy()->addHours(20);
                                $showQuizLink = $now->lte($quizEndTime);
                            }
                            // If test date is in the past (but within 20 hours from test time), still allow
                            elseif ($testDate < $currentDate) {
                                $quizEndTime = $testDateTime->copy()->addHours(20);
                                $showQuizLink = $now->lte($quizEndTime);
                            }
                        }

                        
                    @endphp
                    
                    <div class="col-md-12 mb-2">
                        <div class="card test-card">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        @if($testDateTime && $testDateTime->format('Y-m-d') >= $now->format('Y-m-d'))
                                        <span class="test-status 
                                            @if($isCompleted) status-completed 
                                            @elseif($showQuizLink) status-active 
                                            @else status-upcoming @endif">
                                            @if($isCompleted) Completed 
                                            @elseif($showQuizLink) Active 
                                            @else Upcoming @endif
                                        </span>
                                        
                                        @else
                                        <span class="test-status 
                                            @if($isCompleted) status-completed 
                                            @elseif($showQuizLink) status-active 
                                            @else status-missed @endif">
                                            @if($isCompleted) Completed 
                                            @elseif($showQuizLink) Active 
                                            @else Missed @endif
                                        </span>
                                        @endif
                                        <h5 class="test-header mb-1">{{$test->test ? $test->test->name : ''}} </h5>
                                    </div>
                                </div>
                                
                                <div class="test-meta">
                                    <div class="test-meta-item">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $test->test ? $test->test->duration : '' }} mins</span>
                                    </div>
                                    <div class="test-meta-item">
                                        <i class="bi bi-question-circle"></i>
                                        <span>{{ $test->test ? $test->test->total_questions : '' }} Qs</span>
                                    </div>
                                    <div class="test-meta-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>{{ $testDateTime ? $testDateTime->format('M d') : '' }}</span>
                                    </div>
                                    <div class="test-meta-item">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $testDateTime ? $testDateTime->format('h:i A') : '' }}</span>
                                    </div>
                                </div>
                                
                                <div class="test-footer">
                                    <div>
                                       
                                    </div>
                                    <div>
                                        @if($isCompleted)
                                            <a href="https://exam.vaagaacademy.com/result/{{base64_encode($test->mytest->id)}}" 
                                               class="btn btn-sm btn-info action-btn" target="_blank">
                                                <i class="bi bi-file-text"></i> Result
                                            </a>
                                        @elseif($showQuizLink)
                                            <a href="/user/exam/waiting/{{$batch->id}}/{{Auth::user()->id}}/{{$test->test ? $test->test_id : '0'}}/{{$test->id}}" 
                                               id="testUrl" 
                                               class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil-square"></i> Start
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Enhanced Status Bar -->
                                @php
                                    $isMissedRegular = $testDateTime && $testDateTime->format('Y-m-d') < $now->format('Y-m-d') && !$isCompleted && !$showQuizLink;
                                @endphp
                                <div class="test-status-bar 
                                    @if($isCompleted) status-bar-completed 
                                    @elseif($isMissedRegular) status-bar-missed 
                                    @elseif($showQuizLink) status-bar-active 
                                    @else status-bar-upcoming @endif">
                                    <div class="d-flex align-items-center">
                                        @if($isCompleted)
                                            <i class="bi bi-check-circle-fill status-bar-icon"></i>
                                            <span>Test Completed</span>
                                        @elseif($isMissedRegular)
                                            <i class="bi bi-x-circle-fill status-bar-icon"></i>
                                            <span>Test Missed</span>
                                        @elseif($showQuizLink)
                                            <i class="bi bi-play-circle-fill status-bar-icon"></i>
                                            <span>Available Now</span>
                                        @else
                                            <i class="bi bi-clock-fill status-bar-icon"></i>
                                            <span>Upcoming Test</span>
                                        @endif
                                    </div>
                                    @if($testDateTime && !$isCompleted && !$showQuizLink)
                                        <div class="status-bar-date">
                                            <i class="bi bi-calendar3"></i> {{ $testDateTime->format('M d, Y - h:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                 
                @else
                    <div class="col-md-12">
                        <div class="no-tests">
                            <i class="bi bi-folder-x"></i>
                            <h6>No Tests Available</h6>
                            <p class="text-muted small">No tests or mock tests scheduled for this course</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>

@stop
