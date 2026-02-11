@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'GST Reports | '.app_name())


@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            <h3 class="page-title d-inline mb-0">GST Reports</h3>

             <div class="float-right">
               <input type="text" name="daterange" class="form-control" value="{{$date}}" />
            </div>


        </div>
        <div class="card-body">
            
            <div class="row">
                
                <div class="col-md-6 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">

                                    <h1 class="">{{$appCurrency['symbol']}} {{number_format($orders->sum('amount') + $subscriptions->sum('amount'),2)}}</h1>
                                    <h3>Total Invoice Amount</h3>
                                </div>
                            </div>
                            
                        </div>

                         <div class="col-md-6 col-12">
                         
                            <div class="card bg-light text-dark text-center py-3">
                                <div class="card-body pad0">
                                    <h1 class="">{{$appCurrency['symbol']}} {{number_format($orders->sum('gst') + $subscriptions->sum('gst'),2)}}</h1>
                                    <h3>Total GST</h3>
                                </div>
                            </div>
                            
                        </div>
                         

                         


            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      
                        <th>@lang('labels.general.sr_no')</th>
                        <th>@lang('labels.backend.orders.fields.reference_no')</th>
                        <th>Invoice No.</th>
                        <th>Invoice Amount<small>(in {{$appCurrency['symbol']}})</small></th>
                        <th>GST Amount<small>(in {{$appCurrency['symbol']}})</small></th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($orders->get() as $order)

                        <tr>
                            <td></td>
                            <td>{{$order->reference_no}}</td>
                            <td><a href="/user/orders/{{$order->id}}">{{$order->id}}</a></td>
                            <td>{{$appCurrency['symbol']}} {{number_format($order->amount,2)}}</td>
                            <td>{{$appCurrency['symbol']}} {{number_format($order->gst,2)}}</td>
                            <td>{{date('d M Y',strtotime($order->created_at))}}</td>
                        </tr>

                        @endforeach

                          @foreach($subscriptions->get() as $order)

                        <tr>
                            <td></td>
                            <td>{{$order->reference_no}}</td>
                            <td><a href="/user/subscription-reports-details/{{$order->order_id}}">{{$order->order_id}}-{{$order->id}}</a></td>
                            <td>{{$appCurrency['symbol']}} {{number_format($order->amount,2)}}</td>
                            <td>{{$appCurrency['symbol']}} {{number_format($order->gst,2)}}</td>
                            <td>{{date('d M Y',strtotime($order->created_at))}}</td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('after-scripts')

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>

      
        $(document).ready(function () {
         $('input[name="daterange"]').daterangepicker({},function(start, end, label) {
            window.location.href="?start="+start.format('YYYY-MM-DD')+"&end="+end.format('YYYY-MM-DD')
    // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

         $("#myTable").DataTable();
        });

    </script>
@endpush