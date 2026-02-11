<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Create Content | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Add Content</h3>
            @can('course_create')
                <div class="float-right">
                    <a href="{{ route('admin.content.index') }}"
                       class="btn btn-success"> Contents</a>

                </div>
            @endcan
        </div>
        <div class="card-body">

             <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.content.save'], 'files' => true,]) !!}

                    <div class="row ">
                        <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('course_id', trans('labels.backend.lessons.fields.course'), ['class' => 'control-label']) !!}
            <!--         {!! Form::select('course_id', $courses,  (request('course_id')) ? request('course_id') : old('course_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'course_id']) !!} -->



                    <select class="form-control js-example-placeholder-single select2" id="course_id" name="course_id">
                        @foreach($courses as $k=>$c)
                        <option value="{{$k}}" @if(request('course_id')==$k) selected @endif>

                             <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($k);
                     ?>

                        </option>

                        @endforeach

                    </select>



                    
                </div>
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!}

                        </div>
 

                      

                
                        <div class="col-12 form-group text-center">

                            {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>
<div class="row">

              
            </div>
                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@stop

@push('after-scripts')
   <script type="text/javascript">
        $(document).on('change', '#course_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{route('admin.content.create')}}" + "?course_id=" + course_id
            });
   </script>
@endpush