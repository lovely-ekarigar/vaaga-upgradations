@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Question Edit'.' | '.app_name())

@section('content')

    

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Edit Question</h3>
            
            <!--<div class="float-right">-->
            <!--    <a href="/user/assessment/ques/list/{{$id}}"-->
            <!--       class="btn btn-success">View Questions</a>-->
            <!--</div>-->
        </div>
<form method="POST" enctype="multipart/form-data">
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
                      <label for="batch" class="control-label">Question</label>
                      <textarea class="form-control" required="" name="question" type="text">{{$assquestion->question}}</textarea>
                    </div>
                    
                </div>
                 <div class="row">
                       <div class="col-6 col-lg-4 form-group">
                    {!! Form::label('res_file', 'MP3 File for question', ['class' => 'control-label']) !!}
                    {!! Form::file('res_file',  ['class' => 'form-control', 'accept' => '.mp3']) !!}
                   <audio controls>
  
  <source src="{{$assquestion->question_file}}" type="audio/mpeg">
Your browser does not support the audio element.
</audio>

                </div>
                    <div class="col-6 col-lg-4 form-group">
                      <label for="batch" class="control-label">Time</label>
                       <input class="form-control" required="" name="qtime" type="time" value="{{$assquestion->qtime}}">
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


