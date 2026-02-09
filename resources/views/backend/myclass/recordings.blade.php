@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Reorded Class'.' | '.app_name())

@section('content')

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">{{$batch->name}} Recordings</h3>
          
        </div>
        <div class="card-body">
          <div class="row">
             @foreach($list as $rec)
             
             @if($rec->length > 0)
             
            <div class="col-md-3" style="margin-bottom: 10px;">
              <div style="height: 100%;
    width: 100%;
    border: 1px solid #2f353a54;
    padding: 12px 0px;
    border-radius: 7px;
    margin: 0px 10px;">
              <a href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{$rec->internal_id}}" target="_blank" style="    color: #0e0e0e;">
              <h1 style="    font-size: 16px;
    text-align: center;">@if($rec->lesson) {{$rec->lesson->title}} @endif</h1>
              <p style="font-size: 10px;
    text-align: center;">{{date("d M Y",strtotime($rec->recording_date))}}</p>
            </a>
          </div>
            </div>
            @endif
            @endforeach
          </div>
           
        </div>
    </div>
@stop

@push('after-scripts')
    <script>
$('#myTable').DataTable();

    </script>

@endpush