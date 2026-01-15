
Hello <strong>{{$teacher->first_name}}</strong>
<br>
Your demo has been scheduled. Kindly check below the demo details:<br>
Demo Date & Time: <strong>{{$demo->demo_date_time}}</strong>
<br>
Student name: <strong>{{$user->first_name}} {{$user->last_name}}</strong>
<br>
Instruction :<strong>{{$demo->instructions}}</strong>
<br>
Kindly login to your dashboard to get demo joining link.
<br>





Thanks,<br>
{{ config('app.name') }}

