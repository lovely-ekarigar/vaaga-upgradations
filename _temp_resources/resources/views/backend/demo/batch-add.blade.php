<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Add Demo Batch'.' | '.app_name())

@section('content')

   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"> 

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Add Demo Batch</h3>
            
            <div class="float-right">
                <a href="{{ route('admin.demo_batch') }}"
                   class="btn btn-success">View Batches</a>
            </div>
        </div>

        <div class="card-body">
          {!! Form::open(['method' => 'POST', 'route' => ['admin.demo_batch.save'], 'files' => true,]) !!}
                <div class="row">
                    <div class="col-10 form-group">
                      <label for="batch" class="control-label">Batch name</label>
                      <input class="form-control" required="" placeholder="Enter batch name" name="name" type="text" id="batch">
                    </div>
                    
                </div>
           

            <div class="row">
                
                    <div class="col-6 form-group">
                    <label for="course" class="control-label">Teacher</label>
                    <select name="tid" required=""  class="form-control select2">
                        <option value=""></option>
                        
                        @foreach($teachers as $t)
                        <option value="{{$t->id}}">{{$t->name}}</option>
                        
                        @endforeach
                       
                        
                    </select>
                </div>
                   
                   
                </div>
                
                <div class="row">
                
                <div class="col-12 col-lg-3 form-group">
                    <label for="sbatchduration" class="control-label">Demo date</label>
                    <input class="form-control" required="" placeholder="Enter Demo date" name="date" type="date" id="sbatchduration">

                </div>
                
                
                 <div class="col-12 col-lg-3 form-group">
                    <label for="sbct" class="control-label">Demo start time</label>
                    <input class="form-control time" required="" placeholder="Enter batch start time" name="from" type="text" id="sbct">
                </div>
                <div class="col-12 col-lg-3 form-group">
                    <label for="ebct" class="control-label">Demo end time</label>
                    <input class="form-control time" required="" placeholder="Enter batch end time" name="to" type="text" id="ebct">
                </div>
                
            </div>
               
            

           
                
            </div>

            	<div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.demo_batch'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('Submit')) }}
                        </div>
                    </div>
            
           </form>
            
    </div>
  
@stop


@push('after-scripts')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" ></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>

$('.time').timepicker({});
        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
        });


    </script>

@endpush

