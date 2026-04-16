@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Subscriptions | '.app_name())


@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline mb-0">Subscriptions</h3>

        </div>
        <div class="card-body">
            <div class="d-block">
                <ul class="list-inline">
                   
                </ul>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align:center;">
                            <input type="checkbox" class="mass" id="select-all"/>
                        </th>
                        <th>@lang('labels.general.sr_no')</th>
                        <th>@lang('labels.backend.orders.fields.reference_no')</th>
                        <th>Invoice No.</th>
                        <th>@lang('labels.backend.orders.fields.items')</th>
                        <th>@lang('labels.backend.orders.fields.amount') <small>(in {{$appCurrency['symbol']}})</small></th>
                        <th>@lang('labels.backend.orders.fields.payment_status.title')</th>
                        <th>User Name</th>
                        <th>Course Mode</th>
                        <th>Subscription Date</th>
                        <th>&nbsp; @lang('strings.backend.general.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('after-styles')
    <style>
        .cycle-switch-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }
        .cycle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }
        .cycle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .cycle-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #28a745;
            border-radius: 24px;
            transition: background-color .3s;
        }
        .cycle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: #fff;
            border-radius: 50%;
            transition: transform .3s;
        }
        .cycle-switch input:checked + .cycle-slider {
            background-color: #dc3545;
        }
        .cycle-switch input:checked + .cycle-slider:before {
            transform: translateX(20px);
        }
        .cycle-label {
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .cycle-label.on { color: #28a745; }
        .cycle-label.off { color: #dc3545; }
    </style>
@endpush

@push('after-scripts')
    <script>
        $(document).ready(function () {
            var route = '{{route('admin.subscription.get_data')}}';

            @if(request('offline_requests') == 1)
                route = '{{route('admin.subscription.get_data',['offline_requests' => 1])}}';
            @endif

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                order: [[9, 'desc']], // Default sort by subscription date column (index 9) in descending order
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6, 7 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6, 7 ]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                    {
                        data: function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                    {data: "DT_RowIndex", name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: "reference_no", name: 'reference_no'},
                    {data: "id", name: 'id'},
                    {data: "items", name: 'items', orderable: false, searchable: false},
                    {data: "amount", name: 'amount'},
                    {data: "payment", name: 'payment', orderable: false},
                    {data: "name", name: 'name'},
                    {data: "course_mode", name: 'course_mode', orderable: false, searchable: false},
                    {data: "date", name: "created_at"},
                    {data: "actions", name: "actions", orderable: false, searchable: false}
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
            @can('course_delete')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.orders.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan

            // Cycle switch toggle handler
            $(document).on('change', '.cycle-checkbox', function () {
                var cb = $(this);
                var referenceNo = cb.attr('data-reference');
                var enable = cb.is(':checked') ? 1 : 0;

                cb.prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.subscription.toggle_cycle") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reference_no: referenceNo,
                        enable: enable
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            // Full page reload to reflect updated state
                            location.reload();
                        } else {
                            cb.prop('checked', !cb.is(':checked'));
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        cb.prop('checked', !cb.is(':checked'));
                        toastr.error('Something went wrong. Please try again.');
                    },
                    complete: function () {
                        cb.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush