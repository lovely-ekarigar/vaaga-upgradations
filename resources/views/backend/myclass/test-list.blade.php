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
                @if(count($test_list) > 0)
                    @foreach($test_list as $test)
                    @php
                      
                        
                        
                         if($test->mytest){
                        $testDateTime = $test ? Carbon::parse(date("Y-m-d",$test->mytest->will_start)) : null;
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
                            $quizEndTime = $testDateTime->copy()->addHours(20);
                            $showQuizLink = $now->between($testDateTime, $quizEndTime);
                           
                        }

                        
                    @endphp
                    
                    <div class="col-md-12 mb-2">
                        <div class="card test-card">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        @if(Carbon::parse($test->test_date_time)->format('Y-m-d') >= date("Y-m-d"))
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
                                        <span>{{ $test ? Carbon::parse($test->test_date_time)->format('M d') : '' }}</span>
                                    </div>
                                    <div class="test-meta-item">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $test ? Carbon::parse($test->test_date_time)->format('h:i A') : '' }}</span>
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
                                     
                                        @else
                                            <a href="/user/exam/waiting/{{$batch->id}}/{{Auth::user()->id}}/{{$test->test ? $test->test_id : '0'}}/{{$test->id}}" 
                                               id="testUrl" 
                                               class="btn btn-sm btn-primary action-btn">
                                                <i class="bi bi-pencil-square"></i> Start
                                            </a>
                                        @endif
                                    </div>
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
                            <p class="text-muted small">No tests scheduled for this course</p>
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
