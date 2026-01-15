<?php
$url=base64_decode($sid);
header("Location:".$url);
?>
<!-- <html>
    <head>
        <title>Online Live Class</title>
    </head>
    <body style="margin:0px;">
<iframe src="{{$url}}" style="height:100%;width:100%;border:0px;" allow="camera *;microphone *;fullscreen *" ></iframe>
</body>

</html> -->
