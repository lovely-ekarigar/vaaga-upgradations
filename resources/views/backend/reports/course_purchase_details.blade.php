@extends('backend.layouts.app')

@section('title', __('Course Purchase Details').' | '.app_name())

@push('after-styles') 
@endpush

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Purchase Details - {{ $course_name }}</h3>
            <div class="float-right">
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Sales Report
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="purchaseDetailsTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Student Name</th>
                                <th>Reference No.</th>
                                <th>Date of Purchase</th>
                                <th>Amount <span style="font-weight: lighter">(in {{config('settings.currency_symbol')}})</span></th>
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
            var purchase_route = '{{route('admin.reports.get_course_purchase_details_data', ['course_id' => $course->id])}}';

            $('#purchaseDetailsTable').DataTable({
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
                ajax: purchase_route,
                columns: [
                    {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                    {data: "student_name", name: 'student_name'},
                    {data: "reference_no", name: 'reference_no'},
                    {data: "purchase_date", name: 'purchase_date'},
                    {data: "amount", name: 'amount'},
                ],
                order: [[3, 'desc']], // Sort by date descending
            });
        });
    </script>
@endpush
