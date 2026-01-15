<?php
   $meetingNumber = '84724223719';
   $meetingPassword = '123456';
   $username = 'Babita Second';
   $useremail = 'babita748893@gmail.com';
   $role = '1';

?>

<!DOCTYPE html>

<head>
    <title>MentorTLE Connect</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.1.1/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.1.1/css/react-select.css" />
    <link type="text/css" rel="stylesheet" href="css/zoom-sdk-example.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <style type="text/css">
        .sharee-sharing-indicator, .meeting-info-container, .zmu-toast-container{
            display: none !important;
        }

    </style>

</head>

<body>

  
<script type="text/javascript">
    
    var MEETING_NUMBER='<?=$meetingNumber?>';
    var MEETING_PASSWORD='<?=$meetingPassword?>';
    var USERNAME='<?=$username?>';
    var USEREMAIL='<?=$useremail?>';
    var MEETING_ROLE='<?=$role?>';

</script>
    <script src="https://source.zoom.us/2.1.1/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.1.1/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.1.1/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.1.1/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.1.1/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.1.1.min.js"></script>
    <script src="js/zoom.js"></script>

</body>

</html>