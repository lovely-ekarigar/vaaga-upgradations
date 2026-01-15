<?php
use App\Models\Batch;
use App\Models\Earning;
use App\Models\TeacherBatch;

?>
@extends('backend.layouts.app')

@section('title', __('labels.backend.payments.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.payments.title')</h3>
            <div class="float-right">
                <a href="{{ route('admin.payments.withdraw_request') }}"
                   class="btn btn-success">@lang('labels.backend.payments.add_withdrawal_request')</a>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.number_format($total_earnings,2)}}</h2>
                            <h5>@lang('labels.backend.payments.total_earnings')</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.number_format($total_withdrawal,2)}}</h2>
                            <h5>Total Withdrawal</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.number_format($total_withdrawal_pending,2) }}</h2>
                            <h5>Pending Withdrawal</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card text-white bg-primary text-center">
                        <div class="card-body">
                            <h2 class="">{{$appCurrency['symbol'].' '.number_format($total_balance,2) }}</h2>
                            <h5>@lang('labels.backend.payments.total_balance')</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a data-toggle="tab" class="nav-link active " href="#earning">
                                {{__('labels.backend.payments.earnings')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link" href="#withdrawal">
                               Withdrawals
                            </a>
                        </li>
                    </ul>
                </div><!--col-->
            </div><!--row-->
            <div class="tab-content">
                <!---Earning Tab--->
                <div id="earning" class="tab-pane container active">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="earningTable" class="table table-bordered table-striped ">
                                <thead>
                                <tr>
                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>Batch</th>
                                   <th>Batch Completion</th>

                                    <th>Per Hour Fees</th>
                                    <th>Max. Batch Hours</th>
                                    <th>Mins. Taught</th>
                                    <th>Hrs. Taught</th>
                                    <th>Subtotal</th>
                                    
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($tas as $k=>$t)
                                    <tr>

                                        <td>{{$k+1}}</td>
                                        <td>
                                            <?php $batch = Batch::find($t->bid); ?>
                                            @if($batch)
                                            {{$batch->name}}
                                            @endif
                                        </td>
                                        <td>
                                            <?php 
                        // if($batch->is_completed=='0'){
                                            $bo = new Batch;
                                            $br = $bo->bacthCompletion($t->bid,Auth::user()->id);

                                             if($br['total']!=0){
$completion = $br['completed']/$br['total'];
            }else{
            $completion = 0;
        }
//     }else{
// $completion=1;
//     }

      $er = new Earning;

       $tb=TeacherBatch::where("tid",$t->tid)->where("bid",$t->bid)->first();

                                            ?>
                                            {{floor($completion*100)}}%
                                        </td>
                                        <td>₹{{$t->fees}}</td>
                                         <td>{{$tb->fees_schedule}} Hrs.</td>
                                        <td>{{$er->getBatchHours($t->tid,$t->bid)}} Mins.</td> 
                                           <td>{{floor($er->getBatchHours($t->tid,$t->bid)/60)}} Hrs.</td> 
                                        <td>₹{{$er->totalEarnings($t->tid,$t->bid)}}</td>
                                       
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="withdrawal" class="tab-pane container">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="withdrawalTable" class="table table-bordered table-striped ">
                                <thead>
                                <tr>
                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>@lang('labels.backend.payments.fields.amount')</th>
                                    <th>@lang('labels.backend.payments.fields.payment_type')</th>
                                    <th>@lang('labels.backend.payments.fields.status')</th>
                                    <th>@lang('labels.backend.payments.fields.remarks')</th>
                                    <th>@lang('labels.backend.payments.fields.date')</th>
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
    </div>
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var withdrawal_route = '{{route('admin.payments.get_withdrawal_data')}}';
            // var earning_route = '{{route('admin.payments.get_earning_data')}}';


            $('#earningTable').DataTable({
                processing: false,
                serverSide: false,
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
                // ajax: earning_route,
                // columns: [

                //     {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                //     {data: "reference_no", name: 'reference_no'},
                //     {data: "course", name: 'course'},
                //     {data: "user", name: 'user'},
                //     {data: "amount", name: 'amount'},
                //     {data: "created_at", name: 'created_at'},
                //     {data: "actions", name: 'actions'},
                // ],
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
                },

            });

            $('#withdrawalTable').DataTable({
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
                ajax: withdrawal_route,
                columns: [

                    {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                    {data: "amount", name: 'amount'},
                    {data: "payment_type", name: 'payment_type'},
                    {data: "status", name: 'status'},
                    {data: "remarks", name: 'remarks'},
                    {data: "created_at", name: 'created_at'},
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                },
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
            });
        });

    </script>

@endpush