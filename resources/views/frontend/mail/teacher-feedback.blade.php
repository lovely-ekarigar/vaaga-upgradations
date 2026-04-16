@component('mail::message')
# Dear {{$user->name}}
<br>
Thank you for choosing VaaGa Academy.<br>

We hope you are having a great teaching experience with us. Please provide the feedback of assigned students under your guidence.<br>

Your feedback is extremely valuable for students to improve their performance. Please visit Tutor dashboard to give your feeedback. <br>


Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>


If you have any query or further assistance, please feel free to reach out to us at info@vaagaacademy.com








<br>


Best regards,<br>
Team VaaGa
@endcomponent
