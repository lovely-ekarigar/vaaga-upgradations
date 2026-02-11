@extends('backend.layouts.app')

@section('title', 'Demo Requests | '.app_name())




@section('content')
<style>
.loader {
     border: 7px solid #f3f3f3;
    border-radius: 50%;
    border-top: 7px solid #3498db;
    width: 21px;
    height: 20px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> 
    <div class="card">
        <div class="card-header">

                <h3 class="page-title d-inline">Demo Batch</h3>
                <div class="float-right">
<a class="btn btn-sm btn-primary text-white" href="{{route('admin.demo_batch.add')}}">Add Demo Batch</a>
</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">


                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>

                                <th>@lang('labels.backend.contacts.fields.name')</th>
                               
                                <th> Date</th>
                                <th> Time</th>
                                <th>Tutor</th>
                               
                                <th>Action</th>
                            </tr>
                            </thead>
                           

                            <tbody>
                                @foreach($batches as $batch)
                                
                                <tr>
                                    <td>{{$batch->name}}</td>
                                    <td>{{date("d M Y",strtotime($batch->demo_date))}}</td>
                                    <td>{{$batch->time_from}} - {{$batch->time_to}} </td>
                                    <td> @if($batch->teacher) {{$batch->teacher->name}} @endif</td>
                                  <td>
                
              
                
                     <a href="{{route('admin.demo_batch.student',['id'=>$batch->id])}}" class="btn btn-xs btn-primary mb-1"><i class="icon-plus"></i></a>
                     <a href="{{route('admin.demo_batch.edit',['id'=>$batch->id])}}" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                     <a href="?del={{$batch->id}}" onclick="return confirm('Do you really want to delete this demo batch')" class="btn btn-xs btn-danger mb-1"><i class="fa fa-trash"></i></a>
                 


                     

                     

                  </td>
                                    
                                    
                                </tr>
                                
                                @endforeach
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>






@stop


@push('after-scripts')
<script>
    $("#myTable").DataTable();
   </script>

@endpush
