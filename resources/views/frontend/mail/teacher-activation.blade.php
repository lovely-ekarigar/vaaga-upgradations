@component('mail::message')
#Dear {{$user->name}}

#Congratulations!

A warm welcome to VaaGa Academy. Your registration process is complete. Your account is now active. To explore the Tutor platform, visit: www.vaagaacademy.com<br>


Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>


Get ready to embark on an exciting journey. Happy Teaching!<br>

In case you need any assistance or have any questions, please feel free to contact us at info@vaagaacademy.com


Best regards,<br>
Team VaaGa
@endcomponent
