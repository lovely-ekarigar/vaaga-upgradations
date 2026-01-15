@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Assign Student'.' | '.app_name())

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
             
                <a href="{{ route('admin.demo_batch') }}"
                   class="btn btn-success">View Batches </a>
            </div>
        </div>

        <div class="card-body">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.demo_batch.student.update',$batch->id], 'files' => true,]) !!}
            <div class="row">
                

               
             
                
                    <div class="col-10 form-group">
                    <label for="stu1" class="control-label">Students</label>
                    <select name="student[]"  class="form-control select2" multiple="" id="stu1" required="">
                        <?php foreach($requestList as $stu){ ?>
                        <option value="{{$stu->user_id}}" <?php if(in_array($stu->user_id,$assigneduid)){ echo "selected";} ?> >{{$stu->name}} ({{$stu->email}}) - @if($stu->course) {{$stu->course->title}} @endif</option>
                    <?php } ?>
                        
                    </select>
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

