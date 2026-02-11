@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Create Resource | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Add Resource</h3>
            @can('course_create')
                <div class="float-right">
                    <a href="{{ route('admin.resource.index') }}"
                       class="btn btn-success"> Resources</a>

                </div>
            @endcan
        </div>
        <div class="card-body">
             <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.resource.save'], 'files' => true,]) !!}

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!}

                        </div>

<div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Select Resouce type</label>
                            <select class="form-control" name="parent">
                                <option value="0"></option>
                                @foreach($res as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                      
 <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('res_file', 'Resource File', ['class' => 'control-label']) !!}
                    {!! Form::file('res_file',  ['class' => 'form-control', 'accept' => '']) !!}
                   

                </div>
                  <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['id'=>'summernote','class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}

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
   
@endpush