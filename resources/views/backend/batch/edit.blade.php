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
            <h3 class="page-title float-left">Edit Batch</h3>
            
            <div class="float-right">
                <a href="{{ route('admin.batch') }}"
                   class="btn btn-success">View Batches</a>
            </div>
        </div>

        <div class="card-body">
           {!! Form::open(['method' => 'POST', 'route' => ['admin.batch.update'], 'files' => true,]) !!}
                <div class="row">
                    <div class="col-10 form-group">
                      <label for="batch" class="control-label">Batch name</label>
                      <input class="form-control" placeholder="Batch" name="batch" type="text" id="batch" value="{{$batch->name}}">
                    </div>
                    
                </div>
           

            <div class="row">
                
                    <div class="col-10 form-group">
                    <label for="course" class="control-label">Course</label>
                    <select name="cid"  class="form-control select2">
                        <?php foreach($courses as $c){ ?>
                        <option value="{{$c->id}}" <?php if($c->id==$batch->cid){ echo "selected";} ?> >
                            
<?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($c->id);
                     ?>
                        </option>
                    <?php } ?>
                        
                        
                    </select>
                </div>
                   
                   
                </div>
                
                <div class="row">
                
                <div class="col-12 col-lg-6 form-group">
                    <label for="sbatchduration" class="control-label">Batch start date</label>
                    <input class="form-control" required="" placeholder="Enter Batch Start Date" name="startbatchdate" value="{{$batch->start_date}}" type="date" id="sbatchduration">

                </div>
                <div class="col-12 col-lg-6 form-group">
                    <label for="ebatchduration" class="control-label">Batch end date</label>
                    <input class="form-control" required="" placeholder="Enter Batch End Date" name="endbatchdate" type="date" id="ebatchduration" value="{{$batch->end_date}}">

                </div>
            </div>
               
               <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="sbct" class="control-label">Batch start time</label>
                    <input class="form-control time" required="" placeholder="Enter batch start time" name="sbatchtime" type="text" id="sbct" value="{{$batch->start_time}}">
                </div>
                <div class="col-12 col-lg-6 form-group">
                    <label for="ebct" class="control-label">Batch end time</label>
                    <input class="form-control time" required="" placeholder="Enter batch end time" name="ebatchtime" type="text" id="ebct" value="{{$batch->end_time}}">
                </div>
               
            </div>

            <?php

 $wdays=json_decode($batch->occur,true);

             ?>
             <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="week" class="control-label" >Occurance</label>
                    <select name="occurance[]" required="" class="form-control select2" multiple="" id="week">
                    
                      <option value="0" <?php if(in_array(0, $wdays)){ echo "selected";}?>>Monday</option>
                      <option value="1" <?php if(in_array(1, $wdays)){echo "selected";} ?> >Tuesday</option>
                      <option value="2" <?php if(in_array(2, $wdays)){echo "selected";} ?> >Wednesday</option>
                      <option value="3" <?php if(in_array(3, $wdays)){echo "selected";} ?> >Thursday</option>
                      <option value="4" <?php if(in_array(4, $wdays)){echo "selected";} ?>>Friday</option>
                      <option value="5" <?php if(in_array(5, $wdays)){echo "selected";} ?> >Saturday</option>
                      <option value="6"<?php if(in_array(6, $wdays)){echo "selected";} ?> >Sunday</option>
                    </select>
                </div>
                <input type="hidden" name="bid" value="{{$batch->id}}" />
               
            </div>

           
                
            </div>

            	<div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.batch'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('Update')) }}
                        </div>
                    </div>
            
           
            
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

