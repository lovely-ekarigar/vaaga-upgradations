@extends('frontend.layout.sub-master')

@section('title')
<title>Login | {{env('APP_NAME')}} </title>
@stop
@section('content')

<style type="text/css">
  .pwdView {
    position: absolute;
    right: 0px;
    top: 45px;
    width: 30px;
    height: 30px;
    cursor: pointer;
}
</style>

<main class="bg-cover bg-no-repeat bg-center effect-section" style="background-image: url(/public/newassets/img/bg/bg-banner-13.jpg);">
  <div class="mask bg-0000_ opacity-8"></div>
    <div class="particles-box" id="particles-box"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>

   <div class="container">
      <div class="row align-items-center justify-content-center min-vh-100">
         <div class="col-md-5 col-xl-4 py-12">
            <div class="card">
               <div class="card-body p-4">
                  <div class="pb-4 text-center">
                     <h3 class="mb-2">Login</h3>
                     <p>Sign in to your account to continue.</p>
                  </div>
                  <form action="/userlogin" method="post" id="loginForm">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			             @if(Session::has('message'))      
							<div class="form-group col-sm-12" style="text-align:center;">
							<div class="alert alert-danger">{!! Session::get('message') !!}</div>
							</div>
							@endif 
                      <div class="form-group mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email address">
                      </div>
                      <div class="form-group mb-3" style="position: relative;">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control pass-input" name="password" placeholder="Enter your password">

                        <i class="bi bi-eye pwdView"></i>
          
                      </div>
                      <div class="form-group mb-3" >
                        <div class="form-check">
                          <a class="forgot-link" href="/forgot/password">Forgot Password ?</a>
                        </div>
                      </div>
                      <div class="form-group mb-3">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="remember" value="1" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckDefault">Remember me 
                          </label>
                        </div>
                      </div>
                      <div class="pt-2">
                        <button class="btn btn-primary w-100" type="submit">Sign in</button>
                      </div>
                      
                      <div class="mt-3 text-center">
                        <small>Not registered?</small>
                        <a href="/userregister?redirect={{$red}}" class="small fw-700">Create account</a>
                      </div>
                    </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>

@stop

@section('page_js')
<script src="/public/newassets/vendor/particles/particles.min.js"></script>
    <script src="/public/newassets/vendor/particles/particles-app.js"></script><!-- Theme JS -->

    <script type="text/javascript">
      
      $(document).on("click",".pwdView",function(){
        $(this).toggleClass('bi-eye-slash');
        $(this).toggleClass('bi-eye');
        var elm = $(this).parent().find("input");
        if(elm.attr("type")=="text"){

          elm.attr("type","password");
        }else{
          elm.attr("type","text");
        }
      })

    </script>
@stop
