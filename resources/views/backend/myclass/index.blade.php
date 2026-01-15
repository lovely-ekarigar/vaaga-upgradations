
<?php
use App\Models\Batch;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Class list'.' | '.app_name())

@section('content')

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">My Classes</h3>
          
        </div>
        <div class="card-body">
            <div class="table-responsive">
        
                <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>

                     <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all"/>
                                </th>

                        <th>@lang('labels.general.sr_no')</th>

                        <th>Batch Name</th>
                        <th>Course Name</th>
                        <th>Batch Fees</th>
                        <th>Completion</th>
                        <th>Batch Duration</th>
                        <th>Batch class time</th>                        
                        <th>Batch Repeat</th>
                      
                            <th>&nbsp; @lang('strings.backend.general.actions')</th>
                       
                    </tr>
                    </thead>

                   <tbody>
                       <?php $count=0; ?>
                          @foreach($list as $l)
                       
                       <?php $count++; ?>
   <tr data-entry-id="1" role="row" class="odd">
      <td class="text-center">
        <input type="checkbox" class="single" name="id[]" value="1">
      </td>
      
      <td><?=$count?></td>
      <td>{{$l->name}}</td>   
     
      <td>{{$l->course->title}}</td>
        <td>{{$l->fees}}</td>  
      <td>
          <?php
          $lo = new Batch;
          $ar = $lo->bacthCompletion($l->id,Auth::user()->id);
           ?>

           {{$ar['completed']}}/{{$ar['total']}}
           @if($ar['total']!=0)
            ({{floor($ar['completed']*100/$ar['total'])}}%)
           @endif
      </td>
      <td>{{$l->start_date}} - {{$l->end_date}}</td>
      <td>{{$l->start_time}} - {{$l->end_time}}</td>
      <td>
        <?php
         $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
         $wdays=json_decode($l->occur,true);
         foreach($wdays as $wd){
          echo $days[$wd].", ";

         }

         ?>
      </td>
      <td>
        @if($l->active=='1')
         <a href="<?php echo route('admin.myclass.details', ['id' => $l->id]); ?>" class="btn btn-xs btn-primary mb-1"><i class="icon-eye"></i></a>

         <a href="<?php echo route('admin.myclass.upload', ['id' => $l->id]); ?>" class="btn btn-xs btn-info mb-1"><i class="icon-cloud-upload"></i></a>

       <a href="<?php echo route('admin.myclass.attendance', ['id' => $l->id]); ?>" class="btn btn-xs btn-info mb-1"><i class="fa fa-users" aria-hidden="true"></i>

        <a href="{{route('admin.myclass.assignment',['id'=>$l->id])}}" class="btn btn-outline-info mb-1">Assignment</a>
        <a href="{{route('admin.myclass.exam',['id'=>$l->id])}}" class="btn btn-outline-info mb-1">Subjective Exam</a>

        <a href="/user/batch-progress-list-teacher/{{$l->id}}" class="btn btn-outline-info mb-1">Progress</a>
</a>

@else

<p>This batch no longer belongs to you.</p>
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