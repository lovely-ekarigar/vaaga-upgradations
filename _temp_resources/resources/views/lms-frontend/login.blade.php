@extends('frontend.layout.login-master')
@section('content')
<div class="row">

<div class="col-md-6 login-bg">
<div class="owl-carousel login-slide owl-theme">
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
<div class="w-100">
<div class="img-logo">
<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
<div class="back-home">
<a href="/">Back to Home</a>
</div>
</div>
<h1>Sign into Your Account</h1>
<form action="">
<div class="form-group">
<label class="form-control-label">Email</label>
<input type="email" class="form-control" placeholder="Enter your email address">
</div>
<div class="form-group">
<label class="form-control-label">Password</label>
<div class="pass-group">
<input type="password" class="form-control pass-input" placeholder="Enter your password">
<span class="feather-eye toggle-password"></span>
</div>
</div>
<div class="forgot">
<span><a class="forgot-link" href="/forgot-password">Forgot Password ?</a></span>
</div>
<div class="remember-me">
<label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
<input type="checkbox" name="radio">
<span class="checkmark"></span>
</label>
</div>
<div class="d-grid">
<button class="btn btn-primary btn-start" type="submit">Sign In</button>
</div>
</form>
</div>
</div>
<div class="google-bg text-center">
<div class="sign-google">
</div>
<p class="mb-0">New User ? <a href="/registration">Create an Account</a></p>
</div>
</div>

</div>
</div>
@stop