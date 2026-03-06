@extends('backend.layouts.app')

@section('title')
Batch Live Tracking | {{ env('APP_NAME') }}
@stop

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Live Class Tracking</h4>
        <span class="badge bg-light text-dark">
            <i class="fas fa-sync-alt fa-sm me-1"></i> Auto-refresh
        </span>
    </div>

    @php
        $allMeetings = $meetings['meeting'] ?? [];
        if(isset($allMeetings['startTime'])){
            $allMeetings = [$meetings['meeting']];
        }
        if(count($allMeetings) > 0){
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
                            <span class="badge {{ $meeting['running'] == 'true' ? 'bg-success' : 'bg-secondary' }} me-2"><i class="fas fa-circle fa-xs"></i></span>
                            <h6 class="mb-0 text-truncate" style="max-width: 200px" title="{{ $meeting['meetingName'] ?? 'Meeting ' . ($index + 1) }}">
                                {{ $meeting['meetingName'] ?? 'Class ' . ($index + 1) }}
                            </h6>
                        </div>
                        <small class="text-muted">
                            <a href="https://manager.bigbluemeeting.com/lb?meeting={{ $meeting['internalMeetingID'] }}&lang=en" target="_blank">
                                <i class="fas fa-broadcast-tower"></i>
                            </a>
                            <a class="ms-2 joinDemo" href="javascript:void(0)" data-mid="{{ $meeting['meetingID'] }}">
                                <i class="fas fa-play"></i>
                            </a>
                        </small>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="d-flex border-bottom text-center">
                            <div class="p-2 flex-grow-1 border-end">
                                <small class="text-muted d-block">Started</small>
                                @php
                                    $startTimestamp = is_numeric($meeting['startTime']) ? intval($meeting['startTime']) / 1000 : null;
                                    $startTimeFormatted = $startTimestamp ? date('d M h:i A', $startTimestamp) : 'N/A';
                                @endphp
                                <small class="fw-semibold">{{ $startTimeFormatted }}</small>
                            </div>
                            <div class="p-2" style="width: 95px">
                                <small class="text-muted d-block">Participants</small>
                                <small class="fw-semibold">{{ $meeting['participantCount'] ?? 0 }}</small>
                            </div>
                            <div class="p-2" style="width: 95px">
                                <small class="text-muted d-block">Moderators</small>
                                <small class="fw-semibold">{{ $meeting['moderatorCount'] ?? 0 }}</small>
                            </div>
                        </div>

                        @php
                            $attendees = $meeting['attendees']['attendee'] ?? [];
                            if (isset($attendees['fullName'])) {
                                $attendees = [$attendees];
                            }
                        @endphp
                        
                        @if(!empty($attendees))
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr class="text-muted border-bottom">
                                        <th class="fw-normal py-2 ps-3" style="width: 35%">Name</th>
                                        <th class="fw-normal py-2 text-center" style="width: 20%">Role</th>
                                        <th class="fw-normal py-2 text-center" style="width: 20%">Device</th>
                                        <th class="fw-normal py-2 text-center" style="width: 12%"><i class="fas fa-microphone fa-sm"></i></th>
                                        <th class="fw-normal py-2 pe-3 text-center" style="width: 13%"><i class="fas fa-video fa-sm"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendees as $attendee)
                                    <tr class="border-bottom">
                                        <td class="ps-3 py-2">
                                            <small class="text-truncate d-block" style="max-width: 120px" title="{{ $attendee['fullName'] ?? '-' }}">
                                                {{ substr(($attendee['fullName'] ?? '-'), 0, 1) }} {{ $attendee['fullName'] ?? '-' }}
                                            </small>
                                        </td>
                                        <td class="text-center py-2">
                                            @if(strtolower($attendee['role'] ?? '') === 'moderator')
                                                <span class="badge bg-primary text-white" style="font-size: 0.75rem;">Moderator</span>
                                            @else
                                                <span class="badge bg-secondary text-white" style="font-size: 0.75rem;">Viewer</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            <small class="text-muted">{{ $attendee['clientType'] ?? 'HTML5' }}</small>
                                        </td>
                                        <td class="text-center py-2">
                                            @if(($attendee['hasJoinedVoice'] ?? 'false') === 'true')
                                                <i class="fas fa-microphone text-success"></i>
                                            @else
                                                <i class="fas fa-microphone-slash text-secondary"></i>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-center py-2">
                                            @if(($attendee['hasVideo'] ?? 'false') === 'true')
                                                <i class="fas fa-video text-success"></i>
                                            @else
                                                <i class="fas fa-video-slash text-secondary"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
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
        <div class="card-body text-center py-5">
            <i class="fas fa-video-slash text-muted mb-2 fa-2x"></i>
            <p class="text-muted mb-0">No active meetings</p>
        </div>
    </div>
    @endif
</div>

<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
    }
    .table th {
        font-weight: 500;
        color: #6c757d;
    }
    .badge {
        font-weight: 500;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-secondary {
        color: #adb5bd !important;
    }
    .bg-primary {
        background-color: #007bff !important;
    }
    small {
        font-size: 0.875rem;
    }
</style>

@stop

@push('after-scripts')
<script>
setInterval(function(){
    window.location.reload();
}, 20000);

$(document).on("click", ".joinDemo", function(){
    $(this).attr("disabled", true);
    $(this).html('<div class="loader"></div>'); 
    var mid = $(this).data("mid");
    var route = '/user/getLaunch/0/' + mid; 
    window.open(route);
    
    $.ajax({
        url: route,
        data: {'_token': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        success: function(data){
            if(data.success == true){
                window.open("https://vaagaacademy.com/runclass?sid=" + btoa(data.url));
            } else {
                alert(data.msg);
            }
        },
        complete: function(){
            $(this).html('<i class="fas fa-play"></i>');
            $(this).attr("disabled", false); 
        }
    });
});
</script>
@endpush
