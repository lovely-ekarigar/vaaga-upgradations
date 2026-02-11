@extends('backend.layouts.app')

@section('title', __('labels.backend.Achievement.title').' | '.app_name())




@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h3 class="page-title d-inline">Achievement</h3>
                </div>
            </div>
        </div>
<form method="post" action="">
    @csrf
        <div class="card-body">

            <div class="form-group row">
                <label class="col-md-4 form-control-label">Courses offered</label>

                <div class="col-md-8">
                    <input class="form-control" type="text" name="courses_offered" value="{{$achievement->courses_offered}}">

                </div>
              
            </div>
            <div class="form-group row">
                <label class="col-md-4 form-control-label" >Happy Students</label>

                <div class="col-md-8">
                    <input class="form-control" type="text" name="happy_students" value="{{$achievement->happy_students}}">

                </div>
              

            </div>
            <div class="form-group row">

                <label class="col-md-4 form-control-label">Expert Tutor</label>

                <div class="col-md-8">
                    <input class="form-control" type="text" name="expert_tutor" value="{{$achievement->expert_tutor}}">

                </div>
               
            </div>
            <div class="form-group row">

                <label class="col-md-4 form-control-label">Hours taught</label>

                <div class="col-md-8">
                    <input class="form-control" type="text" name="hours_taught" value="{{$achievement->hours_taught}}">

                </div>
               
            </div>
            
            <input type="hidden" name="achievement_id" value="{{$achievement->id}}">
           
           
        </div>
        <div class="card-footer clearfix">
            <div class="row">
                
                <div class="col text-right">
                    <button class="btn btn-success pull-right" type="submit">Update</button>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
        </form>
    </div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            
            $('#myTable').DataTable();
        });

    </script>

@endpush