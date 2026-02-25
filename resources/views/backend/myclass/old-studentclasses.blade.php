@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Classes'.' | '.app_name())

@section('content')

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Classes for {{$course->title}}</h3>
          
        </div>
        <div class="card-body">
          <div class="row"> 
         
@foreach($batchlist as $b)
<div class="col-md-6 col-lg-6 col-xl-4">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="xp-widget-box">
                                    <div class="float-left">
                                        <h4 class="xp-counter text-primary">{{$b->name}}</h4>
                                                                                <p class="mb-0 font-16 text-muted">By: <strong>{{$b['teacher']->first_name}} {{$b['teacher']->last_name}}</strong></p>  
                                                              
                                    </div>
                                  
                                    <div class="clearfix"></div>
                                    <hr>
                                                                        <p>Class Timing: <strong><?php echo date("h:iA",strtotime(date("Y-m-d ").$b->start_time)); ?> - <?php echo date("h:iA",strtotime(date("Y-m-d ").$b->end_time)); ?></strong> </p>

                                                                        <?php 
                                                                        $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                                                                        $bdays=json_decode($b->occur,true);

                                                                        foreach($bdays as $d){
                                                                          echo $days[$d].", ";

                                                                        }

                                                                        ?>
                                                                        <?php if($b["can_join"]){ ?>
                                      <p class="mb-0 text-primary" style="    text-align: center;
    padding-top: 15px;">
    <a href="{{route('myclass.slaunch',['id'=>$b['id'],'meetid'=>$b['api_id']])}}" target="_blank" class="btn btn-primary btn-sm cnotstart" data-class="<?=$b['api_id']?>">Goto  class</a>
    <?php }else{
      ?>
      <p class="mb-0 text-primary" style="    text-align: center;
    padding-top: 15px;">
    <a href="javascript:void(0)" class="btn btn-danger btn-sm cnotstart" data-class="" >Class has not started</a>
      <?php
    } ?>


<a href="{{route('myclass.sdownloads',['id'=>$b['id']])}}" class="btn btn-success btn-sm">Course Material</a>

<a href="{{route('myclass.pastclass',['id'=>$b['id']])}}" class="btn btn-warning btn-sm">Past Classes</a>
</p>


                                   
                                </div>
                            </div>
                        </div>
                    </div>

@endforeach

          </div>
           
        </div>
    </div>
@stop

@push('after-scripts')
    <script>
      $(".cnotstart").on("click",function(){
        var classid=$(this).data("class");


        }else{
          alert("Class has not started")
        }


      });
$('#myTable').DataTable();

    </script>

@endpush