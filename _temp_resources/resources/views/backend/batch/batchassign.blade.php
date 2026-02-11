@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())

@section('content')

    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
  
    max-height: 100px;
    overflow: scroll;
}
    </style>

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Batch Assign</h3>
            
            <div class="float-right">
                 <a href="{{route('admin.teachers_course_availability',['slot'=>$batch->cid])}}" target="_blank"  class="btn btn-info">Check Tutor availability</a>
                <a href="{{ route('admin.batch') }}"
                   class="btn btn-success">View Batches </a>
            </div>
        </div>

        <div class="card-body">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.batch.batchassign.save'], 'files' => true,]) !!}
            <div class="row">
                

                <div class="col-10 form-group">
                    <label for="teac" class="control-label">Tutor</label>
                    <select name="teachersid"  class="form-control select2" id="teac" required="">
                        <?php foreach($teachers as $tea){ ?>
                            @if($tea)
                        <option value="{{$tea->id}}" <?php if($teacher){if($teacher->tid==$tea->id){ echo "selected";}} ?>>{{$tea->first_name}} {{$tea->last_name}} {{$tea->email}}</option>
                        @endif
                    <?php } ?>
                        
                    </select>
                </div>
               
             
                
                    <div class="col-10 form-group">
                    <label for="stu1" class="control-label">Students</label>
                    <select name="student[]"  class="form-control select2" multiple="" id="stu1" required="">
                        <?php foreach($students as $stu){ ?>
                        <option value="{{$stu->id}}" <?php if(in_array($stu->id,$assigneduid)){ echo "selected";} ?> >{{$stu->first_name}} {{$stu->last_name}} {{$stu->email}}</option>
                    <?php } ?>
                        
                    </select>
                    <input type="hidden" name="bid" value="{{$bid}}">
                </div>
                <div class="col-10 form-group">
                    <button id="all" type="button" role="button" class="btn btn-primary">Select All</button>
                    </div>
   
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
var toggle=true;
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
            $("#all").on("click",function(){
              if(toggle){
    $('#stu1').select2('destroy').find('option').prop('selected', 'selected').end().select2();
              }else{
                  $('#stu1').select2('destroy').find('option').prop('selected', false).end().select2();

              }
              toggle = !toggle;
            })
        });


    </script>

@endpush

