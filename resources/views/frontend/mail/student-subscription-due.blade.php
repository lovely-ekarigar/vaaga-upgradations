@component('mail::message')
#Dear {{$order->user->name}}

We would like to inform you about your pending subscription for <strong>{{$items}}</strong> for the month of <strong>{{date("d M Y",strtotime("-1 Months",strtotime($order->end_date)))}} - {{date("d M Y",strtotime($order->end_date))}}</strong>. Kindly renew at the earliest for uninterrepted learning.<br><br>

Visit student dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>

If you have any query or need further assistance, please feel free to reach out to us at info@vaagaacademy.com.<br><br>

Best regards,<br>
Team VaaGa




<br>

@endcomponent
