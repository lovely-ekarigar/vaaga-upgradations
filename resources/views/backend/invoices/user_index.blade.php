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
   @foreach($subscriptions as $key=>$item)
 @php $keyx++ @endphp
<tr>
<td>{{ $keyx }}</td>
<td>{{$item->updated_at->format('d M, Y | h:i A')}}</td>
<td>{{$appCurrency['symbol'].' '.$item->amount}} </td>
<td>
  <a class="btn btn-danger btn-sm" href="/user/student-view-invoice/{{$item->order_id}}/show/{{$item->id}}" target="_blank">@lang('labels.backend.invoices.fields.view')</a>
  
  <a class="btn btn-success btn-sm" href="/user/student-view-invoice/{{$item->order_id}}/download/{{$item->id}}" target="_blank">@lang('labels.backend.invoices.fields.download')</a>
 
</td>
</tr>
  @endforeach

 @foreach($orders as $key=>$item)
 @php $keyx++ @endphp
<tr>
<td>{{ $keyx }}</td>
<td>{{$item->updated_at->format('d M, Y | h:i A')}}</td>
<td>{{$appCurrency['symbol'].' '.$item->amount}} </td>
<td>
  <a class="btn btn-danger btn-sm" href="/user/student-view-invoice/{{$item->id}}/show" target="_blank">@lang('labels.backend.invoices.fields.view')</a>
  
  <a class="btn btn-success btn-sm" href="/user/student-view-invoice/{{$item->id}}/download" target="_blank">@lang('labels.backend.invoices.fields.download')</a>
 
</td>
</tr>
  @endforeach

</tbody>
</table>
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