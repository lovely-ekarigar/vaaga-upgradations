@component('mail::message')
# Dear {{$user->name}}

We would like to confirm that we have received your payment of ₹{{$amount}}. Thank you for choosing VaaGa Academy.You can now access your purchased courses and begin your exciting learning journey.<br><br>

Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>
If you have any query or further assistance, please feel free to reach out to us at info@vaagaacademy.com



<br>


Best regards,<br>
Team VaaGa
@endcomponent
