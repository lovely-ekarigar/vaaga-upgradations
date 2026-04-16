@component('mail::message')
# Dear {{$user->name}}
<br>
Thank you for choosing VaaGa Academy. We appreciate your interest in sharing your expertise and knowledge with our precious students. Our team will review your application, and we will contact you soon.<br>


Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>


If you have any query or require additional information, please feel free to contact us at info@vaagaacademy.com







<br>


Best regards,<br>
Team VaaGa
@endcomponent
