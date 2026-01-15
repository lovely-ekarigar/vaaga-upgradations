@extends('frontend.layout.sub-master')
@section('title')
<title>{{$course->title}} | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class=" mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Classes for {{$course->title}} </h6>
                <div>
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
                          <tr>
                           
                            <td><a href="" target="_blank">{{$b->name}}</a></td>
                            <td><a href="javascript:void(0)">{{$b['teacher']->first_name}} {{$b['teacher']->last_name}}</a></td>
                            <td><?php echo date("h:iA",strtotime(date("Y-m-d ").$b->start_time)); ?> - <?php echo date("h:iA",strtotime(date("Y-m-d ").$b->end_time)); ?>
                            <hr>
                            <?php 
                                                                                                    $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                                                                                                    $bdays=json_decode($b->occur,true);

                                                                                                    foreach($bdays as $d){
                                                                                                      echo $days[$d].", ";

                                                                                                    }

                                                                                                    ?>
                                                                                                    <hr>

                                                                                                    @if(!$expired)
                            <?php if($b["can_join"]){ ?>
                            <p><a href="{{route('myclass.slaunch',['id'=>$b['id'],'meetid'=>$b['api_id']])}}" target="_blank" class="btn btn-primary btn-sm mb-2 cnotstart" data-class="<?=$b['api_id']?>">Goto  class</a>
                             <?php }else{
                                  ?>
                                  <p>
                                      <a href="javascript:void(0)" class="btn btn-danger btn-sm cnotstart mb-2" data-class="" >Class has not started</a>
                             <?php
                                } ?>

                                      <a href="{{route('myclass.sdownloads',['id'=>$b['id']])}}" class="btn btn-success btn-sm mb-2">Course Material</a>
                                      <!--<a href="{{route('myclass.pastclass',['id'=>$b['id']])}}">Past Classes</a>-->
                                       <a class="btn btn-info btn-sm mb-2" href="{{route('student-test',['id'=>$b['id'],'course_id'=>$course->id])}}">Available Quiz</a>
                                       <a class="btn btn-warning btn-sm mb-2" href="{{route('lession-progress',['id'=>$b['id']])}}">Progress</a>
                                           <a class="btn btn-primary btn-sm mb-2" href="/user/assignments/{{$b->id}}">Assignments</a>
                                           <a class="btn btn-success btn-sm mb-2" href="/user/sexams/{{$b->id}}">Subjective Exams</a>
                                       <a class="btn btn-danger btn-sm mb-2" href="/user/pastClasses/{{$b->id}}">Class History</a>
                                   
                                  </p>
                                  @else

                                  <p class="text-danger">Your Subscription has been expired.  Kindly renew your subscription from dashboard for uninterrupted classes.</p>

                                  @endif
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
    </div>
  </section>
  <!-- End Section -->
</main>

@stop