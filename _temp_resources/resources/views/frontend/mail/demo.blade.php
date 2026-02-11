@component('mail::message')
#Hello {{$request->name}}

Thank you for booking a demo with us. We appreciate your interest in our courses. Our team will reach out to you shortly to schedule the demo.<br>

@if($request->password)
#Please use the below credentials to login:<br>

User Id:  {{ $request->email }}<br>
Password :  {{ $request->password }}<br>
@else

#Please use the credentials sent you before via email.
@endif

<a href="{{env('APP_URL')}}/user/dashboard">Click here to login </a>
<br>
<br>
In case of any query, please feel free to reach out to us at info@vaagaacademy.com<br>


Best regards,<br>
Team VaaGa

@endcomponent
