<?php
use App\Models\Course;
?>

@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())

@section('content')

    

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">User Course Managment</h3>
          
        </div>

        <div class="card-body">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.batch.course.save'], 'files' => true,]) !!}
            <div class="row">
                

                <div class="col-10 form-group">
                    <label for="selcourse" class="control-label">Select Course</label>
                    <select name="cid"  class="form-control select2" id="selcourse" required="">
                        <option value="">Select course</option>
                        <?php foreach($courses as $c){ 
                            $cro = new Course;


                            ?>
                        <option value="{{$c->id}}" <?php if($sel==$c->id){ echo "selected";} ?>>{{$cro->getCouseNameWithCat($c->id)}}</option>
<?php } ?>
                       
                        
                    </select>
                </div>
               
                    
                    @if($sel)
                    <div class="col-10 form-group">
                    <label for="stu1" class="control-label">Students</label>
                    <select name="student[]"  class="form-control select2" multiple="" id="stu1" required="">
                                      <?php foreach($students as $s){ ?>
                        <option value="{{$s->id}}" <?php if(in_array($s->id, $uids)){ echo "selected";} ?>>{{$s->first_name}} {{$s->last_name}}({{$s->email}})</option>
<?php } ?>
                        
                    </select>
                   
                </div>
                @endif
   
                </div>

           
                
            </div>

            <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.batch'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('Submit')) }}
                        </div>
                    </div>
            
           
            
    </div>
  
@stop


@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route='<?=route("admin.batch.course")?>';
            $("#selcourse").on("change",function(){
                console.log($(this).val());
                if($(this).val()!=""){
                window.location.href=route+"?id="+$(this).val();
            }

            });
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

