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
<div class="img-logo">
<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
<div class="back-home">
<a href="/">Back to Home</a>
</div>
</div>
<h1>Sign up</h1>
<form action="">
<div class="form-group">
<label class="form-control-label">Full Name</label>
<input type="text" class="form-control" placeholder="Enter your Full Name">
</div>
<div class="form-group">
<label class="form-control-label">Email</label>
<input type="email" class="form-control" placeholder="Enter your email address">
</div>
<div class="form-group">
<label class="form-control-label">Password</label>
<div class="pass-group" id="passwordInput">
<input type="password" class="form-control pass-input" placeholder="Enter your password">
<span class="toggle-password feather-eye"></span>
<span class="pass-checked"><i class="feather-check"></i></span>
</div>
<div class="password-strength" id="passwordStrength">
<span id="poor"></span>
<span id="weak"></span>
<span id="strong"></span>
<span id="heavy"></span>
</div>
<div id="passwordInfo"></div>
</div>
<div class="form-check remember-me">
<label class="form-check-label mb-0">
<input class="form-check-input" type="checkbox" name="remember"> I agree to the <a href="term-condition.html">Terms of Service</a> and <a href="privacy-policy.html">Privacy Policy.</a>
</label>
</div>
<div class="d-grid">
<button class="btn btn-primary btn-start" type="submit">Create Account</button>
</div>
</form>
</div>
<div class="google-bg text-center">
<p class="mb-0">Already have an account? <a href="/login">Sign in</a></p>
</div>
</div>

</div>
</div>

@stop