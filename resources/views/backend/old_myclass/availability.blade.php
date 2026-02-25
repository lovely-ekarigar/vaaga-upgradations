@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','availability list'.' | '.app_name())

@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">My availability</h3>

          
        </div>
        <div class="card-body">
            <p>*Leave Empty start and end time in case of unavailability.</p>

  <form method="post">
    @csrf

 
            <div class="table-responsive">
                <?php 
                $days  = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
                ?>
        
                <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
              <th>S.No.</th>
              <th>Days</th>
              <th>Timing</th>
                    </thead>

                   <tbody>
                    @foreach($days as $kx=>$d)
                    <tr>
                        <td>{{$kx+1}}</td>
                        <td>{{ucwords($d)}}</td>
                        <td class="slots">
                            @if(count($data[$d]['s'])==0)
                            <div class="row">
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">Start Time</label>
                                         <input type="text" name="start[{{$d}}][]" class="form-control time start" value="" placeholder="Start Time">
                                    </div>
                                </div>
                                  <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">End Time</label>
                                         <input type="text" name="end[{{$d}}][]" class="form-control time end" value="" placeholder="End Time">
                                    </div>
                                </div>
                                <div class="col-4 mb-3" style="margin-top: 30px;">
                                    <a class="btn btn-sm btn-primary add-more" href="javascript:void(0)" data-day="{{$d}}">Add More</a>
                                </div>
                                
                            </div>
                            @else

                            @foreach($data[$d]['s'] as $k=>$s)

                            <div class="row">
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">Start Time</label>
                                         <input type="text" name="start[{{$d}}][]" value="{{$s}}" class="form-control time start"  placeholder="Start Time">
                                    </div>
                                </div>
                                  <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">End Time</label>
                                         <input type="text" name="end[{{$d}}][]" class="form-control time end" value="{{$data[$d]['e'][$k]}}" placeholder="End Time">
                                    </div>
                                </div>
                                @if($k==0)
                                <div class="col-4 mb-3" style="margin-top: 30px;">
                                    <a class="btn btn-sm btn-primary add-more" href="javascript:void(0)" data-day="{{$d}}">Add More</a>
                                </div>
                                @else
<div class="col-4 mb-3" style="margin-top: 30px;">
                                    <a class="btn btn-sm btn-danger rmslot" href="javascript:void(0)" >Delete</a>
                                </div>
                                @endif
                                
                            </div>


                            @endforeach

                            @endif
                            
                        </td>
                    </tr>
                    
                    @endforeach
                     
                      
</tbody>
                </table>
            </div>
            <div style="text-align: right;">
                <input type="submit" name="submit" class="btn btn-primary" value="Update availability">
            </div>
            
            </form>
        </div>
    </div>
@stop

@push('after-scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" ></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


    <script>
// $('#myTable').DataTable();
$('.time').timepicker({
  change:function(time){
      var element = $(this), text;
       var timepicker = element.timepicker();
            text =  timepicker.format(time);

        if($(this).hasClass('start')){
$(this).parent().parent().parent().find(".end").val("");

var elmStart = $(this).parent().parent().parent().parent().find(".start");
var elmEnd = $(this).parent().parent().parent().parent().find(".end");

var sTime=[];
var eTime=[];
elmStart.each(function(){
console.log($(this).val());
sTime.push(moment($(this).val(), 'HH:mm A').diff(moment().startOf('day'), 'seconds'))
})
elmEnd.each(function(){
eTime.push(moment($(this).val(), 'HH:mm A').diff(moment().startOf('day'), 'seconds'))
})

  $(".err").remove();
var overlapped=false;
for(var i=0;i<sTime.length-1;i++){

    if(sTime[sTime.length-1] >= sTime[i] && sTime[sTime.length-1] <= eTime[i]){
overlapped=true;
    }

}
if(overlapped){
 $(this).parent().append('<span class="text-danger err"> Selected time overlapped. </span>')
            $(this).val("");
}
console.log(overlapped);


        }
        if($(this).hasClass('end')){
           var startTime = $(this).parent().parent().parent().find(".start").val();
           $(".err").remove();
           if(startTime==""){
            $(this).parent().parent().parent().find(".start").focus();
            $(this).parent().append('<span class="text-danger err"> Kindly select start time </span>')
            $(this).val("");
           }
           var startSec = moment(startTime, 'HH:mm A').diff(moment().startOf('day'), 'seconds');
           var endSec = moment(text, 'HH:mm A').diff(moment().startOf('day'), 'seconds');
           if(startSec >= endSec){
$(this).parent().append('<span class="text-danger err"> End time can not be smaller than start time </span>')
            $(this).val("");
           }

        }
            // get access to this Timepicker instance
           
           
    }});
        $(document).on("click",".add-more",function(){
            var day = $(this).data('day');

            var html =`<div class="row">
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">Start Time</label>
                                         <input type="text" name="start[`+day+`][]" class="form-control time start " required value="" placeholder="Start Time">
                                    </div>
                                </div>
                                  <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="">End Time</label>
                                         <input type="text" name="end[`+day+`][]" class="form-control time end" required value="" placeholder="End Time">
                                    </div>
                                </div>
                                <div class="col-4 mb-3" style="margin-top: 30px;">
                                    <a class="btn btn-sm btn-danger rmslot" href="javascript:void(0)" >Delete</a>
                                </div>
                                
                            </div>`;
                    $(this).parent().parent().parent().append(html);        

$('.time').timepicker({
  change:function(time){
      var element = $(this), text;
       var timepicker = element.timepicker();
            text =  timepicker.format(time);

        if($(this).hasClass('start')){
$(this).parent().parent().parent().find(".end").val("");

var elmStart = $(this).parent().parent().parent().parent().find(".start");
var elmEnd = $(this).parent().parent().parent().parent().find(".end");

var sTime=[];
var eTime=[];
elmStart.each(function(){
console.log($(this).val());
sTime.push(moment($(this).val(), 'HH:mm A').diff(moment().startOf('day'), 'seconds'))
})
elmEnd.each(function(){
eTime.push(moment($(this).val(), 'HH:mm A').diff(moment().startOf('day'), 'seconds'))
})

  $(".err").remove();
var overlapped=false;
for(var i=0;i<sTime.length-1;i++){

    if(sTime[sTime.length-1] >= sTime[i] && sTime[sTime.length-1] <= eTime[i]){
overlapped=true;
    }

}
if(overlapped){
 $(this).parent().append('<span class="text-danger err"> Selected time overlapped. </span>')
            $(this).val("");
}
console.log(overlapped);


        }
        if($(this).hasClass('end')){
           var startTime = $(this).parent().parent().parent().find(".start").val();
           $(".err").remove();
           if(startTime==""){
            $(this).parent().parent().parent().find(".start").focus();
            $(this).parent().append('<span class="text-danger err"> Kindly select start time </span>')
            $(this).val("");
           }
           var startSec = moment(startTime, 'HH:mm A').diff(moment().startOf('day'), 'seconds');
           var endSec = moment(text, 'HH:mm A').diff(moment().startOf('day'), 'seconds');
           if(startSec >= endSec){
$(this).parent().append('<span class="text-danger err"> End time can not be smaller than start time </span>')
            $(this).val("");
           }

        }
            // get access to this Timepicker instance
           
           
    }});
        });

$(document).on("click",".rmslot",function(){
    $(this).parent().parent().remove();
})




    </script>

@endpush