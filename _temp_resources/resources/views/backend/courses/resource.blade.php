@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Resources | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Resources</h3>
            @can('course_create')
                <div class="float-right">
                    <a href="{{ route('admin.resource.create') }}"
                       class="btn btn-success">Add Resource</a>

                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
             


                <table id="myTable" class="table table-bordered table-striped @can('course_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                    <thead>
                    <tr>
                      

                     
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Name</th>
                      

                        <th>Type</th>
                        <th>Date</th>
                        <th>&nbsp; @lang('strings.backend.general.actions')</th>
                   
                    </tr>
                    </thead>

                    <tbody>
                        <?php $count=0; ?>
@foreach($rrs as $r)
               <?php $count++; ?>            <tr>
                            <td>{{$count}}</td>
                            <td>{{$r->name}}</td>
                            <td>{{$r->tdata->name}}</td>
                            <td><?=date("d M, Y",strtotime($r->created_at))?></td>
                            
                            <td><a href="?del={{$r->id}}" onclick="return confirm('Are you sure to delete?')" class="btn btn-sm btn-danger">Delete</a></td>
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

        $(document).ready(function () {
            var route = '{{route('admin.courses.get_data')}}';

            

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4,5,6 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4,5,6 ]
                        }
                    },
                    'colvis'
                ]
                
        });

    </script>

@endpush