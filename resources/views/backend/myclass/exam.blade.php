@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title',' Upload Subjective Exam'.' | '.app_name())

@section('content')
<style type="text/css">
    form {
        display: inline-block;
    }
</style>

<div class="card">
    <div class="card-header">
        <h3 class="page-title float-left mb-0">{{$batch->name}} Upload Subjective Exam</h3>

    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label>Title</label>
                    <input type="text" name="title" required value="{{old('title')}}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Exam Date</label>
                    <input type="date" name="date" min="{{date('Y-m-d')}}" required value="{{old('date')}}"
                        class="form-control">
                </div>


                <div class="col-md-4">

                    <label>Select the file to upload.</label>
                    <input class="form-control" accept="application/msword,application/pdf, image/*" required
                        name="file" type="file">
                </div>
                <div class="col-md-4">
                    <input name="batch_id" type="hidden" value="{{$batch->id}}">
                    <input class="btn btn-primary" style="margin-top: 18px;" type="submit" value="Upload File">
                </div>
            </div>
        </form>


        <br>

        <hr>
        <div class="table-responsive">

            <table id="myTable" class="table table-bordered table-striped dt-select ">
                <thead>
                    <tr>
                        <th>@lang('labels.general.sr_no')</th>
                        <th>Title</th>
                        <th>Exam Date</th>
                        <th>&nbsp; @lang('strings.backend.general.actions')</th>

                    </tr>
                </thead>

                <tbody>
                    @php
                    $sl = 1;
                    @endphp
                    @foreach($assignment_list as $assignment)
                    <tr>
                        <td>{{$sl++}}</td>
                        <td>{{$assignment->title}}</td>
                        <td>{{date("d M Y",strtotime($assignment->exam_date))}}</td>
                        <td>
                            <a href="{{asset($assignment->file)}}" download="" class="btn btn-xs btn-primary mb-1"><i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <a href="/user/myclass/exam/{{$assignment->id}}/uploads" class="btn btn-xs btn-info mb-1"><i
                                    class="fa fa-users" aria-hidden="true"></i></a>

                            <a href="/user/myclass/exam/{{$assignment->id}}/delete"
                                onclick="return confirm('Do you want to delete?')" class="btn btn-xs btn-danger mb-1"><i
                                    class="icon-trash"></i></a>

                            <a href="{{asset($assignment->file)}}" target="_blank"
                                class="btn btn-xs btn-primary mb-1">View</a>
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