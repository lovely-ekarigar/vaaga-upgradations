<?php
use App\Models\Course;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.lessons.title')</h3>
                <div class="float-right ml-2">
                    <a target="_blank"  href="{{ route('admin.courses.index') }}" class="btn btn-primary">Course View</a>
                </div>

            @can('lesson_create')
                <div class="float-right">
                    @if(request('course_id'))

<a target="_blank"  href="/user/content?course_id={{request('course_id')}}"
                       class="btn btn-primary">Course Content</a>

                    @endif
                    <a href="{{ route('admin.lessons.create') }}@if(request('course_id')){{'?course_id='.request('course_id')}}@endif @if(request('content_id')){{'&content_id='.request('content_id')}}@endif"
                       class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>

                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-lg-6 form-group">
                    {!! Form::label('course_id', trans('labels.backend.lessons.fields.course'), ['class' => 'control-label']) !!}
                   <!--  {!! Form::select('course_id', $courses,  (request('course_id')) ? request('course_id') : old('course_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'course_id']) !!} -->

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
                 <div class="col-6 col-lg-6 form-group">
                   
                    <label class="control-label" for="course_id">Course content</label>
                    <select class="form-control js-example-placeholder-singlex select2" name="content_id" id="content_id">
                        <option></option>
                        @foreach($contents as $ct)
                        <option value="{{$ct->id}}" <?php if($request->content_id==$ct->id){ echo "selected"; } ?> >{{$ct->title}}</option>
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
                            @can('lesson_delete')
                                @if ( request('show_deleted') != 1 )
                                    <th style="text-align:center;"><input class="mass" type="checkbox" id="select-all"/>
                                    </th>@endif
                            @endcan
                            <th>@lang('labels.general.sr_no')</th>
                            <th>@lang('labels.backend.lessons.fields.title')</th>
                            <th>@lang('labels.backend.lessons.fields.published')</th>
                            <th>Sort Order</th>
                            @if( request('show_deleted') == 1 )
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @else
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            @endif

        </div>
    </div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {

  $(document).on("change",".sort_order",function(){

                var sort_order = $(this).val();
                var sort_order_id = $(this).data("id");
                $.ajax({
                    url:'/user/lesson/sort/'+sort_order_id+"/"+sort_order,
                    type:'GET',
                    success:function(res){
                        console.log(res);
                    }
                })

            })




            var route = '{{route('admin.lessons.get_data')}}';


            @php
                $show_deleted = (request('show_deleted') == 1) ? 1 : 0;
                $course_id = (request('course_id') != "") ? request('course_id') : 0;
                $content_id = (request('content_id') != "") ? request('content_id') : 0;
            $route = route('admin.lessons.get_data',['show_deleted' => $show_deleted,'course_id' => $course_id,'content_id'=>$content_id]);
            @endphp

            route = '{{$route}}';
            route = route.replace(/&amp;/g, '&');


            @if(request('course_id') != "" || request('show_deleted') != "")

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
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
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    {
                        "data": function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                        @endif
                    {
                        data: "DT_RowIndex", name: 'DT_RowIndex'
                    },
                    {data: "title", name: 'title'},
                    {data: "published", name: "published"},
                    {data: "sort_order", name: "sort_order"},
                    {data: "actions", name: "actions"}
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });

            @endif

            @can('lesson_delete')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.lessons.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan


            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.lessons.select_course')}}",
            });
             $(".js-example-placeholder-singlex").select2({
                placeholder: "Select course content",
            });
            $(document).on('change', '#course_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{route('admin.lessons.index')}}" + "?course_id=" + course_id
            });
             $(document).on('change', '#content_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{route('admin.lessons.index')}}" + "?course_id={{$request->course_id}}&content_id="+course_id
            });
        });

    </script>
@endpush