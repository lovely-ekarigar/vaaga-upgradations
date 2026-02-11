
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
@yield('title')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="shortcut icon" type="image/x-icon" href="https://mentortle.com/storage/logos/1666214871-new-project-2.png">

<link rel="stylesheet" href="/public//frontend/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="/public//frontend/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="/public//frontend/assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="/public//frontend/assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="/public//frontend/assets/css/owl.theme.default.min.css">

<link rel="stylesheet" href="/public//frontend/assets/plugins/slick/slick.css">
<link rel="stylesheet" href="/public//frontend/assets/plugins/slick/slick-theme.css">

<link rel="stylesheet" href="/public//frontend/assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="/public//frontend/assets/plugins/aos/aos.css">

<link rel="stylesheet" href="/public//frontend/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<style>
    .all-category .btn-primary {

    border: 1px solid #b4a7f552;
    }
</style>
</head>
<body>
	<div class="main-wrapper">

 @include("frontend.include.user-header")
 @yield('content')
 @include("frontend.include.footer")


</div>

<script data-cfasync="false" src="/public/"></script>
<script src="/public//frontend/assets/js/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script src="/public//frontend/assets/js/bootstrap.bundle.min.js"></script>

<script src="/public//frontend/assets/js/jquery.waypoints.js"></script>
<script src="/public//frontend/assets/js/jquery.counterup.min.js"></script>
 
<script src="/public//frontend/assets/plugins/select2/js/select2.min.js"></script>

<script src="/public//frontend/assets/js/owl.carousel.min.js"></script>

<script src="/public//frontend/assets/plugins/slick/slick.js"></script>

<script src="/public//frontend/assets/plugins/aos/aos.js"></script>

<script src="/public//frontend/assets/js/script.js"></script>
@yield('page_js')
</body>

</html>