@extends('frontend.layout.sub-master')
@section('title')
<title>Invoices | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2">Invoices</h3>
              <h6 class="text-body fw-500 mb-3"></h6>
                <div>
                    <table class="table table-nowrap mb-0">
<thead>
<tr>
 <th>@lang('labels.general.sr_no')</th>
         <th>Course</th>
 <th>Cycle No.</th>
 <th>@lang('labels.backend.invoices.fields.order_date')</th>
 <th>@lang('labels.backend.invoices.fields.amount')</th>
  @if( request('show_deleted') == 1 )
 <th>&nbsp; @lang('strings.backend.general.actions')</th>
  @else
<th>&nbsp; @lang('strings.backend.general.actions')</th>
  @endif
  </tr>
</thead>
<tbody>
   @php $keyx=0 @endphp
   @foreach(($paidInvoiceRows ?? collect()) as $item)
   @php $keyx++ @endphp
<tr>
<td>{{ $keyx }}</td>
<td>
  {{ $item->course_names ?: '' }}
  @if(!empty($item->remarks))
    {{ $item->remarks }}
  @endif
</td>
<td>{{ $item->cycle_no }}</td>
<td>
  @if($item->date)
    {{ timezone()->convertToLocal(\Carbon\Carbon::parse($item->date), 'd M, Y | h:i A') }}
  @else
    -
  @endif
</td>
<td>{{ $appCurrency['symbol'].' '.intval($item->amount) }}</td>
<td>
  @if($item->has_invoice)
    @if($item->subscription_id)
      <a class="btn btn-danger btn-sm" href="{{ route('admin.student.invoice.subscription', ['oid' => $item->order_id, 'type' => 'show', 'sid' => $item->subscription_id]) }}" target="_blank">@lang('labels.backend.invoices.fields.view')</a>
      <a class="btn btn-success btn-sm" href="{{ route('admin.student.invoice.subscription', ['oid' => $item->order_id, 'type' => 'download', 'sid' => $item->subscription_id]) }}" target="_blank">@lang('labels.backend.invoices.fields.download')</a>
    @else
      <a class="btn btn-danger btn-sm" href="{{ route('admin.student.invoice', ['oid' => $item->order_id, 'type' => 'show']) }}" target="_blank">@lang('labels.backend.invoices.fields.view')</a>
      <a class="btn btn-success btn-sm" href="{{ route('admin.student.invoice', ['oid' => $item->order_id, 'type' => 'download']) }}" target="_blank">@lang('labels.backend.invoices.fields.download')</a>
    @endif
  @else
    <span class="text-muted">Invoice not generated</span>
  @endif
</td>
</tr>
   @endforeach

</tbody>
</table>

@if(($activeSubscriptionRows ?? collect())->count() > 0)
<div class="mt-5">
  <h4 class="mb-3">Active Subscriptions (Upcoming Cycles)</h4>
  <table class="table table-nowrap mb-0">
    <thead>
      <tr>
        <th>@lang('labels.general.sr_no')</th>
        <th>Course</th>
        <th>Order ID</th>
        <th>Upcoming Cycle No.</th>
        <th>Expected Date</th>
        <th>@lang('labels.backend.invoices.fields.amount')</th>
        {{-- <th>Status</th> --}}
      </tr>
    </thead>
    <tbody>
      @php $activeKey=0 @endphp
      @foreach(($activeSubscriptionRows ?? collect()) as $row)
        @php $activeKey++ @endphp
        <tr>
          <td>{{ $activeKey }}</td>
          <td>
            {{ $row->course_names ?: '' }}
            @if(!empty($row->remarks))
              <br><small class="text-muted">{{ $row->remarks }}</small>
            @endif
          </td>
          <td>ORD-{{ $row->order_id }}</td>
          <td>{{ $row->cycle_no }}</td>
          <td>
            @if($row->date)
              {{\Carbon\Carbon::parse($row->date)->format('d M, Y')}}
            @else
              -
            @endif
          </td>
             <td>{{$appCurrency['symbol'].' '.intval($row->amount)}}</td>
          {{-- <td><span class="badge badge-warning">Upcoming</span></td> --}}
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
                </div>
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop