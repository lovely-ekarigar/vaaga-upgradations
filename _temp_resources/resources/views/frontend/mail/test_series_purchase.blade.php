@component('mail::message')
# Dear Parent,

Greetings from **VaaGa Academy**!  

We are pleased to confirm your child's enrollment in:  
**{{ $course->title }}**

At VaaGa Academy, we believe in one rule for success — **practice a lot!**  
Take the mock tests and practice for success in Olympiad exams.  
All mock tests are available on your dashboard.

@component('mail::button', ['url' => env('APP_URL').'/user/my-test'])
Access Test Series
@endcomponent

We’re excited to support you on this learning journey and help build a strong foundation.  

If you have any questions, feel free to reach out — we’re always happy to assist!  

Warm regards,  
**Team VaaGa Academy**  

@endcomponent
