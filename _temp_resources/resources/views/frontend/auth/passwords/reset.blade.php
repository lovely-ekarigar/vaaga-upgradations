
@extends('frontend.layout.sub-master')

@section('title')
<title>Reset Password | {{env('APP_NAME')}} </title>
@stop

@section('content')
<style type="text/css">
  .form-control[readonly] {
    background: #f1f1f1;
}
.pwdView {
    position: absolute;
    right: 0px;
    top: 45px;
    width: 30px;
    height: 30px;
    cursor: pointer;
}
</style>

        <!-- Section -->
        <section class="bg-cover bg-no-repeat effect-section" style="background-image: url(/public/newassets/img/bg/bg-banner-13.jpg);">
            <div class="mask bg-0000_ opacity-8"></div>
    <div class="particles-box" id="particles-box"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>
          <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100">
              <div class="col-md-6 col-lg-5 col-xl-4 pt-12 pb-5">
                <div class="card">
                  <div class="card-body">
                    <div class="pb-4 text-center">
                      <h3 class="mb-2">Reset Password</h3>
                      <p>Enter your email to reset your password.</p>
                    </div>
                           <form action="" method="post">
    {{csrf_field()}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="list-inline list-style-none">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{ html()->hidden('token', $token) }}
                      <div class="form-group mb-3">
                        <label class="form-label">Email address</label>
                        {{ html()->email('email',old('email',$email))
->class('form-control')
->placeholder(__('validation.attributes.frontend.email'))
->attribute('maxlength', 191)
->attribute('readonly', 'readonly')
->required() }}
                      </div>
                      <div class="form-group mb-3" style="position: relative;">
                        <label class="form-label">Password</label>
                         {{ html()->password('password')
    ->class('form-control')
    ->placeholder(__('validation.attributes.frontend.password'))
    ->required() }}
    <i class="bi bi-eye pwdView"></i>
                      </div>
                       <div class="form-group mb-3" style="position: relative;">
                        <label class="form-label">Confirm Password</label>
                         {{ html()->password('password_confirmation')
        ->class('form-control')
        ->placeholder(__('validation.attributes.frontend.password_confirmation'))
        ->required() }}
        <i class="bi bi-eye pwdView"></i>
                      </div>
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Reset Now</button>
                      </div>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End section -->


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