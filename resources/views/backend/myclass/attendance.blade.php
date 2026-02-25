@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Attendance list'.' | '.app_name())

@section('content') 

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Attendance for {{$batch->name}}</h3>
          
        </div>
        <div class="card-body">
  <form>
        
<div class="row">
          <div class="col-md-4">
           
        <input type="hidden" name="bid" value="{{$batch->id}}">
        <input type="date" name="date" class="form-control" required="">
        </div>
<div class="col-md-4">
       <button type="submit" class="btn btn-xs btn-primary mb-1" > Check Attendance</button>
      
      </div>
    </div>
  </form> 
  <hr>
            <div class="table-responsive">
        
                <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>

                   

                        <th>@lang('labels.general.sr_no')</th>

                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Join Time</th>
                        <th>Status</th>
                        
                       
                    </tr>
                    </thead>

                   <tbody>
                       <?php $count=0; ?>
                       @foreach($students as $st)

                       <tr>
                         <td>
                           <?php $count++;echo $count; ?>
                         </td>
                         <td>{{$st->first_name}} {{$st->last_name}}</td>
                         <td>{{$st['date']}}</td>
                         <td>{{$st['time']}}</td>
                         <td>
                           <?php if($st["present"]){
                            echo "Present";

                           }else{
                            echo "Absent";
                           } ?>
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