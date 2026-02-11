<?php
use App\Models\StudentCommitment;
?>

@extends('frontend.layout.sub-master')
@section('title')
<title>{{$course->title}} | {{env('APP_NAME')}}</title>
@stop
@section('content')
<style>
    /* Mobile Responsive Styles */
    .batch-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .batch-card {
        display: none;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .batch-info {
        margin-bottom: 1rem;
    }
    
    .batch-info-item {
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .batch-info-item:last-child {
        border-bottom: none;
    }
    
    .batch-days {
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .action-buttons .btn {
        flex: 1;
        min-width: 140px;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    
    .status-message {
        padding: 0.75rem;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    
    .status-message.text-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
    }
    
    @media (max-width: 768px) {
        .table {
            display: none;
        }
        
        .batch-card {
            display: block;
        }
        
        .profile-content-area {
            padding: 1rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            min-width: auto;
        }
        
        h3.mb-2 {
            font-size: 1.5rem;
        }
        
        h6.text-body.fw-500.mb-3 {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 576px) {
        .batch-card {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .btn-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
    
    @media (min-width: 769px) {
        .batch-table {
            display: block;
        }
        
        .batch-card {
            display: none;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
            <h6 class="text-body fw-500 mb-3">Classes for {{$course->title}}</h6>
            
            <!-- Desktop Table View -->
            <div class="batch-table d-none d-md-block">
                <table class="table table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Batch Name</th>
                            <th>Tutor Name</th>
                            <th>Class Timing</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batchlist as $b)
                            @php
                                $completed = false;
                                $liveComplted = false;
                                $st = StudentCommitment::where("batch_id", $b->id)->where("student_id", Auth::user()->id)->first();
                                
                                if($st && $st->completion_date) {
                                    if(date("Y-m-d") >= date("Y-m-d", strtotime("+0 months", strtotime($st->completion_date)))) {
                                        $liveComplted = true;
                                    }
                                    if(date("Y-m-d") >= date("Y-m-d", strtotime("+3 months", strtotime($st->completion_date)))) {
                                        $completed = true;
                                    }
                                }
                                
                                $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                                $bdays = json_decode($b->occur, true);
                                $classDays = '';
                                if($bdays) {
                                    foreach($bdays as $d) {
                                        $classDays .= $days[$d] . ", ";
                                    }
                                    $classDays = rtrim($classDays, ', ');
                                }
                            @endphp
                            <tr>
                                <td><a href="" target="_blank">{{$b->name}}</a></td>
                                <td><a href="javascript:void(0)">{{$b['teacher']->first_name}} {{$b['teacher']->last_name}}</a></td>
                                <td>
                                    <div class="mb-2">
                                        <strong>Time:</strong> <?php echo date("h:iA", strtotime(date("Y-m-d ").$b->start_time)); ?> - <?php echo date("h:iA", strtotime(date("Y-m-d ").$b->end_time)); ?>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Days:</strong> {{ $classDays }}
                                    </div>
                                    <div class="mt-3">
                                        @if(!$expired)
                                            @if(!$liveComplted)
                                                <a href="{{route('myclass.waiting',['id'=>$b['parent_api_class_id']])}}" target="_blank" class="btn btn-primary btn-sm mb-2">Goto class</a>
                                            @endif
                                        @endif
                                        
                                        @if(!$expired)
                                            @if($completed)
                                                <div class="status-message text-danger">
                                                    Your classes are now complete. If you need help, please get in touch with support.
                                                </div>
                                            @else
                                                <div class="action-buttons">
                                                    <a href="{{route('myclass.sdownloads',['id'=>$b['id']])}}" class="btn btn-success btn-sm">Course Material</a>
                                                    <a class="btn btn-info btn-sm" href="{{route('student-test',['id'=>$b['id'],'course_id'=>$course->id])}}">My Test</a>
                                                    <a class="btn btn-warning btn-sm" href="{{route('lession-progress',['id'=>$b['id']])}}">Progress</a>
                                                    <a class="btn btn-primary btn-sm" href="/user/assignments/{{$b->id}}">Assignments</a>
                                                    <a class="btn btn-success btn-sm" href="/user/sexams/{{$b->id}}">Subjective Exams</a>
                                                    <a class="btn btn-danger btn-sm" href="/user/pastClass/{{$b->id}}">Class History</a>
                                                    <a class="btn btn-info btn-sm" href="/user/commitment/{{$b->id}}">Course Progress</a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="status-message text-danger">
                                                Your Subscription has been expired. Kindly renew your subscription from dashboard for uninterrupted classes.
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="batch-cards d-md-none">
                @foreach($batchlist as $b)
                    @php
                        $completed = false;
                        $liveComplted = false;
                        $st = StudentCommitment::where("batch_id", $b->id)->where("student_id", Auth::user()->id)->first();
                        
                        if($st && $st->completion_date) {
                            if(date("Y-m-d") >= date("Y-m-d", strtotime("+0 months", strtotime($st->completion_date)))) {
                                $liveComplted = true;
                            }
                            if(date("Y-m-d") >= date("Y-m-d", strtotime("+3 months", strtotime($st->completion_date)))) {
                                $completed = true;
                            }
                        }
                        
                        $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        $bdays = json_decode($b->occur, true);
                        $classDays = '';
                        if($bdays) {
                            foreach($bdays as $d) {
                                $classDays .= $days[$d] . ", ";
                            }
                            $classDays = rtrim($classDays, ', ');
                        }
                    @endphp
                    <div class="batch-card">
                        <div class="batch-info">
                            <div class="batch-info-item">
                                <strong>Batch Name:</strong> {{$b->name}}
                            </div>
                            <div class="batch-info-item">
                                <strong>Tutor:</strong> {{$b['teacher']->first_name}} {{$b['teacher']->last_name}}
                            </div>
                            <div class="batch-info-item">
                                <strong>Class Time:</strong> 
                                <?php echo date("h:iA", strtotime(date("Y-m-d ").$b->start_time)); ?> - 
                                <?php echo date("h:iA", strtotime(date("Y-m-d ").$b->end_time)); ?>
                            </div>
                            <div class="batch-info-item">
                                <strong>Class Days:</strong>
                                <div class="batch-days">
                                    {{ $classDays }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="batch-actions">
                            @if(!$expired)
                                @if(!$liveComplted)
                                    <a href="{{route('myclass.waiting',['id'=>$b['parent_api_class_id']])}}" target="_blank" class="btn btn-primary btn-sm w-100 mb-2">Join Class</a>
                                @endif
                            @endif
                            
                            @if(!$expired)
                                @if($completed)
                                    <div class="status-message text-danger">
                                        Your classes are now complete. If you need help, please get in touch with support.
                                    </div>
                                @else
                                    <div class="action-buttons">
                                        <a href="{{route('myclass.sdownloads',['id'=>$b['id']])}}" class="btn btn-success btn-sm">Course Material</a>
                                        <a class="btn btn-info btn-sm" href="{{route('student-test',['id'=>$b['id'],'course_id'=>$course->id])}}">My Test</a>
                                        <a class="btn btn-warning btn-sm" href="{{route('lession-progress',['id'=>$b['id']])}}">Progress</a>
                                        <a class="btn btn-primary btn-sm" href="/user/assignments/{{$b->id}}">Assignments</a>
                                        <a class="btn btn-success btn-sm" href="/user/sexams/{{$b->id}}">Subjective Exams</a>
                                        <a class="btn btn-danger btn-sm" href="/user/pastClass/{{$b->id}}">Class History</a>
                                        <a class="btn btn-info btn-sm" href="/user/commitment/{{$b->id}}">Course Progress</a>
                                    </div>
                                @endif
                            @else
                                <div class="status-message text-danger">
                                    Your Subscription has been expired. Kindly renew your subscription from dashboard for uninterrupted classes.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
<!-- End Section -->
</main>

@stop