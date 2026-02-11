@component('mail::message')
#Dear {{$user->name}}
<br>
Thank you for choosing VaaGa Academy.
<br>
<br>
We hope you are having a great learning experience with us. As part of our ongoing effort to improve your experience, we would love to hear from you about your experience.
<br>
<br>
We truely appreciate your time and effort in sharing your thoughts and suggestions. Your feedback is extremely valuable to us. Please visit student dashboard to give your feeedback or click the link to provide your feedback.<br>

<a href="{{env('APP_URL')}}/user/feedback/{{$batch_id}}">Click here</a><br>


If you have any query or further assistance, please feel free to reach out to us at info@vaagaacademy.com





<br>


Best regards,<br>
Team VaaGaAcademy
@endcomponent
