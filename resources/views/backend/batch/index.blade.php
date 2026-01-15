<?php
use App\Models\Course;
use App\Models\Batch;
use App\Models\Earning;
use App\Models\Recording;

?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">Batch list</h3>
      @can('course_create')
      <div class="float-right">
         <a href="{{ route('admin.batch.create') }}"
            class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
      </div>
      @endcan
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable" class="table table-bordered table-striped dt-select ">
            <thead>
               <tr>
                  <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all"/>
                  </th>
                  <th>@lang('labels.general.sr_no')</th>
                  <th>Batch Name</th>
                  <th>Course Name</th>
                  <th>Completion</th>
                  <th>Batch Duration</th>
                  <th>Batch class time</th>
                  <th>Batch Days</th>
                  <th>Expenses</th>
                  <td>Join</td>
                  <th>&nbsp; @lang('strings.backend.general.actions')</th>
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>
               
               @foreach($list as $l)
               @if($l->name)
               <?php $count++; ?>
               <tr data-entry-id="1" role="row" class="odd">
                  <td class="text-center">
                     <input type="checkbox" class="single" name="id[]" value="1">
                  </td>
                  <td><?=$count?></td>
                  <td>{{$l->name}}</td>
                  <td>
                     <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($l->course->id);
                     ?>

                   </td>
                  <td><?php
          $lo = new Batch;
          $ar = $lo->bacthCompletion($l->id);
           ?>

           @if($l->is_completed == '0')

              {{$ar['completed']}}/{{$ar['total']}}
              @if($ar['total']!=0)
               ({{floor($ar['completed']*100/$ar['total'])}}%)
              @endif

           @else

         <span  class="badge badge-success">Completed</span>

           @endif

        </td>
                  <td>{{$l->start_date}} - {{$l->end_date}}</td>
                  <td>{{$l->start_time}} - {{$l->end_time}}</td>
                  <td>
                     <?php
                        $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                        $wdays=json_decode($l->occur,true);
                        foreach($wdays as $wd){
                         echo $days[$wd].", ";
                        
                        }
                        
                        ?>
                  </td>

                  <td>
                     <?php
                     $ern = new Earning();
                     $rp = $ern->totalEarningsBatch(0,$l->id);
                      ?>
                     ₹{{number_format($rp)}}
                  </td>
                  <td>
                 <?php
                 $meetid=Recording::where("parent",$l->parent_api_class_id)->where("created_at",">=",date("Y-m-d 00:00:00"))->orderBy("id","desc")->first();
            if($meetid){
                $l["can_join"]=true;
                $l["api_id"]=$meetid->api_class_id;

            }else{
                $l["can_join"]=false;
                $l["api_id"]=""; 
            }
            
                 
                 if($l["can_join"]){ ?>
                            <p><a href="{{route('myclass.slaunch',['id'=>$l['id'],'meetid'=>$l['api_id']])}}" target="_blank" class="btn btn-primary btn-sm mb-2 cnotstart" data-class="<?=$l['api_id']?>">Goto  class</a>
                             <?php }else{
                                  ?>
                                  <p>
                                      <a href="javascript:void(0)" class="btn btn-danger btn-sm cnotstart mb-2" data-class="" >Class has not started</a>
                             <?php
                                } ?>
                  </td>
                  <td>
                
              
                
                     <a href="<?php echo route('admin.batch.batchassign', ['id' => $l->id]); ?>" class="btn btn-xs btn-primary mb-1"><i class="icon-plus"></i></a>
                     <a href="<?php echo route('admin.batch.edit', ['id' => $l->id]); ?>" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                     <a href="<?php echo route('admin.batch.delete', ['id' => $l->id]); ?>" onclick="return confirm('Do you really want to delete this batch')" class="btn btn-xs btn-danger mb-1"><i class="fa fa-trash"></i></a>
                     <!-- <a href="javascript:void(0)" data-batch="{{$l->id}}"    class="btn btn-xs btn-warning mb-1 notify"><i class="icon-bell"></i></a> -->

                     <a class="btn btn-info btn-sm mb-1" href="/user/batch/batch-progress-list/{{$l->id}}">Progress</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/batch/batch-recordings/{{$l->id}}">Recordings</a>
                     <a class="btn btn-secondary btn-sm mb-1" href="/user/batch/batch-feedback/{{$l->id}}">Feedbacks</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/assignment/{{$l->id}}">Assignments</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/upload/{{$l->id}}">Study Material</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/exam/{{$l->id}}">Subjective Exams</a>
                     <a class="btn btn-success btn-sm mb-1" href="/user/myclass/fees/{{$l->id}}">Fees</a>
                     <a class="btn btn-info btn-sm mb-1" href="/user/myclass/attend/{{$l->id}}">Attendance</a>

                     @if($l->is_completed == '0')

                        <a class="btn btn-primary btn-sm mb-1" onclick="return confirm('Do you want to mark this batch as completed? Once marked completed can not be changed.')" href="/user/batch/batch-is-completed/{{$l->id}}">Mark Completed</a>


                     @endif


                     

                  </td>
               </tr>
               @endif
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
<div class="modal fade" id="lessionModal" tabindex="-1" role="dialog" aria-labelledby="lessionTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="lessionTitle">Notify Batch Students</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-12">
                  <div class="form-group">
                     <label>Enter Title</label>
                     <input type="text" id="ntitle" name="title" class="form-control" />
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-group">
                     <label>Enter Message</label>
                     <input type="text" id="nmsg" name="msg" class="form-control" />
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary notify-now">Notify</button>
         </div>
      </div>
   </div>
</div>
@stop
@push('after-scripts')
<script>
   $('#myTable').DataTable();
   var bid="";
   
   $(".notify").on("click",function(){
       bid = $(this).data("batch");
        $("#lessionModal").modal("show");
   })
   
   $(document).on("click",".notify-now",function(){
       var title = $("#ntitle").val();
        var msg = $("#nmsg").val();
        
        
        $(".notify-now").html("Please wait...");
        $.ajax({
            url:'/user/batches/onesignal',
            type:'POST',
            data:{bid:bid,msg:msg,title:title,_token:'<?=csrf_token()?>'},
            success:function(res){
                if(res.success){
                   $("#ntitle").val("");
                   $("#nmsg").val("");
                   $("#lessionModal").modal("hide");
                   alert("Notification sent succesfully");
                   
                }else{
                     $(".notify-now").html("Notify");
                   alert(res.message)   
                }
            },
            error:function(){
                $(".notify-now").html("Notify");
                alert("Unable to send notification")
            }
        })
        
        
   })
   
   
       
</script>
@endpush