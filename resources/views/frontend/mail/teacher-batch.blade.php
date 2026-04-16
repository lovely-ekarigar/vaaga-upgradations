@component('mail::message')
# Dear {{$user->name}}
<br>
We are excited to inform you that you have been assigned as Tutor for the new batch <strong>{{$batch}}</strong> of course <strong>{{$course}}</strong>. We appreciate your commitment to providing quality education and we are confident that your expertise will greatly benefit the students.
<br>
<br>
 <a href="{{env('APP_URL')}}/user/dashboard">Visit Tutor Dashboard for more details.</a>
<br>
<br>
In case you need any assistance or have any questions, please feel free to contact us at info@vaagaacademy.com
<br>
<br>
Good luck with the upcoming sessions!



Best regards,<br>
Team VaaGa
@endcomponent
