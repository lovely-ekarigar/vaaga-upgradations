@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Assessment'.' | '.app_name())

@section('content')

    

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Add New Question</h3>
            
            <div class="float-right">
                <a href="/user/assessment/ques/list/{{$id}}"
                   class="btn btn-success">View Questions</a>
            </div>
        </div>
<form method="POST"  enctype="multipart/form-data">
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
                      <textarea class="form-control" required="" name="question" type="text"></textarea>
                      <input class="form-control" required="" name="assessment_id" value="{{$assid}}" type="hidden">
                    </div>
                                          
 
                </div>
                
                 <div class="row">
                     <div class="col-6 col-lg-4 form-group">
                    {!! Form::label('res_file', 'MP3 File for question', ['class' => 'control-label']) !!}
                    {!! Form::file('res_file',  ['class' => 'form-control', 'accept' => '.mp3']) !!}
                   

                </div>
                    <div class="col-6 col-lg-4 form-group">
                      <label for="batch" class="control-label">Time</label>
                       <input class="form-control" required="" name="qtime" type="time">
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


