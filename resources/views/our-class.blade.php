<?php
use App\Models\Category;
use App\Models\Board;
use App\Models\Coupon;
use App\Models\Auth\User;
use App\Models\TeacherProfile;
?>
@extends('frontend.layout.sub-master')
@section('title')
<title>Best LIVE Classes For CBSE, ICSE, IIT,JEE & NEET | Join Us | {{env('APP_NAME')}}</title>


<meta name="description" content="Join VaaGa Academy live online classes for K12, CBSE, ICSE, IIT, JEE & NEET courses led by experienced master Teachers and Prepare for your exam more effectively. Enroll Now.">
<meta name="keywords" content="Online live learning platform for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Best LIVE Classes For CBSE, ICSE, IIT,JEE & NEET | Join Us | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Join VaaGa Academy live online classes for K12, CBSE, ICSE, IIT, JEE & NEET courses led by experienced master Teachers and Prepare for your exam more effectively. Enroll Now." />
    <meta property="og:url" content="{{URL::to('/our-classes')}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Best LIVE Classes For CBSE, ICSE, IIT,JEE & NEET | Join Us | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="Join VaaGa Academy live online classes for K12, CBSE, ICSE, IIT, JEE & NEET courses led by experienced master Teachers and Prepare for your exam more effectively. Enroll Now." />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/our-classes')}}">

@stop
@section('page_css')
<style type="text/css">
.text-whitex {
    color: #e9e9e9f2 !important;
}
@media  only screen and (max-width: 600px) {
        .mtp-65{
  margin-top: 65px !important;
}
}
</style>
@stop

@section('content')

  <main>
   <!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-65" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">

  {{-- <section class="bg-primary effect-section page-heading-pad bg-no-repeat bg-cover mtp-20" style="background-image: url(https://www.vaagaacademy.com/newassets/img/bg/bg-page-header.jpeg);"> --}}
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1">Our Classes</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item active">Transforming Students, Shaping their future!!</li>
              </ol>
            </div>
         </div>
      </div>
   </section>

  <section class="section">
   <div class="container">
      <div class="row gy-4 align-items-center">

         <div class="col-lg-6"><img src="https://www.vaagaacademy.com/newassets/img/about/about01.gif" title="" alt=""></div>
         <div class="col-lg-6">
            <h3 class="h1 mb-1">Private Classes</h3>
           
           <p class="mb-5 lead">Looking for Private classes? Choose your course and start your personalised learning journey with unmatched personal attention of Tutor.  We offer Live video classes for interactive and engaging learning experience at your own pace. Instant doubt clearing, no need to hesitate to ask any doubts from your Tutor. Enjoy your learning journey at the comfort of your home.</p>
         </div>


      </div>
   </div>
</section>


<section class="section  effect-section" style="background: #f9f9f9 !important;">
   <div class="container">
      <div class="row gy-4 align-items-center">
         <div class="col-lg-6">
            <h3 class="h1 mb-1">Group Classes</h3>
           
           <p class="mb-5 lead">Engaging and productive group classes with maximum up to 5 students. Our expert Tutors provides all the necessary guidance and teaching support to our students to achieve good results in your exams.</p>
         </div>


         <div class="col-lg-6" style="text-align:right;"><img src="https://www.vaagaacademy.com/newassets/img/about/3.png" title="" alt=""></div>
      </div>
   </div>
</section>


<section class="section bg-gray-100 effect-section">
   <div class="particles-box" id="particles-box-01"><canvas class="particles-js-canvas-el" width="1349" height="926" style="width: 100%; height: 100%;"></canvas></div>
   <div class="container">
      <div class="row align-items-center justify-content-between ">
         <div class="col-lg-6 my-3 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInLeft;">
            <h3 class="h2 text-warning"> Benefits of Online Learning </h3>
            <p class="lead  text-whitex" style="text-align: justify;">Classroom learning isn’t enough, need an expert mentor to assist you with a new study pattern and provide you in-depth understanding of the different concepts.</p>
             <ul class="list-type-03 mb-4 list-unstyled  text-whitex">
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Don’t have to step out of your home, simply log in to VaaGa Academy and enjoy hassle free learning with best Tutors.</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Notes and Assessments for all the topics available for later reference.</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Video recording of all the session for students to re-watch it later.</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Continuous evaluation by the Tutor.</li>
            </ul>

         </div>
         <div class="col-lg-6 my-3 wow fadeInRight text-center" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInRight;"><img src="https://www.vaagaacademy.com/newassets/img/about/4.png" style="background: white;" title="" alt=""></div>
      </div>
   </div>
</section>

</main>



@stop
@section('page_js')
<script src="/public/newassets/vendor/particles/particles.min.js"></script>
    <script src="/public/newassets/vendor/particles/particles-app.js"></script><!-- Theme JS -->
  
<script>


</script>


@stop
