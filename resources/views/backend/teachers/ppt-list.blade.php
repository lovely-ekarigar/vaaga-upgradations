@extends('backend.layouts.app')

@section('title', __('Teacher ppt-video') . ' | ' . app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">{{ $teacher_name->first_name }} {{ $teacher_name->last_name }} PPT/Video</h3>
           
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>Title</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            @php
                                $sl = 1;
                            @endphp
                            @foreach ($teacher_ppt as $tppt)
                              
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{$tppt->title}}</td>
                                        <td><a href="{{asset($tppt->file_path)}}" target="_blank">View</a></td>
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
