@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Subjective Exam Uploads'.' | '.app_name())

@section('content')
<style type="text/css">
  form{
    display: inline-block;
  }
</style>
 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">{{$batch->name}} Uploaded Subjective Exam</h3>
          
        </div>
        <div class="card-body">

          
          <div class="table-responsive">
        
                <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>
                        <th>@lang('labels.general.sr_no')</th>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Uploaded File</th>
                        <th>Action</th>
                       
                    </tr>
                    </thead>

                   <tbody>
                    @php
                    $sl = 1;
                    @endphp
                    @foreach($users as $user)
                    @if($user->user)
                    <tr>
                        <td>{{$sl++}}</td>
                        <td>{{$user->user->first_name}} {{$user->user->last_name}}</td>
                        <td>
                            @if($user->uploaded)
                                <span class="badge badge-success">Uploaded</span>
                            @else
<span class="badge badge-danger">Not Uploaded</span>
                            @endif
                        </td>
                        <td> 
                            @if($user->uploaded)
                            
                            <a href="{{asset($user->assignment->file)}}" download="" class="btn btn-xs btn-primary mb-1"><i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                        </td>

                        <td>
                            @if($user->uploaded)
                                @if($user->assignment->marks)

                               Marks:  {{$user->assignment->marks}}

                                @else

                                <form method="post">
                                    @csrf

                                    <input type="text" name="marks" class="form-control mb-2" placeholder="Enter Marks">
                                    <input type="hidden" name="upload_id" value="{{$user->assignment->id}}">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-sm btn-primary">
                                    
                                </form>

                                @endif

                                  @endif


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