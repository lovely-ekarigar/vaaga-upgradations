@extends('frontend.layout.sub-master')
@section('title')
<title>VaaGa Academy - Online Olympiad Coaching for Students | About Us</title>

<meta name="description" content="Learn more about VaaGa Academy's mission to provide expert online Olympiad coaching for Class 2–8 students. We specialize in Maths, Science & English coaching for IMO, NSO & IEO exams.">
<meta name="keywords" content="About VaaGa Academy, Online Olympiad coaching, Olympiad coaching mission, IMO NSO IEO coaching, Maths Science English coaching, Best Olympiad academy, Online learning platform, Olympiad preparation India">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="VaaGa Academy - Online Olympiad Coaching for Students | About Us" />
    <meta property="og:description" content="Learn more about VaaGa Academy's mission to provide expert online Olympiad coaching for Class 2–8 students. We specialize in Maths, Science & English coaching for IMO, NSO & IEO exams." />
    <meta property="og:url" content="{{URL::to('/about')}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="VaaGa Academy - Online Olympiad Coaching for Students | About Us" />
<meta name="twitter:description" content="Learn more about VaaGa Academy's mission to provide expert online Olympiad coaching for Class 2–8 students. We specialize in Maths, Science & English coaching for IMO, NSO & IEO exams." />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/about')}}">

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
   {{-- <section class="bg-primary effect-section page-heading-pad bg-no-repeat bg-cover" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8 ">
               <h1 class="text-white h1">About Us</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
              </ol>
            </div>
         </div>
      </div>
   </section> --}}
   <section class="bg-primary effect-section page-heading-pad mtp-46" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h2 class="text-white h1">About Us</h2>
               <ol class="breadcrumb breadcrumb-light">
                  <li class="breadcrumb-item"><a href="/">Home</a></li>
                  <li class="breadcrumb-item active">About Us</li>
               </ol>
            </div>
         </div>
      </div>
   </section>
   <!-- End Home Banner --><!-- Section -->
   
   <section class="section">
   <div class="container">
      <div class="row align-items-center justify-content-between ">
         <div class="col-lg-6 my-3 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInLeft;">
            <h3 class="h2 pt-2">About {{env('PROJECT_NAME')}}</h3>
            <p class="lead" style="text-align: justify;">Our mission is to provide best customized learning experience to students in the safe environment of home. We believe all the students deserve the best of learning to enhance their foundation and achieve their goals and good result</p>
            <p class="lead" style="text-align: justify;">VaaGa Academy is here to help students with the personalised and effective academic plan for the session to achieve their goal by constantly monitoring students’ progress and guiding them through in the right direction.</p>
                    <p class="lead" style="text-align: justify;">Our Private classes provides unmatched personal attention to student with better concept clarity and instant doubt clearing during the class to ensure good result.</p>
             <!-- <ul class="list-type-03 mb-4 list-unstyled">
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Personalised and interactive Live 1:1 online class</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Experienced and Well qualified tutors</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Tutor replacement guarantee</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Best education at comfort and safety of your home</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Regular test to track the progress</li>
               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Regular progress reporting to parents</li>
            </ul> -->
            <a class="btn btn-primary" href="/#demo">Book Free Demo</a>
                        <a class="btn btn-warning" href="/contact">Contact Us</a>

         </div>
         <div class="col-lg-6 my-3 wow fadeInRight text-center" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInRight;"><img src="{{asset('newassets/img/about/about01.png')}}" title="" alt=""></div>
      </div>
   </div>
</section>
   
</main>

@stop