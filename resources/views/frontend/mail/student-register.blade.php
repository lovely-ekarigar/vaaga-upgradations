@component('mail::message')
Dear {{$user->name}}
<br>
Congratulations!<br>

A warm welcome to VaaGa Academy. Your registration process is completed. Your account is now active. To explore the Student platform, visit: www.vaagaacademy.com<br>

Get ready to embark on an exciting journey. Happy Learning!<br>


Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>


In case you need any assistance or have any questions, please feel free to contact us at info@vaagaacademy.com





<br>


Best regards,<br>
Team VaaGa
@endcomponent
