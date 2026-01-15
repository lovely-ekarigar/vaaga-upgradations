
<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Tutor availability'.' | '.app_name())

@section('content')
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>

<style type="text/css">
    .fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start {
    margin-right: 2px;
    cursor: pointer;
}
</style>
 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Tutor availability</h3>
 <div class="float-right" style="width:70%;">

                 

                     

                </div>
          
        </div>
        <div class="card-body">
            <form>
          <div class="row">
       
        <div class="col-3">
             <label>Select Course</label>
  <select class="form-control form-select select2 slotList" name="slot" id="slotList">
    <option value="">Select Course to check availability</option>
    @foreach($courseList as $c)

    <?php

$co = new Course;
     ?>
<option value="{{$c->id}}" @if($c->id==$course) selected @endif> {{$co->getCouseNameWithCat($c->id)}}</option>
    @endforeach
                           
                       </select>
        </div>

@if($tutors)

   <div class="col-3">
     <label>Select Tutors</label>
  <select class="form-control form-select select2 slotList" name="tutors[]" multiple >
    <option value="">Select Tutor</option>
    @foreach($tutors as $c)

<option value="{{$c->id}}" @if(request('tutors')) @if(in_array($c->id,request('tutors'))) selected @endif @endif> {{$c->name}}</option>
    @endforeach
                           
                       </select>
        </div>


@endif
@if($tutors)

<div class="col-3">
    <label>Select Date</label>
 <input type="date" name="date" class="form-control" value="{{request('date')}}" placeholder="Date" >
 @if(request('date'))
 <span id="clearDate" style="cursor: pointer;color: blue;">Clear Date</span>
 @endif
        </div>

<div class="col-3" style="padding-top: 30px;">
 <input type="submit" name="submit" class="btn btn-primary" value="Apply Filter">
        </div>

@endif

<div class="col-12">
    <hr>
</div>
    </div>
</form>
              <div id='calendar'></div>

        </div>
    </div>
    <div id="calendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            
            <h4 id="modalTitle" class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
        </div>
        <div id="modalBody" class="modal-body"> </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <a href="" id="eventUrl" class="btn btn-primary" >Goto</a>
        </div>
    </div>
</div>
</div>


@stop

@push('after-scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" ></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>


    <script>

$(document).on("change","#slotList",function(){

    window.location.href = "?slot="+$(this).val();
});

$(document).on("click","#clearDate",function(){

    $("input[name='date']").val('');
})

 document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      timeZone: 'UTC',
      themeSystem: 'bootstrap5',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      weekNumbers: true,
      dayMaxEvents: true, // allow "more" link when too many events
   
      events: <?=json_encode($dateList)?>,
        eventClick:  function(event, jsEvent, view) {
            event = event.event;
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.extendedProps.description);
            if(event.extendedProps.url!=""){
                 $('#eventUrl').show();
            $('#eventUrl').attr('href',event.extendedProps.url);
        }else{
 $('#eventUrl').hide();
        }
            $('#calendarModal').modal();
        
        },

        eventDidMount: function (info) {
      if (info.event.extendedProps.background) {
        info.el.style.background = info.event.extendedProps.background;
      }
    }

    });

    calendar.render();
  });
    </script>

@endpush