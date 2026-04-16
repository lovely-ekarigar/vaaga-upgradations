@component('mail::message')
# Dear {{$teacher->first_name}}

We are excited to inform you that a demo has been scheduled. Kindly check below the demo details:<br><br>

# Date & Time: {{$demo->demo_date_time}}<br>
# Student's name: {{$user->first_name}} {{$user->last_name}}<br>
# Course: {{$course}}<br>
<br><br>
Kindly login to your dashboard to get demo joining link <a href="{{env('APP_URL')}}/user/dashboard">Login Now</a>.<br>

You can reach out to us anytime in case of any query at info@vaagaacademy.com



<br>


Best regards,<br>
Team VaaGaAcademy
@endcomponent
