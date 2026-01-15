<?php
use App\Models\TeacherProfile;

?>

@extends('backend.layouts.app')

@section('title', __('strings.backend.dashboard.title').' | '.app_name())

@push('after-styles')
    <style>
        .trend-badge-2 {
            top: -10px;
            left: -52px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            padding: 40px 40px 12px;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background-color: #ff5a00;
        }

        .progress {
            background-color: #b6b9bb;
            height: 2em;
            font-weight: bold;
            font-size: 0.8rem;
            text-align: center;
        }

        .best-course-pic {
            background-color: #333333;
            background-position: center;
            background-size: cover;
            height: 150px;
            width: 100%;
            background-repeat: no-repeat;
        }


    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}! </strong>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->hasRole('student'))

<!--
                            @if(count($pending_orders) > 0)
                                <div class="col-12">
                                    <h4>@lang('labels.backend.dashboard.pending_orders')</h4>
                                </div>
                                <div class="col-12 text-center">

                                    <table class="table table table-bordered table-striped">
                                        <thead>
                                        <tr>

                                            <th>@lang('labels.general.sr_no')</th>
                                            <th>@lang('labels.backend.orders.fields.reference_no')</th>
                                            <th>@lang('labels.backend.orders.fields.items')</th>
                                            <th>@lang('labels.backend.orders.fields.amount')
                                                <small>(in {{$appCurrency['symbol']}})</small>
                                            </th>
                                            <th>@lang('labels.backend.orders.fields.payment_status.title')</th>
                                            <th>@lang('labels.backend.orders.fields.date')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pending_orders as $key=>$item)
                                            @php $key++ @endphp
                                            <tr>
                                                <td>
                                                    {{$key}}
                                                </td>
                                                <td>
                                                    {{$item->reference_no}}
                                                </td>
                                                <td>
                                                    @foreach($item->items as $key=>$subitem)
                                                        @php $key++ @endphp
                                                        @if($subitem->item != null)
                                                            {{$key.'. '.$subitem->item->title}} <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{$item->amount}}
                                                </td>
                                                <td>
                                                    @if($item->status == 0)
                                                        @lang('labels.backend.dashboard.pending')
                                                    @elseif($item->status == 1)
                                                        @lang('labels.backend.dashboard.success')
                                                    @elseif($item->status == 2)
                                                        @lang('labels.backend.dashboard.failed')
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$item->created_at->format('d-m-Y h:i:s')}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif

                        -->

<div class="col-12">
                                <h4>My Courses</h4>
                            </div>
                         <div class="col-12 text-center">

                                    <table class="table table table-bordered table-striped">
                                        <thead>
                                        <tr>

                                            <th>@lang('labels.general.sr_no')</th>
                                            <th>Course Name</th>
                                            <th>Course category</th>

                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php $count=0 @endphp
                                        @foreach($purchased_courses as $item)
                                        @php $count++ @endphp

                                            <tr>
                                               <td>{{$count}}</td>

                                                <td><a href="/course/study/{{$item->slug}}/" target="_blank">{{$item->title}}</a></td>
                                                 <td><a href="javascript:void(0)"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a></td>
                                                  <td>
                                                      <a href="{{ route('classes.show', [$item->slug]) }}" class="btn btn-primary">Classes</a>
                                                  </td>
                                            </tr>
                                        @endforeach


                                         @foreach($purchased_bundles as $key=>$bundle)


                                    @if(count($bundle->courses) > 0)
                                        @foreach($bundle->courses as $item)
                                        @php $count++ @endphp
                                          <tr>
                                               <td>{{$count}}</td>
                                                <td>{{$item->title}}</td>
                                                 <td><a href="javascript:void(0)"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a></td>
                                                  <td>
                                                      <a href="{{ route('classes.show', [$item->slug]) }}" class="btn btn-primary">Classes</a>
                                                  </td>
                                            </tr>



                                        @endforeach
                                        @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

