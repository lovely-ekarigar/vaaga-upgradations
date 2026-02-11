@extends('backend.layouts.app')

@section('title', __('Training') . ' | ' . app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Training</h3>
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#trainigModal">
                    Add Trainig
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>Trainig For</th>
                                    <th>Title</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                                @php
                                $sl = 1;
                                @endphp
                                @foreach($training_list as $training)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$training->training_for}}</td>
                                    <td>{{$training->title}}</td>
                                    <td>
                                        <a href="{{asset($training->file_path)}}" class="btn btn-primary" target="_blank">View</a>
                                        <a href="{{route('admin.training-delete',['id'=>$training->id])}}" onclick="return confirm('Are you sure you want to delete Training Data?');" class="btn btn-xs btn-danger mb-1">
                                            <i class="fa fa-trash"></i>
                                          </a>
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


    <div class="modal fade" id="trainigModal" role="dialog" aria-labelledby="trainigModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trainigModalLabel">Schedule Demo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="{{route('admin.training-store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row ">
                            <div class="col-12 col-lg-6 form-group">
                                <label for="title" class="control-label">Trainig For<span
                                        class="required">*</span></label>

                                <select required class="form-control form-select" name="training_for">
                                    <option value="">Select for....</option>
                                    <option value="tutor">Tutor</option>
                                    <option value="student">Student</option>

                                </select>

                            </div>

                            <div class="col-12 col-lg-6 form-group">
                                <label for="title" class="control-label">Title<span class="required">*</span></label>
                                <input class="form-control" required name="title" type="text">

                            </div>

                            <div class="col-12 col-lg-12 form-group">
                                <label for="title" class="control-label">File <span class="required">*</span></label>
                                <input class="form-control" name="file_upload" accept=".doc, .docx,.ppt, .pptx" type="file">
                               
                                <br>
                                <span class="required">*</span> Indicates required fields
                            </div>


                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Submit" class="btn btn-primary" />
                    </div>
                </form>
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
