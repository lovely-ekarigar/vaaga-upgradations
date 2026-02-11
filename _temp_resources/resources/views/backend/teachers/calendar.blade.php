@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title',$user->name.' Calendar'.' | '.app_name())

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
            <h3 class="page-title float-left mb-0">{{$user->name}} Calendar</h3>
 <div class="float-right">
    <div class="row">
        <div class="col-5">
               <a href="{{ route('admin.teachers_editavailability',['id'=>$user->id]) }}"
                       class="btn btn-primary btn-sm">Edit availability</a>
        </div>
        <div class="col-7">
  <select class="form-control form-select" name="type" id="slotList">
                            <option value="booked" @if($slot=='booked') selected @endif >Booked Slot</option>
                            <option value="available" @if($slot=='available') selected @endif>Available Slot</option>
                            <option value="all" @if($slot=='all') selected @endif>All Slot</option>
                       </select>
        </div>
    </div>
                 

                     

                </div>
          
        </div>
        <div class="card-body">
            <p>*Click on any date box to mark your Unavailability.</p>
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
            <a href="" id="lvDel" class="btn btn-danger" style="display: none;" >Delete</a>
        </div>
    </div>
</div>
</div>

<div id="uncalendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            
            <h4 id="unmodalTitle" class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
        </div>
        <form method="post">
            @csrf
        <div id="unmodalBody" class="modal-body"> 

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Unavailability Reason*</label>
                        <input type="text" name="reason" class="form-control" required>
                        <input type="hidden" name="date" id="unDate">
                        <input type="hidden" name="user_id" value="{{$user->id}}" >
                    </div>
                </div>
               
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           <input type="submit" name="submit" class="btn btn-sm btn-danger" value="Mark  Unavailability">
        </div>
    </form>
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
       dateClick: function(date, jsEvent, view, resourceObj) {

        $("#unDate").val(date.dateStr);
        $("#unmodalTitle").html("Unavailability for date "+date.dateStr);
 $('#uncalendarModal').modal();
   console.log(date.dateStr);
    

  },
      events: <?=json_encode($dateList)?>,
        eventClick:  function(event, jsEvent, view) {
            event = event.event;
            $("#lvDel").hide();
            console.log(event);
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.extendedProps.description);
            if(event.extendedProps.url!="" && event.extendedProps.url!="x"){
                 $('#eventUrl').show();
            $('#eventUrl').attr('href',event.extendedProps.url);
        }else{
            if(event.extendedProps.url=="x"){
                $("#lvDel").attr("href","/user/teachers/u/delete/"+event.extendedProps.lvid);
                $("#lvDel").show();
                $("#eventUrl").hide();
            }else{
               $('#eventUrl').hide(); 
            }
 
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