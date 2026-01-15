@extends('backend.layouts.app')

@section('title', __('labels.backend.reports.sales_report').' | '.app_name())

@push('after-styles')
@endpush

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.reports.sales_report')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.$total_earnings}}</h2>
                            <h5>Full Course Earning</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 ml-auto">
                    <div class="card text-white bg-success text-center">
                        <div class="card-body">
                            <h2 class="">{{$total_sales}}</h2>
                            <h5>Full Course Sales</h5>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-3">
                     <a href="/user/subscription-reports">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.$course_earnings_subs}}</h2>
                            <h5>Subscription Earning</h5>
                        </div>
                    </div>
                </a>
                </div>
                <div class="col-12 col-lg-3 ml-auto">
                    <a href="/user/subscriptions">
                    <div class="card text-white bg-success text-center">
                        <div class="card-body">
                            <h2 class="">{{$course_sales_subs}}</h2>
                            <h5>Subscription Sales</h5>
                        </div>
                    </div>
                </a>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <h4>Full @lang('labels.backend.reports.courses')</h4>
                    <div class="table-responsive">
                        <table id="myCourseTable" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.reports.fields.name')</th>
                                <th>@lang('labels.backend.reports.fields.orders')</th>
                                <th>@lang('labels.backend.reports.fields.earnings') <span style="font-weight: lighter">(in {{$appCurrency['symbol']}})</span></th>
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
            var course_route = '{{route('admin.reports.get_course_data')}}';
            var course_route_subs = '{{route('admin.reports.get_course_data_subs')}}';
           
            $('#myCourseTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    'colvis'
                ],
                ajax: course_route,
                columns: [
                    {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                    {data: "name", name: 'name'},
                    {data: "orders", name: 'orders'},
                    {data: "earnings", name: 'earnings'},
                ],


                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
            });



 
            
        });

    </script>

@endpush