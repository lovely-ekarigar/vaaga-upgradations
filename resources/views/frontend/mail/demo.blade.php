@component('mail::message')
Hello **{{ $request->name }}**,

Thank you for booking a demo with us. We appreciate your interest in our courses.  
Our team will reach out to you shortly to schedule the demo.


@if($request->password)

**Please use the below credentials to login:**

**User Id:** {{ $request->email }}

**Password:** {{ $request->password }}

@else

**Please use the credentials sent to you before via email.**

@endif

[Click here to login]({{ env('APP_URL') }}/user/dashboard)

In case of any query, please feel free to reach out to us at  
[info@vaagaacademy.com](mailto:info@vaagaacademy.com)

Best regards,  
**Team VaaGa**
@endcomponent