<!--
                            <div class="col-12">
                                <h4>@lang('labels.backend.dashboard.my_courses')</h4>
                            </div>


                            @if(count($purchased_courses) > 0)
                                @foreach($purchased_courses as $item)

                                    <div class="col-md-3">
                                        <div class="best-course-pic-text position-relative border">
                                            <div class="best-course-pic position-relative overflow-hidden"
                                                 @if($item->course_image != "") style="background-image: url({{asset('storage/uploads/'.$item->course_image)}})" @endif>

                                                @if($item->trending == 1)
                                                    <div class="trend-badge-2 text-center text-uppercase">
                                                        <i class="fas fa-bolt"></i>
                                                        <span>@lang('labels.backend.dashboard.trending') </span>
                                                    </div>
                                                @endif

                                                <div class="course-rate ul-li">
                                                    <ul>
                                                        @for($i=1; $i<=(int)$item->rating; $i++)
                                                            <li><i class="fas fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="best-course-text d-inline-block w-100 p-2">
                                                <div class="course-title mb20 headline relative-position">
                                                    <h5>
                                                        <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                                    </h5>
                                                </div>
                                                <div class="course-meta d-inline-block w-100 ">
                                                    <div class="d-inline-block w-100 0 mt-2">
                                                     <span class="course-category float-left">
                                                <a href="{{route('courses.category',['category'=>$item->category->slug])}}"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a>
                                            </span>
                                                        <span class="course-author float-right">
                                                 {{ $item->students()->count() }}
                                                            @lang('labels.backend.dashboard.students')
                                            </span>
                                                    </div>

                                                    <div class="progress my-2">
                                                        <div class="progress-bar"
                                                             style="width:{{$item->progress() }}%">
                                                            @lang('labels.backend.dashboard.completed')
                                                            {{ $item->progress()  }} %
                                                        </div>
                                                    </div>
                                                    @if($item->progress() == 100)
                                                        @if(!$item->isUserCertified())
                                                            <form method="post"
                                                                  action="{{route('admin.certificates.generate')}}">
                                                                @csrf
                                                                <input type="hidden" value="{{$item->id}}"
                                                                       name="course_id">
                                                                <button class="btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold"
                                                                        id="finish">@lang('labels.frontend.course.finish_course')</button>
                                                            </form>
                                                        @else
                                                            <div class="alert alert-success px-1 text-center mb-0">
                                                                @lang('labels.frontend.course.certified')
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center">
                                    <h4 class="text-center">@lang('labels.backend.dashboard.no_data')</h4>
                                    <a class="btn btn-primary"
                                       href="{{route('courses.all')}}">@lang('labels.backend.dashboard.buy_course_now')
                                        <i class="fa fa-arrow-right"></i></a>
                                </div>
                            @endif
                            @if(count($purchased_bundles) > 0)

                                <div class="col-12 mt-5">
                                    <h4>@lang('labels.backend.dashboard.my_course_bundles')</h4>
                                </div>
                                @foreach($purchased_bundles as $key=>$bundle)
                                    @php $key++ @endphp
                                    <div class="col-12"><h5><a
                                                    href="{{route('bundles.show',['slug'=>$bundle->slug ])}}">
                                                {{$key.'. '.$bundle->title}}</a></h5>
                                    </div>
                                    @if(count($bundle->courses) > 0)
                                        @foreach($bundle->courses as $item)
                                            <div class="col-md-3 mb-5">
                                                <div class="best-course-pic-text position-relative border">
                                                    <div class="best-course-pic position-relative overflow-hidden"
                                                         @if($item->course_image != "") style="background-image: url({{asset('storage/uploads/'.$item->course_image)}})" @endif>

                                                        @if($item->trending == 1)
                                                            <div class="trend-badge-2 text-center text-uppercase">
                                                                <i class="fas fa-bolt"></i>
                                                                <span>@lang('labels.backend.dashboard.trending') </span>
                                                            </div>
                                                        @endif

                                                        <div class="course-rate ul-li">
                                                            <ul>
                                                                @for($i=1; $i<=(int)$item->rating; $i++)
                                                                    <li><i class="fas fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="best-course-text d-inline-block w-100 p-2">
                                                        <div class="course-title mb20 headline relative-position">
                                                            <h5>
                                                                <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                                            </h5>
                                                        </div>
                                                        <div class="course-meta d-inline-block w-100 ">
                                                            <div class="d-inline-block w-100 0 mt-2">
                                                     <span class="course-category float-left">
                                                <a href="{{route('courses.category',['category'=>$item->category->slug])}}"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a>
                                            </span>
                                                                <span class="course-author float-right">
                                                 {{ $item->students()->count() }}
                                                                    @lang('labels.backend.dashboard.students')
                                            </span>
                                                            </div>

                                                            <div class="progress my-2">
                                                                <div class="progress-bar"
                                                                     style="width:{{$item->progress() }}%">{{ $item->progress()  }}
                                                                    %
                                                                    @lang('labels.backend.dashboard.completed')
                                                                </div>
                                                            </div>
                                                            @if($item->progress() == 100)
                                                                @if(!$item->isUserCertified())
                                                                    <form method="post"
                                                                          action="{{route('admin.certificates.generate')}}">
                                                                        @csrf
                                                                        <input type="hidden" value="{{$item->id}}"
                                                                               name="course_id">
                                                                        <button class="btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold"
                                                                                id="finish">@lang('labels.frontend.course.finish_course')</button>
                                                                    </form>
                                                                @else
                                                                    <div class="alert alert-success px-1 text-center mb-0">
                                                                        @lang('labels.frontend.course.certified')
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach

                            -->
                    </div>
                    @endif
                    @elseif(auth()->user()->hasRole('teacher'))
                       

                        @if(auth()->user()->active)
                            <div class="col-12">
                            <div class="row">
                                <div class="col-md-4 col-12 border-right">
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="{{route('admin.teacher-course-list')}}" style="text-decoration: none;">
                                            <div class="card text-white bg-primary text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{count(auth()->user()->courses) + count(auth()->user()->bundles)}}</h2>
                                                    <h5> Subjects Assigned</h5>
                                                </div>
                                            </div></a>
                                        </div>
                                        <div class="col-12">
                                            <a href="{{route('admin.teacher-student-list')}}" style="text-decoration: none;">
                                            <div class="card text-white bg-success text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{$students_count}}</h2>
                                                    <h5>@lang('labels.backend.dashboard.students_enrolled')</h5>
                                                </div>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 col-12 ">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">Subjects you Opted 
                                        </h4>

                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        
                                        <?php 

                                        $tp = TeacherProfile::where('user_id',Auth::user()->id)->first();
                                        $subjects = [];
                                        if($tp){
                                        if($tp->subject_teach){
                                            $subjects = json_decode($tp->subject_teach,true);
                                        }
                                    }
                                        ?>


                                        <tbody>
                                            @if(is_array($subjects))
                                        @if(count($subjects) > 0)
                                            @foreach($subjects as $item)
                                                <tr>
                                                   
                                                    <td>{{$item}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif

                                        @else

<tr>
                                                <td colspan="3">{{$tp->subject_teach}}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                             <!--    <div class="col-md-4 col-12">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_messages') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.messages')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>


                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.message_by')</td>
                                            <td>@lang('labels.backend.dashboard.message')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($threads) > 0)
                                            @foreach($threads as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{asset('/user/messages/?thread='.$item->id)}}">{{$item->title}}</a>
                                                    </td>
                                                    <td>{{$item->lastMessage->body}}</td>
                                                    <td>{{$item->lastMessage->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div> -->
                            </div>
                        </div>
                        @else
                            <div class="col-md-12">
                                
                                <div class="card text-center">
                                    <div class="card-body">
                                       <h1>Thank you for registering at {{env('PROJECT_NAME')}}</h1>
                                       <p>Your Account is under Review</p>

                                       <p> Read our terms and conditions: <a href="/tutor-terms-and-conditions" target="_blank">Click here</a> </p>
                                    </div>
                                </div>
                                   
                            </div>

                        @endif
                        

                    @elseif(auth()->user()->hasRole('administrator'))
                        <div class="col-md-4 col-12">
                            <a href="{{ route('admin.courses.index') }}" style="text-decoration: none;">
                            <div class="card text-white bg-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$courses_count}}</h1>
                                    <h3>Courses</h3>
                                </div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-4 col-12">
                            <a href="{{ route('admin.students.index') }}" style="text-decoration: none;">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$students_count}}</h1>
                                    <h3>@lang('labels.backend.dashboard.students')</h3>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-12">
                            <a href="{{ route('admin.teachers.index') }}" style="text-decoration: none;">
                            <div class="card text-white bg-primary text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$teachers_count}}</h1>
                                    <h3>Tutors</h3>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-12">
                            <a href="{{ route('admin.demo_requests') }}" style="text-decoration: none;">
                            <div class="card text-white bg-info text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$enquiry_count}}</h1>
                                    <h3>Enquiry</h3>
                                </div>
                            </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4 col-12">
                            <a href="/user/report/sales" style="text-decoration: none;">
                            <div class="card text-white bg-warning text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$course_purchased_count->sum('amount')+$course_subs_purchased_count->sum('amount')}}</h1>
                                    <h3>Total Earning</h3>
                                </div>
                            </div>
                            </a>
                        </div>
                     <div class="col-md-4 col-12">
                            <a href="/user/payments-stat" style="text-decoration: none;">
                            <div class="card text-white bg-danger text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$balance}}</h1>
                                    <h3>Tutor Balance</h3>
                                </div>
                            </div>
                            </a>
                        </div> 
                     <div class="col-md-4 col-12">
                            <a href="/user/batches" style="text-decoration: none;">
                            <div class="card text-white bg-danger text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$batch}}</h1>
                                    <h3>Batch</h3>
                                </div>
                            </div>
                            </a>
                        </div> 
                       <!--  <div class="col-md-4 col-12">
                            <a href="#" style="text-decoration: none;">
                            <div class="card text-white bg-secondary text-center py-3">
                                <div class="card-body">
                                    @php
                                    $total_amount = $course_purchased_count->sum('amount');
                                    $amount_paid = $teacher_payment_count->sum('amount');
                                    @endphp
                                    <h1 class="">{{$total_amount - $amount_paid}}</h1>
                                    <h3>Total Due</h3>
                                </div>
                            </div>
                            </a>
                        </div> -->
                        <div class="col-md-4 col-12">
                            <a href="{{ route('admin.orders.index') }}" style="text-decoration: none;">
                            <div class="card text-white text-center py-3" style="background-color: #8823c975;">
                                <div class="card-body">
                                    <h1 class="">{{$course_purchased_count->count()}}</h1>
                                    <h3>Course Purchased</h3>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-12 col-12 border-right">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">@lang('labels.backend.dashboard.recent_orders') <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.orders.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                </h4>

                            </div>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <td>@lang('labels.backend.dashboard.ordered_by')</td>
                                    <td>Phone</td>
                                    <td>Email</td>
                                    <td>Course Mode</td>
                                    <td>@lang('labels.backend.dashboard.amount')</td>
                                    <td>@lang('labels.backend.dashboard.time')</td>
                                    <td>@lang('labels.backend.dashboard.view')</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($recent_orders) > 0)
                                    @foreach($recent_orders as $item)
                                    @if($item->user)
                                        <tr>
                                            <td>
                                                {{$item->user->full_name}}
                                            </td>
                                            <td>{{$item->user->phone ?? 'N/A'}}</td>
                                            <td>{{$item->user->email}}</td>
                                            <td>{{$item->course_mode ? getCourseType($item->course_mode) : 'N/A'}}</td>
                                            <td>{{$item->amount.' '.$appCurrency['symbol']}}</td>
                                            <td>{{$item->created_at->diffforhumans()}}</td>
                                            <td><a class="btn btn-sm btn-primary"
                                                   href="{{route('admin.orders.show', $item->id)}}" target="_blank"><i
                                                            class="fa fa-arrow-right"></i></a></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">@lang('labels.backend.dashboard.no_data')</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!--<div class="col-md-6 col-12">-->
                        <!--    <div class="d-inline-block form-group w-100">-->
                        <!--        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_contact_requests') <a-->
                        <!--                    class="btn btn-primary float-right"-->
                        <!--                    href="{{route('admin.contact-requests.index')}}">@lang('labels.backend.dashboard.view_all')</a>-->
                        <!--        </h4>-->

                        <!--    </div>-->
                        <!--    <table class="table table-responsive-sm table-striped">-->
                        <!--        <thead>-->
                        <!--        <tr>-->
                        <!--            <td>@lang('labels.backend.dashboard.name')</td>-->
                        <!--            <td>@lang('labels.backend.dashboard.email')</td>-->
                        <!--            <td>@lang('labels.backend.dashboard.message')</td>-->
                        <!--            <td>@lang('labels.backend.dashboard.time')</td>-->
                        <!--        </tr>-->
                        <!--        </thead>-->
                        <!--        <tbody>-->
                        <!--        @if(count($recent_contacts) > 0)-->
                        <!--            @foreach($recent_contacts as $item)-->
                        <!--                <tr>-->
                        <!--                    <td>-->
                        <!--                        {{$item->name}}-->
                        <!--                    </td>-->
                        <!--                    <td>{{$item->email}}</td>-->
                        <!--                    <td>{{$item->message}}</td>-->
                        <!--                    <td>{{$item->created_at->diffforhumans()}}</td>-->
                        <!--                </tr>-->
                        <!--            @endforeach-->
                        <!--        @else-->
                        <!--            <tr>-->
                        <!--                <td colspan="4">@lang('labels.backend.dashboard.no_data')</td>-->

                        <!--            </tr>-->
                        <!--        @endif-->
                        <!--        </tbody>-->
                        <!--    </table>-->
                        <!--</div>-->

                    @else
                        <div class="col-12">
                            <h1>@lang('labels.backend.dashboard.title')</h1>
                        </div>
                    @endif
                </div>
            </div><!--card-body-->
        </div><!--card-->
    </div><!--col-->

     @if(auth()->user()->hasRole('teacher'))
        @if(auth()->user()->active == '')
        @php
           $tpl = json_encode($teacher_profile_list->payment_details);
           @endphp
            @if($teacher_profile_list->payment_details == Null)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Bank Deatils</strong>
                </div><!--card-header-->
                <div class="card-body">

                    <form method="post" action="{{route('admin.teacher_bank_details_create')}}">
                        @csrf
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="Bankname">Bank Name <span class="text-danger">*</span></label>
                          <input type="text" class="form-control " value="{{old('bank_name')}}" name="bank_name" id="Bankname">
                        
                        </div>
                        <div class="form-group col-md-6">
                          <label for="Ifscode">IFSC Code <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="{{old('ifsc_code')}}" name="ifsc_code" id="Ifscode">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="Accountnumber">Account Number <span class="text-danger">*</span></label>
                          <input type="number" class="form-control" value="{{old('account_number')}}" name="account_number" id="Accountnumber">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="Accountname">Account Name <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="{{old('account_name')}}" name="account_name" id="Accountname">
                        </div>
                      </div>
                      <div class="form-row" >
                        <div class="form-group col-md-12" style="text-align: end;">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </div>
                      
                    </form>
                </div>
            </div>
        </div>
            @endif
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Document</strong>
                </div><!--card-header-->
                <div class="card-body">
                     <form method="post" action="{{route('admin.teacher_document_approof_create')}}" enctype="multipart/form-data">
                        @csrf
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label>Aadhar Card <span class="text-danger">*</span></label>
                          <input type="file" onchange="loadFile(event)" class="form-control aadharUplod" name="aadhar_card_photo" >
                            <div class="mx-auto mt-3">
                                <img id="output" class="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                          <label>Pan Card <span class="text-danger">*</span></label>
                          <input type="file"  onchange="panUpload(event)" class="form-control" name="pan_card_photo">
                          <div class="mx-auto mt-3">
                                <img id="output_pan">
                                </div>
                        </div>
                        
                        <div class="form-group col-md-6">
                          <label>Photo ID proof <span class="text-danger">*</span></label>
                          <input type="file"  onchange="idUpload(event)" class="form-control" name="photo_id_proof">
                          <div class="mx-auto mt-3">
                                <img id="output_id">
                                </div>
                            </div>
                        </div>
                      
                        <div class="form-group col-md-6" style="">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </div>

                      
                      
                    </form>
                </div>
            </div>
        </div>


         <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Upload Video/PPT</strong>
                </div><!--card-header-->
                <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                    <form method="post" action="{{route('admin.teacher-ppt-video-store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <label for="ppt_title">Title</label>
                            <input type="text" id="ppt_title" name="ppt_title" class="form-control" required>
                        </div>
                        <div class="mt-4">
                            <label for="ppt_file">Files</label>
                            <input type="file" id="ppt_file" accept=".doc, .docx,.ppt, .pptx" required name="ppt_file" class="form-control">
                        </div>
                       
                        <button type="submit"class="btn btn-primary mt-4" style="margin-left: 16px;">Upload</button>

                        </form>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                              <tr>
                                <th>Sl</th>
                                <th>Title</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                $sl = 1;
                                @endphp
                                @foreach($teacher_ppt_video as $tppt)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$tppt->title}}</td>
                                    <td>
                                        <a href="{{asset($tppt->file_path)}}" class="btn btn-primary" target="_blank">View</a>
                                        <a href="delete/{{$tppt->id}}" class="btn  btn-danger">Delete</a>
                                </td>
                                   
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                    </div>
                  
                    
                </div>
                     
                  
                    
                </div>
            </div>
        </div>

        @endif
    @endif
    <script>

