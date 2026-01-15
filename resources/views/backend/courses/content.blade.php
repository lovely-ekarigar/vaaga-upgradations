<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Course Content | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Course Content</h3>
           <div class="float-right ml-2">
                    <a target="_blank"  href="{{ route('admin.courses.index') }}" class="btn btn-primary">Course View</a>
                </div>
            @can('lesson_create')
                <div class="float-right">
                    <a href="{{ route('admin.content.create') }}@if(request('course_id')){{'?course_id='.request('course_id')}}@endif"
                       class="btn btn-success">Add Content</a>

                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('course_id', trans('labels.backend.lessons.fields.course'), ['class' => 'control-label']) !!}


                 

                   <select class="form-control js-example-placeholder-single select2" id="course_id" name="course_id">
                        @foreach($courses as $k=>$c)
                        <option value="{{$k}}" @if(request('course_id')==$k) selected @endif>

                             <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($k);
                     ?>

                        </option>

                        @endforeach

                    </select>
                </div>
            </div>
            <div class="d-block">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="{{ route('admin.lessons.index',['course_id'=>request('course_id')]) }}"
                           style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                    </li>
                    |
                    <li class="list-inline-item">
                        <a href="{{trashUrl(request()) }}"
                           style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                    </li>
                </ul>
            </div>

            @if(request('course_id') != "" || request('show_deleted') != "")
                <div class="table-responsive">

                    <table id="myTable"
                           class="table table-bordered table-striped @can('lesson_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                        <thead>
                        <tr>
                          
                            <th>@lang('labels.general.sr_no')</th>
                            <th>@lang('labels.backend.lessons.fields.title')</th>
                            <th>Sort Order</th>
                           
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                            @php $count=0; @endphp
                            @foreach($contents as $ct)
                            @php $count++; @endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$ct->title}}</td>
                                <td>
                                    <input type="number" name="sort_order" value="{{$ct->sort_order}}" class="form-control sort_order" data-id="{{$ct->id}}" style="width: 80px;" >
                                </td>
                                <td>
                                    <a href="javascript:void(0)" data-id="{{$ct->id}}" data-name="{{$ct->title}}" class="btn btn-xs btn-info mb-1 editContent"><i class="icon-pencil"></i></a>

                                    
<a href="{{route('admin.content.delete')}}?course_id={{$ct->id}}" onclick="return confirm('Are you sure to delete?')" class="btn btn-xs btn-danger mb-1"><i class="icon-trash"></i></a>

                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>
            @endif

        </div>
    </div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Content</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post">
            @csrf
      <div class="modal-body">
      
            <input type="hidden" name="content_id" id="content_id" >
            <input type="text" required name="content_data" id="content_data" class="form-control">
     
      </div>
      <div class="modal-footer">
       
        <input type="submit" name="submit" class="btn btn-primary" value="Update Now">
      </div>

        </form>
    </div>
  </div>
</div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
           
           $(document).on("click",".editContent",function(){
            var ccid = $(this).data("id");
            var ccon = $(this).data("name");
            $("#content_id").val(ccid);
            $("#content_data").val(ccon);

            $("#exampleModalCenter").modal('show');
           })

            $(document).on("change",".sort_order",function(){

                var sort_order = $(this).val();
                var sort_order_id = $(this).data("id");
                $.ajax({
                    url:'/user/content/sort/'+sort_order_id+"/"+sort_order,
                    type:'GET',
                    success:function(res){
                        console.log(res);
                    }
                })

            })
         

            $('#myTable').DataTable({
                processing: true,
                serverSide: false,
                iDisplayLength: 10,
                retrieve: false,
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    'colvis'
                ]
                
            });

    

          


            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.lessons.select_course')}}",
            });
            $(document).on('change', '#course_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{route('admin.content.index')}}" + "?course_id=" + course_id
            });
        });

    </script>
@endpush