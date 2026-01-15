@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Class Details'.' | '.app_name())

@section('content')
<style>
.loader {
     border: 7px solid #f3f3f3;
    border-radius: 50%;
    border-top: 7px solid #3498db;
    width: 21px;
    height: 20px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>  
 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">{{$batch->name}}</h3>
          <button class="btn btn-primary" style="float:right;" data-toggle="modal" data-target="#lessionModal">Start Class</button>

        </div>
        <div class="card-body">

          <p>Course: <strong>{{$course->title}}</strong></p>
          <p>
            Batch Duration: <strong>{{$batch->start_date}}</strong> - <strong>{{$batch->end_date}}</strong>
            
          </p>
          <p>Class time: <strong><?=date("h:iA",strtotime(date("Y-m-d ").$batch->start_time))?></strong> - <strong>
          <?=date("h:iA",strtotime(date("Y-m-d ").$batch->end_time))?></strong></p>

          <p>
            Weekdays: <strong>
            <?php
            $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");

            $ind=json_decode($batch->occur,true);
            foreach($ind as $in){
              echo $days[$in].", ";

            }


            ?>
          </strong>    

          </p> 
          <a class="btn btn-success" style="margin-bottom:10px;" href="<?php echo route('admin.myclass.recordings', ['id' => $batch->id]); ?>">Recordings</a>
          <a href="{{route('feedback',['id'=>$batch->id])}}" style="margin-bottom:10px;" class="btn btn-outline-info">Feedback</a>
          <br>
            <div class="table-responsive">
               <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>

                  

                        <th>@lang('labels.general.sr_no')</th>

                        <th>Name</th>
                  
                       
                      
                           
                       
                    </tr>
                    </thead>

                   <tbody>
                       <?php $count=0; ?>
                          @foreach($students as $l)
                       
                       <?php $count++; ?>
   <tr data-entry-id="1" role="row" class="odd">
     
      
      <td><?=$count?></td>
      <td>{{$l->first_name}} {{$l->last_name}}</td>   

    
      
   </tr>
   @endforeach
</tbody>
                </table>
        
              
            </div>
        </div>
    </div>

    <div class="modal fade" id="lessionModal" role="dialog" aria-labelledby="lessionTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lessionTitle">Lesson</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <label>Select Lesson</label>
        <select class="form-select form-control lname select2"  name="lname">
          <option>Select</option>
          @foreach($lessons as $l)
          <option value="{{$l->id}}">{{$l->title}}</option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary start-with-lession">Join Class</button>
      </div>
    </div>
  </div>
</div>
<a id="anchorID" href="" target="_blank"></a>

@stop

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/x2js/1.2.0/xml2json.min.js" integrity="sha256-RbFvov4fXA9DW/RzOAcIC0ZHIDmghGdsoug5slJHMMI=" crossorigin="anonymous"></script>
    <script>
      $(".start-with-lession").on("click",function(){
        $(this).attr("disabled",true);
        $(this).html('<div class="loader"></div>');
var lname=$(".lname").val();
if(lname.trim()==""){ 
alert("Please enter lesson name before start");
}else{
var route='<?php echo route('admin.myclass.flaunch'); ?>';

$.ajax({
  url:route,
  data:{lesson:lname,batch:'<?=$batch->id?>','_token':$('meta[name="csrf-token"]').attr('content')},
  type:'POST',
  success:function(data){
    console.log(data);
    if(data.success==true){
        $("#lessionModal").modal("hide");
      //window.open(data.url,"_blank");
//       $("#anchorID").attr("href","<?=URL::to('/')?>/runclass?sid="+btoa(data.url));
//     //  window.location.href="https://livetutorials.in/runclass?sid="+btoa(data.url);
// document.getElementById("anchorID").click();
 window.location.href="<?=URL::to('/')?>/runclass?sid="+btoa(data.url);  
    }else{
      $(this).html('Join Class');
      alert(data.msg);
      $(this).attr("disabled",false); 
    }

  }
})
}
      });
$('#myTable').DataTable();

    </script>

@endpush