var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    // output.style.width = "00px";
    output.style.height = "200px";
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

  var panUpload = function(event) {
    var output = document.getElementById('output_pan');
    output.src = URL.createObjectURL(event.target.files[0]);
    // output.style.width = "100px";
    output.style.height = "200px";
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

//   var newPLUpload = function(event) {
//     var output = document.getElementById('output_new');
//     output.src = URL.createObjectURL(event.target.files[0]);
//     // output.style.width = "100px";
//     output.style.height = "200px";
//     output.onload = function() {
//       URL.revokeObjectURL(output.src) // free memory
//     }
//   };

  
    </script>

    <!-- input table -->
     <script>
        // Add event listener to display selected file name
        const fileInput = document.getElementById('imageUpload');
        const fileNameDisplay = document.querySelector('.file-name');

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = `Selected file: ${fileInput.files[0].name}`;
            } else {
                fileNameDisplay.textContent = 'No file selected';
            }
        });

        // Add event listener to add image title and image name to the table
        const addImageButton = document.getElementById('addImage');
        const imageTable = document.getElementById('imageTable');
        const titleInput = document.getElementById('titleInput');

        addImageButton.addEventListener('click', function() {
            const imageTitle = titleInput.value;
            const imageFileName = fileNameDisplay.textContent.split(': ')[1]; // Extract the image file name

            if (imageTitle && imageFileName) {
                const newRow = document.createElement('tr');
                const titleCell = document.createElement('td');
                titleCell.textContent = imageTitle;
                const nameCell = document.createElement('td');
                nameCell.textContent = imageFileName; // Display the image file name
                newRow.appendChild(titleCell);
                newRow.appendChild(nameCell);
                imageTable.appendChild(newRow);

                // Clear the input fields
                titleInput.value = '';
                fileNameDisplay.textContent = 'No file selected';
            }
        });
    </script>
@endsection
