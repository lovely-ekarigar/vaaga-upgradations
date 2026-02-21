@extends('backend.layouts.app')

@section('title', __('Teacher ppt-video') . ' | ' . app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline"> PPT/Video</h3>
           
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th style="width:50px;">@lang('labels.general.sr_no')</th>
                                    <th>Title</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                           
                           @foreach ($tutor_list as $list)
                               
                              
                                    <tr>
                                        <td>{{$loop->index +1}}</td>
                                        <td>{{$list->title}}</td>
                                        <td>
                                            @if($list->file_path && file_exists(public_path($list->file_path)))
                                                <a href="{{asset($list->file_path)}}" target="_blank" class="btn btn-primary">View</a>
                                            @else
                                                <span class="badge badge-danger">File Not Found</span>
                                            @endif
                                        </td>
                                    </tr>
                               
                                    @endforeach

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@push('after-scripts')
    <script type="text/javascript">
        $('#myTable').DataTable();

        $("#select2insidemodal").select2({
            dropdownParent: $("#exampleModal")
        });
    </script>
@endpush
