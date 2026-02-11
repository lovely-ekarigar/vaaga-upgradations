@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Subscription Due Reports | '.app_name())


@section('content')

<style type="text/css">
    .card-body h3{
        font-size: 16px;
    }
    .pad0{
            padding: 0px 0px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline mb-0">Subscription Due Reports</h3>

             <div class="float-right">
                <select class="form-control form-select select2" name="type" id="stype" style="width: 150px;">
                    <option value="all">Select Subscription filter</option>
                   
                    <option value="7days" @if(request('type')=='7days') selected @endif >Upcoming in 7 days</option>    
                    <option value="15days" @if(request('type')=='15days') selected @endif>Upcoming in 15 days</option>    
                    <option value="pending" @if(request('type')=='pending') selected @endif>Pending Subscription</option>    

                </select>
            </div>


        </div>
        <div class="card-body">
            
            <div class="row">
                
                <div class="col-md-3 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">
                                    <h1 class="">{{$appCurrency['symbol']}}{{$orders->sum('amount')}}</h1>
                                    <h3>Total amount for next cycle</h3>
                                </div>
                            </div>
                            
                        </div>

                         <div class="col-md-3 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">
                                    <h1 class="">{{$orders->count()}}</h1>
                                    <h3>Total Student for next cycle</h3>
                                </div>
                            </div>
                            
                        </div>
                          <div class="col-md-3 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">
                                    <h1 class="">{{$appCurrency['symbol']}}{{$orders->where("end_date","<=",date("Y-m-d"))->sum('amount')}}</h1>
                                    <h3>Total Overdue Amount</h3>
                                </div>
                            </div>
                            
                        </div>

                          <div class="col-md-3 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">
                                    <h1 class="">{{$orders->where("end_date","<=",date("Y-m-d"))->count()}}</h1>
                                    <h3>Total Overdue Subscription</h3>
                                </div>
                            </div>
                            
                        </div>


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
                        <th>Name</th>
                        <th>Items</th>
                        <th>Subscription Date</th>
                        <th>Course Mode</th>
                        <th>Next Due date</th>
                        <th>Due Amount <small>(in {{$appCurrency['symbol']}})</small></th>
                        <th>Total Cycle</th>
                        <th>Due Cycle</th>
                        <th>Total Amount<small>(in {{$appCurrency['symbol']}})</small></th>
                        <th>Paid Amount<small>(in {{$appCurrency['symbol']}})</small></th>
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

@push('after-scripts')
    <script>

        $(document).on("change","#stype",function(){
            window.location.href="?type="+$(this).val();
        })

        $(document).ready(function () {
            var route = '{{route('admin.subscription.report_data',['type'=>request('type')])}}';

            @if(request('offline_requests') == 1)
                route = '{{route('admin.subscription.report_data',['offline_requests' => 1])}}';
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
                        // exportOptions: {
                        //     columns: [ 1, 2, 3, 4, 5, 6, 7,8,9,10,11 ]
                        // }
                    },
                    {
                        extend: 'pdf',
                        // exportOptions: {
                        //     columns: [ 1, 2, 3, 4, 5, 6, 7 ]
                        // }
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
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "reference_no", name: 'reference_no'},
                     {data: "name", name: 'name'},
                     {data: "items", name: 'items'},
                     {data: "subs_date", name: 'subs_date'},
                     {data: "course_mode", name: 'course_mode'},
                    {data: "due_date", name: 'due_date'},
                    {data: "due_amount", name: 'due_amount'},
                    {data: "total_cycle", name: 'total_cycle'},
                    {data: "due_cycle", name: 'due_cycle'},
                   
                    {data: "total_amount", name: 'total_amount'},
                    {data: "paid_amount", name: "paid_amount"},
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
            @can('course_delete')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.orders.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan
        });
    </script>
@endpush