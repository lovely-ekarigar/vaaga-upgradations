@component('mail::message')
# Dear {{$user->first_name}}

We are excited to inform you that your demo has been scheduled. Kindly check below the demo details:<br><br>

# Date & Time: {{$demo->demo_date_time}}<br>
# Tutor's name: {{$teacher->first_name}} {{$teacher->last_name}}<br>
# Course: {{$course}}

Kindly login to your dashboard to get demo joining link or you can click the below link <a href="{{env('APP_URL')}}/user/dashboard">Login Now</a>.<br>

You can reach out to us anytime in case of any query at info@vaagaacademy.com



<br>


Best regards,<br>
Team VaaGaAcademy
@endcomponent
