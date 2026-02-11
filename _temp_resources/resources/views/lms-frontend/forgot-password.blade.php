@extends('frontend.layout.login-master')
@section('content')
<div class="row">

<div class="col-md-6 login-bg">
<div class="owl-carousel login-slide owl-theme aos" data-aos="fade-up">
<div class="welcome-login">
<div class="login-banner">
<img src="assets/img/login-img.png" class="img-fluid" alt="Logo">
</div>
<div class="mentor-course text-center">
<h2>Welcome to <br>DreamsLMS Courses.</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
</div>
</div>
<div class="welcome-login">
<div class="login-banner">
<img src="assets/img/login-img.png" class="img-fluid" alt="Logo">
</div>
<div class="mentor-course text-center">
<h2>Welcome to <br>DreamsLMS Courses.</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
</div>
</div>
<div class="welcome-login">
<div class="login-banner">
<img src="assets/img/login-img.png" class="img-fluid" alt="Logo">
</div>
<div class="mentor-course text-center">
<h2>Welcome to <br>DreamsLMS Courses.</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
</div>
</div>
</div>
</div>

<div class="col-md-6 login-wrap-bg">

<div class="login-wrapper">
<div class="loginbox">
<div class="img-logo">
<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
<div class="back-home">
<a href="/">Back to Home</a>
</div>
</div>
<h1>Forgot Password ?</h1>
<div class="reset-password">
<p>Enter your email to reset your password.</p>
</div>
<form action="">
<div class="form-group">
<label class="form-control-label">Email</label>
<input type="email" class="form-control" placeholder="Enter your email address">
</div>
<div class="d-grid">
<button class="btn btn-start" type="submit">Submit</button>
</div>
</form>
</div>
</div>

</div>
</div>

@stop