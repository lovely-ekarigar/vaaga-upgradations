<?php
use App\Models\Course;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.orders.title').' | '.app_name())

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="page-title mb-0 float-left">
<?php  if(str_contains($order->course_mode,"monthly")){ ?>
          Subscription

        <?php }else{ ?>
  @lang('labels.backend.orders.title')
        <?php } ?>

        </h3>
            @if($order->invoice != "")
                @if(Auth::user()->isAdmin())
                <div class="float-right">
                    <a class="btn btn-success" target="_blank" href="/user/view-invoice/{{$order->id}}/show">
                        @lang('labels.backend.orders.view_invoice')
                    </a>
                    <a class="btn btn-primary" href="/user/view-invoice/{{$order->id}}/download">
                        @lang('labels.backend.orders.download_invoice')
                    </a>
                </div>
                @endif
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.orders.fields.reference_no')</th>
                            <td>
                               ORD-{{$order->id}}
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.orders.fields.ordered_by')</th>
                            <td>
                                Name    : <b>{{$order->user->name}}</b><br>
                                Email   : <b>{{$order->user->email}}</b>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.orders.fields.items')</th>
                            <td>
                                @foreach($order->items as $key=>$item)
                                    @php $key++ @endphp
                                    <?php  $crs = new Course(); ?>
                                    {{$key.'. '.$crs->getCouseNameWithCat($item->item->id)}}<br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.orders.fields.amount')</th>
                            <td>{{ $order->amount.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                         <tr>
                            <th>Course Mode</th>
                            <td>{{ getCourseType($order->course_mode) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.orders.fields.payment_type.title')</th>
                            <td>

                                @if($order->payment_type == 1)
                                    {{trans('labels.backend.orders.fields.payment_type.stripe') }}
                                @elseif($order->payment_type == 2)
                                    {{trans('labels.backend.orders.fields.payment_type.paypal')}}
                                @elseif($order->payment_type == 4)
                                    Razorpay
                                @else
                                    {{trans('labels.backend.orders.fields.payment_type.offline')}}
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.orders.fields.payment_status.title')</th>
                            <td>

                                @if($order->status == 0)
                                {{trans('labels.backend.orders.fields.payment_status.pending') }}
                                    <a class="btn btn-xs mb-1 mr-1 btn-success text-white" style="cursor:pointer;"
                                       onclick="$(this).find('form').submit();">
                                        {{trans('labels.backend.orders.complete')}}
                                        <form action="{{route('admin.orders.complete', ['order' => $order->id])}}"
                                              method="POST" name="complete" style="display:none">
                                            @csrf
                                        </form>
                                    </a>
                                @elseif($order->status == 1)
                               {{trans('labels.backend.orders.fields.payment_status.completed')}}
                                @else
                                {{trans('labels.backend.orders.fields.payment_status.failed')}}
                                @endif

                            </td>
                        </tr>


                        <tr>
                            <th>@lang('labels.backend.orders.fields.date')</th>
                            <td>{{ $order->created_at->format('d M, Y | h:i A') }}</td>
                        </tr>
                        @if($order->end_date)
                        <tr>
                            <th>End Date</th>
                            <td>{{ date('d M, Y', strtotime($order->end_date)) }}</td>
                        </tr>
                        @endif


                    </table>
                </div>
            </div><!-- Nav tabs -->
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.orders.index') }}" class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">Edit Order</a>
            @else
            <a href="{{ route('admin.payments') }}" class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
            @endif
        </div>
    </div>
@stop