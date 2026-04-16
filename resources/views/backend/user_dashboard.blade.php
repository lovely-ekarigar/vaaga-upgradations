
<?php
use App\Models\Course;
?>
@extends('frontend.layout.sub-master')
@section('title')
<title>Student Dashboard| {{env('APP_NAME')}}</title>
@stop
@section('content')
<style>
    .demo_box {
    background: #ffeb3b24;
    padding: 0px 8px;
    border-radius: 8px;
}
button.close {
    background: transparent;
    border: 0px;
}
</style>

          @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
                 @include('includes.partials.messages')

              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>

                 @if(($demo_request ?? collect())->count() > 0)
                     
                <div class="demo_box"> 
                <h6 class="text-body fw-500 mb-3 pt-3 text-primary">My Demo Classes</h6>
                    <table class="table table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Course Name</th>
                                <th>Course Tutor</th>
                                <th>Demo Schedule</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $count=0 @endphp
                        @foreach($demo_request as $item)
                        @php $count++ @endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>
                                    @if($item->course)
                                    {{$item->course->title}}
                                    @endif 
                                    </td>
                                <td>
                                    @if($item->teacher_id)
                                    @php
                                    $teacher_name = App\Models\Auth\User::find($item->teacher_id);
                                    @endphp
                                    {{$teacher_name->name}}
                                    @endif
                                    
                                </td>
                                
                             
                                <td>
                                    @if($item->demo_date_time)
                                    {{date("d M y h:iA",strtotime($item->demo_date_time))}}
                                    @endif
                                    </td>
                                    
                                    
                                    
                                    <!--big blue flow -->
<!--                                <td>-->
<!--                                    @if($item->demo_status=='started')-->
<!--                                    <a class="btn btn-primary btn-sm join-demo" href="{{route('myclass.slaunch',['id'=>$item->id,'meetid'=>$item->api_class_id])}}" data-id="{{$item->id}}">Join</a>-->
<!--                                    <a class="btn btn-outline-info btn-sm" target="_blank" href="/user/demo-feedback/{{$item->id}}">Feedback</a>-->
<!--                                    @else-->
<!--                                    <span class="text-danger">Not Started Yet</span>-->
<!--                                    @endif-->
<!--                                       @if(!empty($item->meet_link))-->
<!--   <a class="btn btn-primary btn-sm joinGoogleMeet"-->
<!--   href="javascript:void(0)"-->
<!--   data-id="{{ $item->id }}"-->
<!--   data-link="{{ $item->meet_link }}">-->
<!--   Join Google Meet-->
<!--</a>-->
<!--@endif-->
<!--                                </td>-->


<!--google meet flow for demo-->

<td>

    @if($item->demo_status === 'started')

        {{--  GOOGLE MEET (NEW FLOW) --}}
        @if(!empty($item->meet_link))
            <a class="btn btn-primary btn-sm joinGoogleMeet"
               href="javascript:void(0)"
               data-id="{{ $item->id }}"
               data-link="{{ $item->meet_link }}">
               Join Google Meet
            </a>
        @else

            {{--  OLD BIGBLUEBUTTON FLOW (ROLLBACK SAFE) --}}
            {{-- 
            <a class="btn btn-primary btn-sm join-demo"
               href="{{ route('myclass.slaunch',['id'=>$item->id,'meetid'=>$item->api_class_id]) }}"
               data-id="{{$item->id}}">
               Join
            </a>
            --}}

            <span class="text-warning">Link not available</span>

        @endif

        {{-- COMMON --}}
        <a class="btn btn-outline-info btn-sm" target="_blank"
           href="/user/demo-feedback/{{$item->id}}">
           Feedback
        </a>

    @else
        <span class="text-danger">Not Started Yet</span>
    @endif

</td>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
               @endif

               <div class="msg_box">

             <?php

           
              ?>
                @foreach(($orders ?? collect()) as $orderd)
@php
    $totalCycle = (int) ($orderd->total_cycle ?? 0);
    $paidCycle = (int) ($orderd->paid_cycle ?? 0);
    $showRenewMessage = !empty($orderd->end_date)
        && date("Y-m-d") >= date("Y-m-d", strtotime("-7 days", strtotime($orderd->end_date)))
        && ($totalCycle !== $paidCycle)
       
