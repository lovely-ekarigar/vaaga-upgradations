@extends('backend.layouts.app')

@section('title', __('labels.backend.categories.title').' | '.app_name())

@section('content')
<style type="text/css">
    table{
        width: 100% !important;
    }
    ul.cat-list {
    list-style: none;
    margin: 0px;
    padding: 0px;
}
ul.cat-list li {
    display: inline;
}
</style>
    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline" style="font-size: 16px;">
                  <ul class="cat-list">
                    <li>
                <a href="?parent=0">@lang('labels.backend.categories.title')</a>
</li>
                @if(count($cats)>0)
                  
                @foreach($cats as $cat)

            <li>
                 > <a href="?parent={{$cat->id}}">{{$cat->name}}</a>
            </li>

                @endforeach
        
                @endif
    </ul>
            </h3>

            <div class="float-right">
                <a href="{{ route('admin.categories.create') }}"
                   class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <div class="d-block">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.categories.index') }}"
                                       style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                                </li>
                                |
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.categories.index') }}?show_deleted=1"
                                       style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                                </li>
                            </ul>
                        </div>


                        <table id="myTable"
                               class="table table-bordered table-striped @can('category_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                            <thead>
                            <tr>

                                @can('category_delete')
                                    @if ( request('show_deleted') != 1 )
                                        <th style="text-align:center;">
                                            <input type="checkbox" class="mass" id="select-all"/>
                                        </th>
                                    @endif
                                @endcan

                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.categories.fields.name')</th>
                                <th>@lang('labels.backend.categories.fields.slug')</th>
                              
                                <th>@lang('labels.backend.categories.fields.courses')</th>
                                <th>Sort Order</th>
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
        </div>
    </div>

@stop

@push('after-scripts')
    <script>
        $(document).ready(function () {
            var route = '{{route('admin.categories.get_data')}}?parent={{$parent}}';

            @if(request('show_deleted') == 1)
                route = '{{route('admin.categories.get_data',['show_deleted' => 1])}}';
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
                            columns: [1, 2, 3, 5]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 5]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @can('category_delete')
                        @if(request('show_deleted') != 1)
                    {
                        "data": function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                        @endif
                        @endcan
                    {
                        data: "DT_RowIndex", name: 'DT_RowIndex'
                    },
                    {data: "name", name: 'name'},
                    {data: "slug", name: 'slug'},
                    {data: "courses", name: "courses"},
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
                language: {
                    url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons: {
                        colvis: '{{trans("datatable.colvis")}}',
                        pdf: '{{trans("datatable.pdf")}}',
                        csv: '{{trans("datatable.csv")}}',
                    }
                }
            });
            @can('category_access')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.categories.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan


            $(document).on('click', '.delete_warning', function () {
                const link = $(this);
                const cancel = (link.attr('data-trans-button-cancel')) ? link.attr('data-trans-button-cancel') : 'Cancel';

                const title = (link.attr('data-trans-title')) ? link.attr('data-trans-title') : "{{ trans('labels.backend.categories.not_allowed') }}";

                swal({
                    title: title,
                    icon: 'error',
                    showCancelButton: true,
                    cancelButtonText: cancel,
                    type: 'info'
                })
            });


        });

$(document).on('change','.sort_order_field',function(){
    var cid = $(this).data('id');
    var sv = $(this).val();
  $.ajax({
    url:'/user/update-sort',
    data:{cid:cid,sort:sv},
    success:function(){
        
    }
  })
})


    </script>
@endpush