@component('mail::message')
# Hi Admin,
<br>
New User onboarded: {{$user->name}}.
<br>
<br>



<br>

Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>



<br>


Best regards,<br>
Team VaaGa
@endcomponent
