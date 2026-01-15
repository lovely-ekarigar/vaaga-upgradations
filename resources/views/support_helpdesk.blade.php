@extends('frontend.layout.sub-master')
@section('title')
<title>Support and Helpdesk | {{env('APP_NAME')}}</title>
@stop
@section('page_css')
<style>
    .bg-gray-100 {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-gray-100-rgb), var(--bs-bg-opacity)) !important;
}
@media  only screen and (max-width: 600px) {
        .mtp-46{
  margin-top: 46px !important;
}
}
</style>
@stop
@section('content')

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-46" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8 ">
               <h1 class="text-white h1">Support and Helpdesk</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Support and Helpdesk</li>
              </ol>
            </div>
         </div>
      </div>
   </section>
   <!-- End Home Banner --><!-- Section -->


   <section class="section bg-gray-100">
      <div class="container">
         <div class="row section-heading justify-content-center text-center wow fadeInUp" data-wow-duration=".4s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.4s; animation-delay: 0.1s; animation-name: fadeInUp;">
            
         </div>
         <div class="row gy-4">
            <div class="col-md-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInUp;">
               <a href="tel:9820380469"><div class="media bg-body rounded py-5 px-4 hover-top shadow">
                  <div class="icon icon-xl text-primary bg-gray-100 rounded-circle dots-icon"><i class="bi bi-phone"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="media-body ps-4">
                     <h6>Mobile Number</h6>
                     <p class="m-0">+91 9820380469</p>
                  </div>
               </div></a>
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInUp;">
             <a href="mailto:info@vaagaacademy.com"><div class="media bg-body rounded py-5 px-4 hover-top shadow">
                  <div class="icon icon-xl text-primary bg-gray-100 rounded-circle dots-icon"><i class="bi bi-envelope"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="media-body ps-4">
                     <h6>Emails</h6>
                     <p class="m-0">info@vaagaacademy.com</p>
                  </div>
               </div></a>  
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInUp;">
               <div class="media bg-body rounded py-5 px-4 hover-top shadow">
                  <div class="icon icon-xl text-primary bg-gray-100 rounded-circle dots-icon"><i class="bi bi-chat-square-text"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="media-body ps-4">
                     <h6>Live Chat</h6>
                     <p class="m-0"><a href="javascript:void(Tawk_API.toggle())" >Click Here</a></p>
                  </div>
               </div>
            </div>
           
         </div>
      </div>
   </section>

  
   
  
   
</main>

@stop