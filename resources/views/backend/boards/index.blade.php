@extends('backend.layouts.app')

@section('title', __('Boards').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Boards</h3>
            <div class="float-right">
                <a href="{{ route('admin.boards.create') }}"
                   class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable"
                               class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.categories.fields.name')</th>
                                <th>@lang('labels.backend.categories.fields.slug')</th>
                                <th>@lang('strings.backend.general.actions')</th>
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                                @foreach($boards_list as $boards)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$boards->name}}</td>
                                    <td>{{$boards->slug}}</td>
                                    <td>
                                      <a href="{{route('admin.boards.edit', $boards->id)}}" class="btn btn-xs btn-info mb-1">
                                        <i class="icon-pencil"></i>
                                      </a>
                                      <a href="{{route('admin.boards_delete',['id' => $boards->id])}}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-xs btn-danger mb-1">
                                        <i class="fa fa-trash"></i>
                                      </a>
                                      
                                    </td>
                                </tr>
                                @endforeach
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
         
            $('#myTable').DataTable({
               
                iDisplayLength: 10,
                                
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
    </script>
@endpush