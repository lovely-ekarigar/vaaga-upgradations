@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">Batch list</h3>
      @can('course_create')
      <div class="float-right">
         <a href="{{ route('admin.batch.create') }}"
            class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
      </div>
      @endcan
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable" class="table table-bordered table-striped dt-select ">
            <thead>
               <tr>
                  <th>@lang('labels.general.sr_no')</th>
                  <th>Batch Name</th>
                  <th>Course Name</th>
                  <th>Batch Duration</th>
                  <th>Batch class time</th>
                  <th>Batch Repeat</th>
                  <th>&nbsp; @lang('strings.backend.general.actions')</th>
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>
               @foreach($list as $l)
               @if(isset($l->batches) && $l->batches && $l->batches->name)
               <?php $count++; ?>
               <tr data-entry-id="1" role="row" class="odd">
                  <td><?=$count?></td>
                  <td>{{$l->batches->name}}</td>
                  <td>{{$l->course->title}}</td>
                  <td>{{$l->batches->start_date}} - {{$l->batches->end_date}}</td>
                  <td>{{$l->batches->start_time}} - {{$l->batches->end_time}}</td>
                  <td>
                     <?php
                        $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                        $wdays=json_decode($l->batches->occur,true);
                        foreach($wdays as $wd){
                         echo $days[$wd].", ";
                        
                        }
                        
                        ?>
                  </td>
                  <td>
                     <a href="<?php echo route('admin.batch.batchassign', ['id' => $l->batches->id]); ?>" class="btn btn-xs btn-primary mb-1"><i class="icon-plus"></i></a>
                     <a href="<?php echo route('admin.batch.edit', ['id' => $l->batches->id]); ?>" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                     <a href="<?php echo route('admin.batch.delete', ['id' => $l->batches->id]); ?>" onclick="return confirm('Do you really want to delete this batch')" class="btn btn-xs btn-danger mb-1"><i class="fa fa-trash"></i></a>
                     <!-- <a href="javascript:void(0)" data-batch="{{$l->id}}"    class="btn btn-xs btn-warning mb-1 notify"><i class="icon-bell"></i></a> -->

                     <a class="btn btn-info btn-sm" href="/user/batch/batch-progress-list/{{$l->batches->id}}">Progress</a>
                  </td>
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