@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','History list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">History list</h3>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable" class="table table-bordered table-striped dt-select ">
            <thead>
               <tr>
                  <th>@lang('labels.general.sr_no')</th>
                  <th>Student Name</th>
                  <th>Course</th>
                  <th>Status</th>
                  <th>Tutor</th>
                  <th>Scheduled Date</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>
               @foreach($list as $demo)

               <?php $count++; ?>
               {{-- DEBUG: Print API data --}}
               <tr class="table-warning">
                  <td colspan="7">
                     <strong>Debug API Data:</strong>
                     <pre style="background:#f5f5f5; padding:10px; font-size:11px; max-height:200px; overflow:auto;">@php
if (isset($demo->api)) {
    echo "API Type: " . gettype($demo->api) . "\n";
    echo "Recordings isset: " . (isset($demo->api['recordings']) ? 'YES' : 'NO') . "\n";
    if (isset($demo->api['recordings'])) {
        $rec = $demo->api['recordings'];
        echo "Recordings Type: " . gettype($rec) . "\n";
        echo "Is SimpleXML: " . (($rec instanceof SimpleXMLElement) ? 'YES' : 'NO') . "\n";
        // Convert to array for display
        $recArray = json_decode(json_encode($rec), true);
        echo "Recording Array:\n";
        print_r($recArray);
    }
} else {
    echo "API data not set";
}
@endphp</pre>
                  </td>
               </tr>
               {{-- END DEBUG --}}
               <tr data-entry-id="1" role="row" class="odd">
                  <td><?=$count?></td>
                  <td>{{$demo->demo_request->name ?? 'N/A'}}</td>
                  <td>{{$demo->course ? $demo->course->title : ($demo->course_old->title ?? 'N/A')}}</td>
                  <td>{{$demo->demo_request->demo_status ?? 'N/A'}}</td>
                  <td>{{$demo->teacher->first_name ?? 'N/A'}} {{$demo->teacher->last_name ?? ''}}</td>
                  <td>{{$demo->date_time}}</td>
                  <td>
{{-- Debug API data type --}}
@if(isset($demo->api['recordings']))
    @php
        $recording = $demo->api['recordings'];
        // Handle SimpleXML object - convert to array or use object syntax
        if ($recording instanceof SimpleXMLElement) {
                            $recordingData = json_decode(json_encode($recording), true);
                        } else {
                            $recordingData = $recording;
                        }
    @endphp
    
    @if(isset($recordingData['recording']['internalMeetingID']))
        <a class="btn btn-sm btn-primary" href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{ $recordingData['recording']['internalMeetingID'] }}" target="_blank">
            <i class="fa fa-play"></i> Watch Recording
        </a>
    @else
        <small class="text-muted">Recording data incomplete</small>
    @endif
@else
    <small class="text-muted">No recording available</small>
@endif

                  </td>
               </tr>
              
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>

@stop
@push('after-scripts')
<script>
   $('#myTable').DataTable();
      
</script>
@endpush