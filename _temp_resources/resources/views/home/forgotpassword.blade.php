@extends('frontend.layout.sub-master')

@section('title')
<title>Forgot Password | {{env('APP_NAME')}} </title>
@stop

@section('content')


        <!-- Section -->
        <section class="bg-cover bg-no-repeat" style="background-image: url(/public/newassets/img/bg/bg-banner-13.jpg);">
          <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100">
              <div class="col-md-6 col-lg-5 col-xl-4 py-5">
                <div class="card">
                  <div class="card-body">
                    <div class="pb-4 text-center">
                      <h3 class="mb-2">Reset password</h3>
                      <p>Enter your email to reset your password.</p>
                    </div>
                           @if(Session::has('success'))      
<div class="form-group col-sm-12" style="text-align:center;">
<div class="alert alert-success">{!! Session::get('success') !!}</div>
</div>
@endif

             @if(Session::has('flash_message'))      
<div class="form-group col-sm-12" style="text-align:center;">
<div class="alert alert-danger">{!! Session::get('flash_message') !!}</div>
</div>
@endif
                     <form action="" method="post">
       {{csrf_field()}}
                      <div class="form-group mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email address" autofocus>
                      </div>
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
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