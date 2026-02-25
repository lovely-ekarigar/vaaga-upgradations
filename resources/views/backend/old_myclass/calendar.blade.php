@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','My Calendar'.' | '.app_name())

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
            <h3 class="page-title float-left mb-0">My Calendar</h3>

          
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
                    </div>
                </div>
               
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           <input type="submit" name="submit" class="btn btn-sm btn-danger" value="Mark my Unavailability">
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
        $("#unmodalTitle").html("My Unavailability for date "+date.dateStr);
 $('#uncalendarModal').modal();
   console.log(date.dateStr);
    

  },
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