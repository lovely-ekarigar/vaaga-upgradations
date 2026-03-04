@extends('backend.layouts.app')

@section('title')
Batch Live Tracking | {{ env('APP_NAME') }}
@stop

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Live Class  Tracking</h4>
        <span class="badge bg-light text-dark">
            <i class="fas fa-sync-alt fa-sm me-1"></i> Auto-refresh
        </span>
    </div>

    @php
        $allMeetings = $meetings['meeting'] ?? [];
      if(isset($allMeetings['startTime'])){
      $allMeetings =  [$meetings['meeting']] ;
      }
        if(count($allMeetings)>0){
        usort($allMeetings, function ($a, $b) {
    return intval($b['startTime']) <=> intval($a['startTime']);
});
}
    @endphp

    @if(!empty($allMeetings))
        <div class="row g-3">
            @foreach($allMeetings as $index => $meeting)
            
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $meeting['running'] == 'true' ? 'bg-success' : 'bg-secondary' }} me-2"></span>
                            <h6 class="mb-0 text-truncate" style="max-width: 200px" title="{{ $meeting['meetingName'] ?? 'Meeting ' . ($index + 1) }}">
                                {{ $meeting['meetingName'] ?? 'Class ' . ($index + 1) }}
                            </h6>
                        </div>
                        <small class="text-muted"><a href="https://manager.bigbluemeeting.com/lb?meeting={{ $meeting['internalMeetingID'] }}&lang=en" target="_blank">
                            <i class="nav-icon icon-feed"></i>
                        </a>
                        
                        <a class="ms-2 joinDemo" style="margin-left:10px;" href="javascript:void(0)" data-mid="{{ $meeting['meetingID'] }}">  <i class="fas fa-play"></i></a>
                        </small>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="d-flex border-bottom">
                            <div class="p-2 flex-grow-1 border-end">
                                <small class="text-muted d-block">Started</small>
                                @php
                                    $startTimestamp = is_numeric($meeting['startTime']) ? intval($meeting['startTime']) / 1000 : null;
                                    $startTimeFormatted = $startTimestamp ? date('d M h:i A', $startTimestamp) : 'N/A';
                                @endphp
                                <small class="fw-semibold">{{ $startTimeFormatted }}</small>
                            </div>
                            <div class="p-2 text-center" style="width: 95px">
                                <small class="text-muted d-block">Participants</small>
                                <small class="fw-semibold">{{ $meeting['participantCount'] ?? 0 }}</small>
                            </div>
                            <div class="p-2 text-center" style="width: 95px">
                                <small class="text-muted d-block">Moderators</small>
                                <small class="fw-semibold">{{ $meeting['moderatorCount'] ?? 0 }}</small>
                            </div>
                        </div>

                        @php
                            $attendees = $meeting['attendees']['attendee'] ?? [];
                            if (isset($attendees['fullName'])) {
                                $attendees = [$attendees]; // wrap single into array
                            }
                        @endphp
                        
                        @if(!empty($attendees))
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr class="text-muted border-bottom">
                                        <th class="fw-normal py-1 ps-3" style="width: 30%">Name</th>
                                        <th class="fw-normal py-1 text-center" style="width: 15%">Role</th>
                                        <th class="fw-normal py-1 text-center" style="width: 15%">Device</th>
                                        <th class="fw-normal py-1 text-center" style="width: 15%"><i class="fas fa-presentation"></i></th>
                                        <th class="fw-normal py-1 text-center" style="width: 15%"><i class="fas fa-microphone"></i></th>
                                        <th class="fw-normal py-1 pe-3 text-center" style="width: 10%"><i class="fas fa-video"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendees as $attendee)
                                    <tr class="border-bottom">
                                        <td class="ps-3 py-1">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark rounded-circle me-2" style="width: 20px; height: 20px; line-height: 20px; font-size: 0.6rem">
                                                    {{ substr(($attendee['fullName'] ?? '-'), 0, 1) }}
                                                </span>
                                                <small class="text-truncate" style="max-width: 120px" title="{{ $attendee['fullName'] ?? '-' }}">{{ $attendee['fullName'] ?? '-' }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center py-1">
                                            <small class="badge {{ strtolower($attendee['role'] ?? '') === 'moderator' ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                                {{ ucfirst(strtolower($attendee['role'] ?? '-')) }}
                                            </small>
                                        </td>
                                        <td class="text-center py-1">
                                            <small class="text-muted">{{ $attendee['clientType'] ?? '-' }}</small>
                                        </td>
                                        <td class="text-center py-1">
                                            <i class="fas fa-xs {{ ($attendee['isPresenter'] ?? 'false') === 'true' ? 'fa-check text-success' : 'fa-times text-secondary' }}"></i>
                                        </td>
                                        <td class="text-center py-1">
                                            <i class="fas fa-xs {{ ($attendee['hasJoinedVoice'] ?? 'false') === 'true' ? 'fa-microphone text-success' : 'fa-microphone-slash text-secondary' }}"></i>
                                        </td>
                                        <td class="pe-3 text-center py-1">
                                            <i class="fas fa-xs {{ ($attendee['hasVideo'] ?? 'false') === 'true' ? 'fa-video text-success' : 'fa-video-slash text-secondary' }}"></i>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3">
                            <small class="text-muted"><i class="fas fa-user-slash me-1"></i> No attendees</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <i class="fas fa-video-slash text-muted mb-2"></i>
            <p class="text-muted mb-0">No active meetings</p>
        </div>
    </div>
    @endif

    <hr class="my-4">

    {{-- Student Join Records Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Student Join Records (App)</h4>
        <span class="badge bg-info text-white">
            <i class="fas fa-database fa-sm me-1"></i> Database Records
        </span>
    </div>

    @if($studentJoins->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 ps-3">Student Name</th>
                                <th class="py-2">Batch</th>
                                <th class="py-2 text-center">Join Time</th>
                                <th class="py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentJoins as $join)
                            <tr>
                                <td class="ps-3 py-2">
                                    @if($join->user)
                                        {{ $join->user->first_name }} {{ $join->user->last_name }}
                                    @else
                                        <span class="text-muted">Unknown (ID: {{ $join->user_id }})</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($join->batch)
                                        {{ $join->batch->name }}
                                    @else
                                        <span class="text-muted">Batch #{{ $join->batch_id }}</span>
                                    @endif
                                </td>
                                <td class="py-2 text-center">
                                    <span class="badge bg-light text-dark">
                                        {{ $join->created_at ? $join->created_at->format('h:i A') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-2 text-center">
                                    <span class="badge bg-success">Joined</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-2">
                <small class="text-muted">Total Records: {{ $studentJoins->count() }}</small>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="fas fa-info-circle text-muted mb-2"></i>
                <p class="text-muted mb-0">No student join records found for today</p>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        border-radius: 8px;
    }
   
    
    .table-sm th, .table-sm td {
        padding: 0.25rem 0.5rem;
       
    }
    small{
    font-size:100%;    
    }
    
    
    
   
</style>

@stop

@push('after-scripts')

<script>
setInterval(function(){
window.location.reload();
},20000);

  $(document).on("click",".joinDemo",function(){
        $(this).attr("disabled",true);
        $(this).html('<div class="loader"></div>'); 
var demo_id = $(this).data("id");
var mid = $(this).data("mid");
var flag = $(this).data("flag");
var route='';
if(!flag)
{
    route='/user/getLaunch/'+demo_id+'/'+mid; 
} else {
    route='javascript:void(0)'; 
}

var route='/user/getLaunch/'+demo_id+'/'+mid; 
//   window.location.href = route;
window.open(route);
 $.ajax({
   url:route,
   data:{demo_id:demo_id,'_token':$('meta[name="csrf-token"]').attr('content')},
   type:'GET',
   success:function(data){
    console.log(data);
     if(data.success==true){
       
       //window.open(data.url,"_blank");
     //   $("#anchorID").attr("href","https://vaagaacademy.com/runclass?sid="+btoa(data.url)); 
    //  window.location.href="https://vaagaacademy.com/runclass?sid="+btoa(data.url); 
    window.open("https://vaagaacademy.com/runclass?sid="+btoa(data.url))
 // document.getElementById("anchorID").click();
 $(this).html('Join Demo');
     }else{
       $(this).html('Join Demo');
       alert(data.msg);
       $(this).attr("disabled",false); 
     }

   }
 })

      });

</script>




@endpush