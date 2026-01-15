@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Edit Assessment'.' | '.app_name())

@section('content')

    

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Edit Assessment</h3>
            
            <div class="float-right">
                <a href="/user/assessment"
                   class="btn btn-success">View Assessment</a>
            </div>
        </div>
<form method="POST">
              {{csrf_field()}}
        <div class="card-body">
          

                                <!--@if(Session::has("flash_message"))-->
                                <!--<div class="alert alert-success">-->
                                <!--{!! Session::get("flash_message") !!} -->
                                <!--</div>-->
                                <!--@endif-->
                                <!--@if(Session::has("flash_error"))-->
                                <!--<div class="alert alert-danger">-->
                                <!--{!! Session::get("flash_error") !!}-->
                                <!--</div>-->
                                <!--@endif-->
                <div class="row">
                    <div class="col-10 form-group">
                      <label for="batch" class="control-label">Name</label>
                      <input class="form-control" required="" name="name" type="text" value="{{$assessment->name}}">
                    </div>
                     <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', $assessment->description, ['id'=>'summernote','class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}

                </div>
                </div>
           
           
                
            </div>

            	<div class="form-group row justify-content-center">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
            
           </form>
            
    </div>
  
@stop