@endphp
@if($showRenewMessage)


                            <?php 

                        

                              $expiredMsg1="";
                            $clistitems='';
                            // dd($order->items);
                             foreach($orderd->items as $key=>$item){
                            $cro = new Course();
                                       $clistitems .=$cro->getCouseNameWithCat($item->item_id).", ";

                        }
 $expiredMsg1 .= '<div class="container position-relative z-index-1 bg-dark mb-2" style="border-radius: 6px;"><div class="row align-items-center"><div class="col-lg-9 col-md-9 my-3 text-md-start text-center"><p class="text-white m-0">Your course <strong>'.$clistitems.'</strong> subscription is expiring on <strong>'.date("d M Y",strtotime($orderd->end_date)).'</strong>. Kindly renew your subscription for uninterrupted classes.</p></div><div class="col-lg-3 col-md-3 my-3 text-md-end text-center"><a class="btn btn-danger btn-sm renew" data-order="'.$orderd->id.'" href="javascript:void(0)">Renew Now</a></div></div></div></div>'; 
                            ?>
                       
                        {!!$expiredMsg1!!}
                         @endif
@endforeach

</div>
       
               @if(count($purchased_courses ?? []) > 0)
<h6 class="text-body fw-500 mb-3 pt-4">My Courses</h6>
                <div>
                    <table class="table table-nowrap mb-0">
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
                            <tr data-order="{{$item->order->id}}">
                                <td>{{$count}}</td>
                                <td><a href="/course/study/{{$item->slug}}/" >{{$item->title}}</a></td>
                                <td><a href="javascript:void(0)">
                                    @if($item->category)
                                    {{$item->category->name}}
                                    @endif
                                    </a></td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('classes.show', [$item->slug]) }}">Classes</a>
                                    <a class="btn btn-success btn-sm" href="/course/study/{{$item->slug}}/">Course Content</a>
                                    <!--<a class="btn btn-warning btn-sm" href="/user/my-mock-series">Mock Tests</a>-->
                                </td>
                                
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
               @endif

                
              

              
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop


@section('page_js')

<script type="text/javascript">
    
var token = '{{csrf_token()}}';

$(document).on("click",".renew",function(e){
    e.preventDefault();
    var oid = $(this).data("order");
    var btn = $(this);
    
    if(!oid){
        alert("Order ID not found");
        return;
    }
    
    btn.html("Please wait...");
    btn.attr("disabled","disabled");
    console.log("Renewing order ID:", oid);
    
    $.ajax({
        url:'/user/renew-subscription',
        type:'POST',
        dataType: 'json',
        data:{oid:oid,_token:token},
        success:function(res){
            console.log("Server response:", res);
            if(res && res.success){
                console.log("Redirecting to:", "/pay/"+res.order_id+"?payment_for=SUBSCRIPTION");
                window.location.href="/pay/"+res.order_id+"?payment_for=SUBSCRIPTION";
            }else{
                alert(res.msg || "Failed to renew subscription");
                btn.html("Renew Now");
                btn.removeAttr("disabled");
            }
        },
        error:function(xhr, status, error){
            console.log("AJAX Error - Status:", status);
            console.log("AJAX Error - Error:", error);
            console.log("AJAX Error - Response:", xhr.responseText);
            var errMsg = error;
            try {
                var resp = JSON.parse(xhr.responseText);
                if(resp && resp.msg) errMsg = resp.msg;
            } catch(e) {}
            alert("Something went wrong: " + errMsg + "\nPlease check console for details.");
            btn.html("Renew Now");
            btn.removeAttr("disabled");
        }
    });
});



// Google Meet Join Click
$(document).on('click', '.joinGoogleMeet', function () {

    let demoId = $(this).data('id');
    let link = $(this).data('link');

    localStorage.setItem('active_demo_id', demoId);

    window.open(link, '_blank');
});

// Auto check demo status every 5 sec
setInterval(function(){
    let demoId = localStorage.getItem('active_demo_id');

    if(demoId){
       $.get('/check-demo-status/' + demoId, function(res){
    if(res.status === 'completed'){
        localStorage.removeItem('active_demo_id');
        window.location.href = "/user/demo-feedback/" + demoId;
    }
});
    }
}, 5000);
</script>

@stop