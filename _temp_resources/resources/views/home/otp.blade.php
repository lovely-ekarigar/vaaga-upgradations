@extends('frontend.layout.sub-master')
@section('title')
<title>Verify Phone | {{env('APP_NAME')}} </title>
@stop
@section('content')


        <!-- Section -->
        <section class="bg-cover bg-no-repeat" style="background-image: url(/public/newassets/img/bg/bg-banner-13.jpg);">
          <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100">
              <div class="col-md-6 col-lg-5 col-xl-4 py-12">
                <div class="card">
                  <div class="card-body">
                    <div class="pb-4 text-center">
                      <h3 class="mb-2">OTP</h3>
                      <p>OTP has been sent to your phone {{sprintf("%s******%s",
              substr($user->phone, 0, 2),
              substr($user->phone, 8))}}</p>
                    </div>
                    @if(Session::has('error'))

<div class='alert alert-danger'>
    {{Session::get('error')}}
</div>
@endif
                    <form method="post">
    {{csrf_field()}}
                      <div class="form-group mb-3">
                        <input type="number" name="otp" class="form-control text-center" placeholder="Enter OTP" >
                      </div>
                      <div class="form-group mb-3 text-end">
                        <div class="form-check">
                          <a class="forgot-link" href="#">Resend OTP</a>
                        </div>
                      </div>
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Continue</button>
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