<?php
use App\Models\Resource;
use App\Models\Category;
use App\Models\Course;
?>
@section('header')

<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="description" content="@yield('seo_des')">
    <meta name="keywords" content="@yield('seo_key')"/>
 <link rel="icon" sizes="16x16" href="/nglive/images/favicon.png">
      <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="images/favicon.png">

    <!-- inject:css -->
    <link rel="stylesheet" href="/nglive/css/bootstrap.min.css">
    <link rel="stylesheet" href="/nglive/css/line-awesome.css">
    <link rel="stylesheet" href="/nglive/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/nglive/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/nglive/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/nglive/css/fancybox.css">
    <link rel="stylesheet" href="/nglive/css/animated-headline.css">
    <link rel="stylesheet" href="/nglive/css/plyr.css">
    <link rel="stylesheet" href="/nglive/css/jquery-te-1.4.0.css">
    <link rel="stylesheet" href="/nglive/css/style.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <style type="text/css">
        
#app {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
#toolbar {
  display: flex;
  align-items: center;
  background-color: #555;
  color: #fff;
  padding: 0.5em;
}
#toolbar button,
#page-mode input {
  color: currentColor;
  background-color: transparent;
  font: inherit;
  border: 1px solid currentColor;
  border-radius: 3px;
  padding: 0.25em 0.5em;
}
#toolbar button:hover,
#toolbar button:focus,
#page-mode input:hover,
#page-mode input:focus {
  color: lightGreen;
}
#page-mode {
  display: flex;
  align-items: center;
  padding: 0.25em 0.5em;
}
#pager{
        position: absolute;
    right: 9px;
}
#viewport-container {
  flex: 1;
  background: #eee;
  overflow: auto;
}
#viewport {
  width: 90%;
  margin: 0 auto;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}
#viewport > div {
  text-align: center;
  max-width: 100%;
}
#viewport canvas {
  width: 100%;
  box-shadow: 0 2px 5px gray;
}
.lecture-viewer-container{
   // min-height: 40%;
}
#scroll-top,ul.social-icons li a i {
    padding-top: 10px;
    }
.close-text{
    position: absolute;
    right: 5px;
    top: 5px;
    font-size: 31px;
    color: #dc3545;
    cursor: pointer;
}

.lecture-viewer-text-bodyx{
    position: absolute;
    top: 0;
    background: #fff;
    width: 100%;
    text-align: center;
    padding: 60px 2px;
    z-index: 999;
}
.lecture-viewer-text-body{
   
    text-align: center;
}
.lecture-video-detail-body {
    padding: 20px 6px 29px 6px;
}



/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
 .lecture-viewer-text-body {
 
    padding: 1px;
  
}
.mobile-course-menu{
    display: block;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
.lecture-viewer-text-body {
 
    padding: 1px;
  
}
.mobile-course-menu{
    display: block;
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
 .lecture-viewer-text-body {
 
   padding: 60px 2px;
  
}
.mobile-course-menu{
    display: none;
}
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
 .lecture-viewer-text-body {
 
    padding: 60px 2px;
  
}
.mobile-course-menu{
    display: none;
}
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
   .lecture-viewer-text-body {
 
   padding: 60px 2px;
  
}
.mobile-course-menu{
    display: none;
}

}




    
    </style>
    <!-- end inject -->
</head>
<body>

<!-- start cssload-loader -->
<div class="preloader">
    <div class="loader">
        <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
    </div>
</div>
<!-- end cssload-loader -->

@show

@yield('content')


@section('footer')



<!-- template js files -->
<script src="/nglive/js/jquery-3.4.1.min.js"></script>
<script src="/nglive/js/bootstrap.bundle.min.js"></script>
<script src="/nglive/js/bootstrap-select.min.js"></script>
<script src="/nglive/js/owl.carousel.min.js"></script>
<script src="/nglive/js/isotope.js"></script>
<script src="/nglive/js/waypoint.min.js"></script>
<script src="/nglive/js/jquery.counterup.min.js"></script>
<script src="/nglive/js/fancybox.js"></script>
<script src="/nglive/js/plyr.js"></script>
<script src="/nglive/js/datedropper.min.js"></script>
<script src="/nglive/js/emojionearea.min.js"></script>
<script src="/nglive/js/jquery-te-1.4.0.min.js"></script>
<script src="/nglive/js/jquery.MultiFile.min.js"></script>
<script src="/nglive/js/main.js"></script>

    <script>
        const player = new Plyr('#player');
$(document).on("click",".close",function(){
    player.pause();
})
//document.addEventListener('contextmenu', event => event.preventDefault());

       
    </script>

 <script type="text/javascript">
    
</script>
@yield('page_js')
</body>
</html>



@show