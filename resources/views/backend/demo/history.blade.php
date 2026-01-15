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
               <tr data-entry-id="1" role="row" class="odd">
                  <td><?=$count?></td>
                  <td>{{$demo->demo_request->name}}</td>
                  <td>{{$demo->course->title}}</td>
                  <td>{{$demo->demo_request->demo_status}}</td>
                  <td>{{$demo->teacher->first_name}} {{$demo->teacher->last_name}}</td>
                  <td>{{$demo->date_time}}</td>
                  <td>

                     @if($demo->internal_id)
                     <a href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{$demo->internal_id}}" target="_blank">Watch</a>
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