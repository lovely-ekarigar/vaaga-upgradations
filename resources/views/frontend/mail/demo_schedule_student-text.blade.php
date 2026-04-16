
**Hello {{$user->first_name}}**

Your demo has been scheduled. Kindly check below the demo details:<br>
Demo Date & Time: {{$demo->datetime}}<br><br>

Teacher name: {{$teacher->first_name}} {{$teacher->last_name}}<br>

Course :{{$course}}<br>

Kindly login to your dashboard to get demo joining link or you can click the below link.
<br>





Thanks,<br>
{{ config('app.name') }}

