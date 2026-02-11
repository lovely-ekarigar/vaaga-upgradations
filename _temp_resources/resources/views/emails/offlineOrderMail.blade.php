@component('mail::message')
#Hello {{auth()->user()->name}}

Congratulations! We are excited to welcome you at VaaGa Academy. Your recent purchase of course is completed. You now have full access to our comprehensive learning materials, and we're confident that you'll find the content engaging and valuable.<br><br>
Order Reference No. {{$content['reference_no']}}
@foreach($content['items'] as $item)
#{{$item['number']}}. {{$item['name']}} :{{'₹'.$item['price']}}
@endforeach

<br>
@if($content['gst'])
@if($content['gst']>0)
#Total GST(18%) :₹{{$content['gst']}}
@endif
@endif
@if($content['discount'])
@if($content['discount']>0)
#Total Discount :₹{{$content['discount']}}
@endif
@endif
#Total Amount Paid :₹{{$content['total']}}
<br>

Visit  dashboard for more details.<br>
<a href="{{env('APP_URL')}}/user/dashboard">Click here</a><br>
<br>
Get ready to embark on an exciting learning journey. Happy studying!<br>
In case you need any assistance or have any questions, please feel free to contact us at info@vaagaacademy.com<br>


<br>


Best regards,<br>
Team VaaGa
@endcomponent
