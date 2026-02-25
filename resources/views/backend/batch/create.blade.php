<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())
 
@section('content')

   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"> 

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Create Batch</h3>
            
            <div class="float-right">
                <a href="{{ route('admin.batch') }}"
                   class="btn btn-success">View Batches</a>
            </div>
        </div>

        <div class="card-body">
          {!! Form::open(['method' => 'POST', 'route' => ['admin.batch.save'], 'files' => true,]) !!}
                <div class="row">
                    <div class="col-10 form-group">
                      <label for="batch" class="control-label">Batch name</label>
                      <input class="form-control" required="" placeholder="Enter batch name" name="batch" type="text" id="batch">
                    </div>
                    
                </div>
           

            <div class="row">
                
                    <div class="col-10 form-group">
                    <label for="course" class="control-label">Course</label>
                    <select name="cid" required=""  class="form-control select2">
                        <?php foreach($courses as $c){ ?>
                        <option value="{{$c->id}}">  <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($c->id);
                     ?></option>
                    <?php } ?>
                        
                    </select>
                </div>
                   
                   
                </div>
                
                <div class="row">
                
                <div class="col-12 col-lg-6 form-group">
                    <label for="sbatchduration" class="control-label">Batch start date</label>
                    <input class="form-control" required="" placeholder="Enter Batch Start Date" name="startbatchdate" type="date" id="sbatchduration">

                </div>
                <div class="col-12 col-lg-6 form-group">
                    <label for="ebatchduration" class="control-label">Batch end date</label>
                    <input class="form-control" required="" placeholder="Enter Batch End Date" name="endbatchdate" type="date" id="ebatchduration">

                </div>
            </div>
               
               <div class="row">
                <div class="col-12 col-lg-3 form-group">
                    <label for="sbct" class="control-label">Batch start time</label>
                    <input class="form-control time" required="" placeholder="Enter batch start time" name="sbatchtime" type="text" id="sbct">
                </div>
                <div class="col-12 col-lg-3 form-group">
                    <label for="ebct" class="control-label">Batch end time</label>
                    <input class="form-control time" required="" placeholder="Enter batch end time" name="ebatchtime" type="text" id="ebct">
                </div>
              
               
            </div>
             <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="week" class="control-label" >Occurance</label>
                    <select name="occurance[]" required="" class="form-control select2" multiple="" id="week">
                    
                      <option value="0">Monday</option>
                      <option value="1">Tuesday</option>
                      <option value="2">Wednesday</option>
                      <option value="3">Thursday</option>
                      <option value="4">Friday</option>
                      <option value="5">Saturday</option>
                      <option value="6">Sunday</option>
                    </select>
                </div>
                
               
            </div>

           
                
            </div>

            	<div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.batch'), __('buttons.general.cancel')) }}
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

