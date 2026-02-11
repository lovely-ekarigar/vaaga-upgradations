@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Student list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">Student list</h3>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable" class="table table-bordered table-striped dt-select ">
            <thead>
               <tr>
                  <th>@lang('labels.general.sr_no')</th>
                  <th>Student Name</th>
                  <th>Batch</th>
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>
               @foreach($list as $l)
               @if($l->student)

               <?php $count++; ?>
               <tr data-entry-id="1" role="row" class="odd">
                  <td><?=$count?></td>
                  <td>{{$l->student->first_name}} {{$l->student->last_name}}</td>
                  <td>{{$l->batches->name}}</td>
               </tr>
              @endif
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