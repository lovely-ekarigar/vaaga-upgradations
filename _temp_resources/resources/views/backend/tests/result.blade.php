@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.tests.title').' | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Test Result</h3>
            
        </div>
        <div class="card-body table-responsive">
       
           

        


            <table id="myTable"
                   class="table table-bordered table-striped @can('test_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                <tr>
                   
                    <th>@lang('labels.general.sr_no')</th>
                    <th>Name</th>
                    <th>is Attempted</th>
                    <th>Total Questiom</th>
                    <th>Correct</th>
                    <th>Unattempted</th>
                    <th>Wrong</th>
                    <th>Action</th>
                   
                </tr>
                </thead>

                <tbody>

                    @foreach($users as $k=>$u)
                    @if($u)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$u->first_name}} {{$u->last_name}}</td>
                        <td>
                            @if($u->isAttempted)
                            Yes

                            @else
                            No
                            @endif
                        </td>
                        <td> @if($u->isAttempted) {{$u->totalQuestion}} @else - @endif</td>
                        <td>@if($u->isAttempted) {{$u->totalCorrect}} @else - @endif</td>
                        <td>@if($u->isAttempted) {{$u->totalUnattempted}} @else - @endif</td>
                        <td>@if($u->isAttempted) {{$u->totalQuestion-$u->totalCorrect-$u->totalUnattempted}} @else - @endif</td>
                        <td>
                            @if($u->isAttempted) <a href="{{route('admin.tests.analysis',['id'=>$test->id,'sid'=>$u->id])}}" class="btn btn-sm btn-primary">View Analysis</a> @else - @endif
                        </td>
                    </tr>
                    @endif


                    @endforeach


                </tbody>
            </table>
           
        </div>
    </div>


     <!-- Start Model -->

     <!-- End Model -->
@stop

@push('after-scripts')
    <script>

       $("#myTable").DataTable();


    </script>

@endpush