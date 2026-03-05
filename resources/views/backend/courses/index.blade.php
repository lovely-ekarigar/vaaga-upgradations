@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">
                @lang('labels.backend.courses.title')
                @if(isset($category) && $category)
                    <small class="text-muted">- {{ $category->name }}</small>
                @endif
            </h3>
            
            @can('course_create')
                <div class="float-right">
                    @if(isset($category) && $category)
                        <a href="{{ route('admin.courses.create', ['category_id' => $category->id]) }}"
                           class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
                        <a href="{{ route('admin.categories.index') }}"
                           class="btn btn-secondary">Back to Categories</a>
                    @else
                        <a href="{{ route('admin.courses.create') }}"
                           class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
                    @endif
                </div>
            @endcan
        </div>
        <div class="card-body">
            @if(isset($category) && $category)
                @php
                    $childCats = \App\Models\Category::where('parent', $category->id)->get();
                    $childIds = $childCats->pluck('id')->toArray();
                    $courseCount = \App\Models\Course::whereIn('category_id', array_merge([$category->id], $childIds))->count();
                @endphp
                <div class="alert alert-info">
                    <strong>Showing courses from: {{ $category->name }}</strong> 
                    @if($childCats->count() > 0)
                        <br><small>Including {{ $childCats->count() }} sub-categories ({{ $courseCount }} total courses)</small>
                    @endif
                </div>
            @endif
            
            <div class="table-responsive">
                <div class="d-block">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="{{ route('admin.courses.index') }}"
                               style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                        </li>
                        |
                        <li class="list-inline-item">
                            <a href="{{ route('admin.courses.index') }}?show_deleted=1"
                               style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                        </li>
                    </ul>
                </div>


                <table id="myTable" class="table table-bordered table-striped @can('course_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                    <thead>
                    <tr>
                        @can('course_delete')
                            @if ( request('show_deleted') != 1 )
                                <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all"/></th>@endif
                        @endcan


                        @if (Auth::user()->isAdmin())
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Tutor</th>
                        @else
                                <th>@lang('labels.general.sr_no')</th>
                            @endif

                        <th>Course</th>
                        <!-- <th>@lang('labels.backend.courses.fields.category')</th> -->
                        <th>Sort Order</th>
                        <th>@lang('labels.backend.courses.fields.price') <br><small>(in {{$appCurrency['symbol']}})</small></th>
                            <th>@lang('labels.backend.courses.fields.status')</th>
                            <th>@lang('labels.backend.lessons.title')</th>
                        @if( request('show_deleted') == 1 )
                            <th>&nbsp; @lang('strings.backend.general.actions')</th>
                        @else
                            <th>&nbsp; @lang('strings.backend.general.actions')</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route = '{{route('admin.courses.get_data')}}';

            @if(request('show_deleted') == 1)
                route = '{{route('admin.courses.get_data',['show_deleted' => 1])}}';
            @endif

            @if(request('teacher_id') != "")
                route = '{{route('admin.courses.get_data',['teacher_id' => request('teacher_id')])}}';
            @endif

            @if(request('cat_id') != "")
                route = '{{route('admin.courses.get_data',['cat_id' => request('cat_id')])}}';
            @endif

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
                            columns: [ 1, 2, 3, 4,5,6,7 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4,5,6,7 ]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    { "data": function(data){
                        return '<input type="checkbox" class="single" name="id[]" value="'+ data.id +'" />';
                    }, "orderable": false, "searchable":false, "name":"id" },
                        @endif
                        @if (Auth::user()->isAdmin())
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "teachers", name: 'teachers'},

                    @else
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},

                    @endif
                    {data: "title", name: 'title'},
                    // {data: "category", name: 'category'},
                    {data: "sort_order", name: 'sort_order'},
                    {data: "price", name: "price"},
                    {data: "status", name: "status"},
                    {data: "lessons", name: "lessons"},
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
            {{--@can('course_delete')--}}
            {{--@if(request('show_deleted') != 1)--}}
            {{--$('.actions').html('<a href="' + '{{ route('admin.courses.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');--}}
            {{--@endif--}}
            {{--@endcan--}}
        });

        $(document).on("change",".sort_order",function(){
            var cid = $(this).data("id");
            var v = $(this).val();
        $.ajax({
            url:'/user/course-sort-order',
            data:{cid:cid,order:v},
            success:function(res){
                console.log(res);
            }
        })
        })

    </script>

@endpush