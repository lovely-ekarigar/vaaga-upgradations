<?php

use App\Models\Category;
use App\Models\Board;
use App\Models\Coupon;
use App\Models\Auth\User;
use App\Models\TeacherProfile;
use Illuminate\Support\Str;
?>
@extends('frontend.layout.sub-master')
 
 

@section('title')
<title>Math & Science Olympiad Online Classes | Expert Coaching </title>
<meta name="description" content="Join expert-led Math & Science Olympiad online classes. Get structured lessons, practice tests & strategies to boost your score. Enroll now for top results! 🚀">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Math & Science Olympiad Online Classes | Expert Coaching" />
    <meta property="og:description" content="Join expert-led Math & Science Olympiad online classes. Get structured lessons, practice tests & strategies to boost your score. Enroll now for top results! 🚀" />
    <meta property="og:url" content="{{URL::to('/')}}" />
    <meta property="og:site_name" content="Math & Science Olympiad Online Classes | Expert Coaching" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Math & Science Olympiad Online Classes | Expert Coaching" />
<meta name="twitter:description" content="Join expert-led Math & Science Olympiad online classes. Get structured lessons, practice tests & strategies to boost your score. Enroll now for top results! 🚀" />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/')}}">


@stop
@section('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" defer />
<link href="{{ asset('style.css') }}" rel="stylesheet">

<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Organization",
  "@id": "https://www.vaagaacademy.com/",

  "legalName": "Vaagaacademy",
  "description": "Join expert-led Math & Science Olympiad online classes. Get structured lessons, practice tests & strategies to boost your score. Enroll now for top results! 🚀 ",
  "url": "https://www.vaagaacademy.com/",
  "email" : "info@vaagaacademy.com",
  "logo" : "https://www.vaagaacademy.com/newassets/img/favicon.png",
  "SameAs" :[
    "https://www.facebook.com/profile.php?id=100095502854507",
      "https://www.youtube.com/channel/UCLc_WUBiIdvi1aMwMk4Ixyg",
        "https://www.instagram.com/vaagaacademy/"
    ]
}
</script>


<style type="text/css">
   .invalid-feedback {
      text-align: left; 
   }

   body {
      overflow-x: hidden;
   }
   
       .img-live-class {
     
        object-fit: cover;
    }
   .dot {
    display: inline-block;
    height: 9px;
    width: 8px;
    background: #0000009e;
    border-radius: 50%;
    margin: 0px 3px;
}

   a.book-demo-button::hover {
      color: #000;
   }

   a.book-demo-button {
      position: fixed;
      right: -58px;
      top: 50%;
      z-index: 999;
      background: #febc58;
      transform: rotate(90deg);
      padding: 2px 5px;
      border-radius: 5px;
      cursor: pointer;
      color: #000;
   }

   /* Extra small devices (phones, 600px and down) */
   @media only screen and (max-width: 600px) {
      .slider-height-respons {
         height: 450px !important;
      }

      .slider-content-padding .slider-p-padding {
         padding-left: 30px !important;
         padding-right: 30px !important;
      }

      .img-live-class {
         height: 200px;
      }

   }

   /* Small devices (portrait tablets and large phones, 600px and up) */
   @media only screen and (min-width: 600px) {
      .slider-height-respons {
         height: 450px !important;
      }

      .slider-content-padding .slider-p-padding {
         padding-left: 30px !important;
         padding-right: 30px !important;
      }

      .img-live-class {
         height: 200px;
      }
   }

   /* Medium devices (landscape tablets, 768px and up) */
   @media only screen and (min-width: 768px) {
      .slider-height-respons {
         height: 450px !important;
      }

      .slider-content-padding .slider-p-padding {
         padding-left: 30px !important;
         padding-right: 30px !important;
      }

      .img-live-class {
         height: 200px;
      }
   }

   /* Large devices (laptops/desktops, 992px and up) */
   @media only screen and (min-width: 992px) {
      .slider-height-respons {
         height: 100vh !important;
      }

      .slider-content-padding {
         padding-top: 135px !important;
      }

      .img-live-class {
         height: 150px;
      }
   }

   /* Extra large devices (large laptops and desktops, 1200px and up) */
   @media only screen and (min-width: 1200px) {
      .slider-height-respons {
         height: 100vh !important;
      }

      .slider-content-padding {
         padding-top: 135px !important;
      }

      .img-live-class {
         height: 150px;
      }
   }

   .card-footer {
      padding: 10px 10px;
   }

   .course-hours {
      /*    float: left;*/
      font-size: 12px;
   }

   .course-fees {
      /* display: inline-block; */
      float: right;
      text-align: center;
      width: 50%;
   }

   span.old_price {
      text-decoration: line-through;
      font-size: 12px;
   }

   .price_type {
      font-size: 12px;
      font-weight: 500;
      color: #FF9800;
      line-height: 1;
      margin-bottom: 6px;
   }

   span.price {
      color: #15db95;
      font-weight: 700;
      /*    display: block;*/
      /*    margin-top: -11px;*/
      font-size: 13px;
   }

   .feature-hover-2 .feature-content {
      position: absolute;
      top: 0;
      left: 0;
      /*     width: 100%;*/
      /*     height: 85%; */
      padding: 40px 34px 0px 37px !important;
   }

   .feature-hover-2 {
      border-radius: 10px !important;
      height: 110px !important;
   }

   .feature-icon img {
      height: 110px !important;
      width: 100% !important;
   }

   .border-2 {
      border: 1.5px solid;
   }

   .slide-image {
      height: 450px;
      margin-top: 68px;
   }


   .bg-cover {
      background-size: cover !important;
   }


   @media only screen and (max-width: 600px) {
      .slide-image {
         height: 150px;
         margin-top: 68px;
      }

      .swiper-arrow-style-01 {
         top: 55px;
      }

      .mobile h1 {
         font-size: 20px;
         margin-bottom: 0px !important;
      }

      .mobile p {
         font-size: 14px;
         margin-bottom: 0px !important;
      }

      .mobile .py-8 {
         padding-top: 1rem !important;
      }

      .mobile .pt-3 {
         padding-top: 0px !important;
      }

      .mobile a.btn {
         padding: 4px 10px;
      }
   }

   /* Small devices (portrait tablets and large phones, 600px and up) */
   @media only screen and (min-width: 600px) {
      .slide-image {
         height: 150px;
         margin-top: 68px;
      }

      .swiper-arrow-style-01 {
         top: 55px;
      }
   }

   /* Medium devices (landscape tablets, 768px and up) */
   @media only screen and (min-width: 768px) {
      .slide-image {
         height: 250px;
         margin-top: 68px;
      }

      .swiper-arrow-style-01 {
         top: 30px;
      }
   }

   /* Large devices (laptops/desktops, 992px and up) */
   @media only screen and (min-width: 992px) {
      .slide-image {
         height: 450px;
         margin-top: 68px;
      }

      .swiper-arrow-style-01 {
         top: 0px;
      }
   }

   /* Extra large devices (large laptops and desktops, 1200px and up) */
   @media only screen and (min-width: 1200px) {
      .slide-image {
         height: 450px;
         margin-top: 68px;
      }

      .swiper-arrow-style-01 {
         top: 0px;
      }
   }

   .arrow {
      text-align: right;
      margin-bottom: 18px;
      color: #febc58;
   }

   .swiper-next-01x:hover,
   .swiper-prev-01x:hover {
      color: #fff;
   }

   .row.card-height .card {
      height: 250px;
   }

   .row.card-height .card-body {
      padding: 15px 13px;
   }

   .row.card-height .card-body {
      background: #ff980014;
   }

   .h5x {
      font-size: 16px;
   }

   .info-text {
      text-align: justify;
   }

   /* Live-style hero */
   .hero-live {
      background-color: #1e3a5f;
      min-height: 70vh;
      position: relative;
      overflow: hidden;
   }
   .hero-live::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Ctext x='0' y='40' font-size='12' fill='%23ffffff' fill-opacity='0.06' font-family='monospace'%3Ey=2x² π%3C/text%3E%3C/svg%3E");
      opacity: 1;
      pointer-events: none;
   }
   .hero-live .container { position: relative; z-index: 1; }
   .hero-live .hero-headline { color: #fff; font-weight: 700; line-height: 1.2; }
   .btn-hero-join {
      background-color: #ffbe3d !important;
      border-color: #ffbe3d !important;
      color: #fff !important;
      font-weight: 600;
      padding: 0.6rem 1.5rem;
   }
   .btn-hero-join:hover {
      background-color: #e5ab35 !important;
      border-color: #e5ab35 !important;
      color: #fff !important;
   }
   .section-title-gold { color: #ffbe3d !important; }
   .section-title-orange { color: #ffbe3d !important; }
   .hero-trophy-wrap { color: #ffbe3d; }
   .hero-trophy-wrap .bi-trophy-fill { font-size: clamp(6rem, 15vw, 10rem); filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3)); }
   @media (max-width: 991px) {
      .hero-live { min-height: auto; padding: 3rem 0; }
      .hero-trophy-wrap { order: -1; margin-bottom: 1rem; }
   }

   /* Live-style Olympiad cards + Book Now */
   .olympiad-card { background: #fff; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.08); overflow: hidden; height: 100%; display: flex; flex-direction: column; }
   .olympiad-card .card-body { flex: 1; padding: 1.5rem; }
   .olympiad-card .olympiad-icon { font-size: 3rem; color: #4a2a51; margin-bottom: 0.75rem; }
   .olympiad-card .card-title { font-weight: 700; color: #333; margin-bottom: 0.5rem; }
   .olympiad-card .card-text { font-size: 0.9rem; color: #666; margin-bottom: 1rem; }
   .btn-book-now {
      background-color: #4a2a51 !important;
      border-color: #4a2a51 !important;
      color: #fff !important;
      font-weight: 600;
      padding: 0.5rem 1.25rem;
      border-radius: 6px;
   }
   .btn-book-now:hover {
      background-color: #3d2342 !important;
      border-color: #3d2342 !important;
      color: #fff !important;
   }
   @media (max-width: 767px) {
      .olympiad-card .card-body { padding: 1.25rem; }
      .olympiad-card .olympiad-icon { font-size: 2.5rem; }
   }
   .program-cards-section {
      background: linear-gradient(180deg, #4a2a5140 0%, #f7f7f7 100%);
   }
   .olympiad-test-series-bg {
      background: linear-gradient(90deg, #e8e0f0 0%, #4a2a51 100%);
   }
   .categories-section {
      background-color: #4a2a51;
   }
   .course-filter-bar .course-filter-btn {
      background: transparent; border: 1px solid rgba(255,255,255,0.5); color: #fff;
   }
   .course-filter-bar .course-filter-btn:hover,
   .course-filter-bar .course-filter-btn.active { background: rgba(255,255,255,0.2); color: #fff; border-color: #ffbe3d; }
   .course-filter-bar .course-filter-btn.active { border-width: 2px; border-color: #ffbe3d; }
   .branding-block {
      background-color: #000;
   }
   .branding-title {
      color: #ffbe3d;
      font-weight: 700;
      font-size: clamp(1.75rem, 4vw, 2.5rem);
      letter-spacing: 0.02em;
   }
   .branding-tagline {
      color: rgba(255,255,255,0.9);
      font-size: 1.1rem;
   }
   .trending-courses-section {
      background-color: #fff;
   }
   .trending-courses-section .section-heading .h1 { color: #333 !important; }
   .trending-courses-section .arrow { color: #4a2a51; }
   .trending-courses-section .card { background: #fff; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
   .trending-view-all { color: #4a2a51; font-weight: 600; }
   .star-tutors-section { background-color: #4a2a51; }

   .Video {
  position: relative;
  width: 100%;
  overflow: hidden;
}

.responsive-iframe {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  height: 70%;
  border: none;
}
.f24{
    font-size: 24px;
}
</style>
@stop

@section('content')

<main>
   <!-- Home Banner (with fallback when no slider) -->
   @if(isset($slider) && count($slider) > 0)
   <div class="swiper swiper-container " data-swiper-options='{
           "slidesPerView": 1,
           "spaceBetween": 0,
           "autoHeight":false,
           "loop": true,
           "navigation": {
           "nextEl": ".swiper-next-01",
           "prevEl": ".swiper-prev-01"
           },
           "autoplay": {
           "delay": 5000,
           "disableOnInteraction": false
           }
           }'>
      <div class="swiper-wrapper">
         @foreach($slider as $sl)
         @php
         $slider_data = json_decode($sl->content);

         @endphp
         <div class="swiper-slide">
            <div class="bg-cover bg-no-repeat effect-section slider-height-responsx slide-image hero-live" style="background-image: url({{asset('/storage/uploads/'.$sl->bg_image)}} );background-position: center center; background-color: #1e3a5f;">
               <div class="mask bg-blackx opacity-5"></div>
               <div class="container position-relative px-5 px-lg-3 mobile">
                  <div class="row align-items-center py-8">
                     <div class="col-lg-7 slider-content-padding text-center text-lg-start">
                        <p class="display-4 lh-sm hero-headline text-white mb-3">{{$slider_data->hero_text}}</p>
                        <div class="slider-p-padding mb-2">
                           <p class="lead text-white text-opacity-90">{{$slider_data->sub_text}}</p>
                        </div>
                        @if($slider_data->hero_text!="" && $slider_data->sub_text!="")
                        <div class="pt-3">
                           <a class="btn btn-hero-join btn-lg me-2" href="#our-courses">JOIN NOW</a>
                           <a class="btn btn-outline-white" href="/about">About</a>
                        </div>
                        @endif
                     </div>
                     <div class="col-lg-5 text-center hero-trophy-wrap d-none d-lg-block">
                        <i class="bi bi-trophy-fill d-inline-block" aria-hidden="true"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endforeach

      </div>
      <div class="swiper-arrow-style-01 swiper-next swiper-next-01"><i class="bi bi-chevron-right"></i></div>
      <div class="swiper-arrow-style-01 swiper-prev swiper-prev-01"><i class="bi bi-chevron-left"></i></div>
   </div>
   @else
   <!-- Hero fallback: live-style (dark blue, JOIN NOW, trophy) -->
   <div class="hero-live">
      <div class="container px-4 py-5 py-lg-5">
         <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start">
               <h1 class="hero-headline display-4 mb-3">Time to Start<br>Olympiad Journey</h1>
               <p class="lead text-white text-opacity-90 mb-4">Join expert-led Olympiad classes and unlock your potential.</p>
               <div class="pt-2">
                  <a class="btn btn-hero-join btn-lg" href="#our-courses">JOIN NOW</a>
               </div>
            </div>
            <div class="col-lg-5 text-center hero-trophy-wrap">
               <i class="bi bi-trophy-fill d-inline-block" aria-hidden="true"></i>
            </div>
         </div>
      </div>
   </div>
   @endif



   <section class="section pt-5 pb-5 bg-white">
      <div class="container">
         <div class="row justify-content-center gy-4 gx-4">
            <div class="col-md-6 col-lg-4">
               <div class="card olympiad-card hover scale shadow-sm">
                  <div class="card-body p-3 d-flex flex-column">
                     <div class="d-flex justify-content-between align-items-start">
                        <div>
                           <h5 class="card-title">Olympiad Preparation</h5>
                           <p class="card-text">Structured courses and study material for Olympiad exams. Start your preparation with expert guidance.</p>
                        </div>
                        <i class="bi bi-journal-check olympiad-icon flex-shrink-0" aria-hidden="true"></i>
                     </div>
                     <a href="#our-courses" class="btn btn-book-now btn-sm mt-auto align-self-start">Know More</a>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-lg-4">
               <div class="card olympiad-card hover scale shadow-sm">
                  <div class="card-body p-3 d-flex flex-column">
                     <div class="d-flex justify-content-between align-items-start">
                        <div>
                           <h5 class="card-title">Live Classes</h5>
                           <p class="card-text">Live interactive classes with certified tutors. Doubt-solving sessions and personalised attention.</p>
                        </div>
                        <i class="bi bi-camera-video olympiad-icon flex-shrink-0" aria-hidden="true"></i>
                     </div>
                     <a href="#our-courses" class="btn btn-book-now btn-sm mt-auto align-self-start">Know More</a>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-lg-4">
               <div class="card olympiad-card hover scale shadow-sm">
                  <div class="card-body p-3 d-flex flex-column">
                     <div class="d-flex justify-content-between align-items-start">
                        <div>
                           <h5 class="card-title">Certificate After Complete</h5>
                           <p class="card-text">Receive a certificate of completion after finishing your course. Track progress and achieve your goals.</p>
                        </div>
                        <i class="bi bi-award olympiad-icon flex-shrink-0" aria-hidden="true"></i>
                     </div>
                     <a href="#our-courses" class="btn btn-book-now btn-sm mt-auto align-self-start">Know More</a>
                  </div>
               </div>
            </div>
         </div>
   </section>
   <!-- Olympiad Test Series card -->
   <section class="section pt-4 pb-5 olympiad-test-series-bg">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-10">
               <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                  <div class="card-body p-4 p-lg-5">
                     <div class="text-center mb-3"><i class="bi bi-journal-text text-primary" style="font-size: 2rem;"></i></div>
                     <h3 class="h4 mb-3 text-center text-dark" style="color: #4a2a51 !important;">Olympiad Test Series</h3>
                     <p class="text-muted text-center mb-4">The best study material and mock tests to ace your Olympiad exams. Practice in a fun way and test yourself from home.</p>
                     <div class="row text-center mb-4">
                        <div class="col-md-4 mb-3">
                           <i class="bi bi-journal-text text-primary" style="font-size: 2rem;"></i>
                           <h6 class="mt-2 fw-bold">The Best Study</h6>
                           <p class="small text-muted mb-0">Comprehensive study material and guides.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                           <i class="bi bi-clock text-success" style="font-size: 2rem;"></i>
                           <h6 class="mt-2 fw-bold">Time to Start</h6>
                           <p class="small text-muted mb-0">Flexible schedules to learn at your pace.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                           <i class="bi bi-bullseye text-danger" style="font-size: 2rem;"></i>
                           <h6 class="mt-2 fw-bold">Get Best Results</h6>
                           <p class="small text-muted mb-0">Practice tests and performance analysis.</p>
                        </div>
                     </div>
                     <div class="text-center">
                        <a href="/our-classes" class="btn btn-book-now">Join Now</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="section pt-5 pb-5 categories-section">
      <div class="container">
         <div class="row justify-content-center section-heading mb-4">
            <div class="col-lg-8 text-center text-lg-left">
               <h1 class="h1 mb-0 text-center text-lg-left f24 text-white">Excel in Math & Science Olympiads with Expert Online Classes</h1>
            </div>
         </div>
         <div class="row justify-content-center gy-2 gx-2 mb-4 course-filter-bar">
            <div class="col-auto"><a href="/courses" class="btn btn-sm rounded-pill px-3 py-2 course-filter-btn active"><i class="bi bi-grid me-1"></i>All Classes</a></div>
            <div class="col-auto"><a href="/courses?type=live" class="btn btn-sm rounded-pill px-3 py-2 course-filter-btn"><i class="bi bi-camera-video me-1"></i>Live Classes</a></div>
            <div class="col-auto"><a href="/courses?type=recorded" class="btn btn-sm rounded-pill px-3 py-2 course-filter-btn"><i class="bi bi-play-circle me-1"></i>Recorded Classes</a></div>
            <div class="col-auto"><a href="/our-classes" class="btn btn-sm rounded-pill px-3 py-2 course-filter-btn"><i class="bi bi-trophy me-1"></i>Olympiad</a></div>
            <div class="col-auto"><a href="/courses?cat=iit-jee" class="btn btn-sm rounded-pill px-3 py-2 course-filter-btn"><i class="bi bi-mortarboard me-1"></i>IIT-JEE</a></div>
         </div>
         <div class="row justify-content-center gy-4">

            @foreach($categories as $cat)

            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
               <div class="p-3 pe-5 border border-white arrow-hover rounded-3" style="background-color:#4a2a51;box-shadow: 3px 3px 0px 0px #e5dbdbd9;text-align: center;">
                  <div class="arrow-icon text-white"></div><a class="stretched-link h6 fw-600 text-white m-0" href="{{route('category',['slug'=>$cat->slug])}}">{{$cat->name}}</a>
               </div>
            </div>


            @endforeach

         </div>
   </section>
  


   <!-- Section -->
   <section class="section trending-courses-section effect-section" id="our-courses">
      <div class="particles-box" id="particles-box"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>
      <div class="container">
         <div class="row section-heading justify-content-between align-items-center wow fadeInUp mb-2" data-wow-duration="0.5s" data-wow-delay="0.1s">
            <div class="col-auto">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-0 section-title-orange">Trending Courses</h3>
            </div>
            <div class="col-auto">
               <a href="/courses" class="trending-view-all fw-semibold">View All</a>
            </div>
         </div>
         <div class="arrow">
            <div class=" swiper-prev swiper-prev-01x d-inline-block"><i class="bi bi-chevron-left"></i>PRV </div>
            <div class=" swiper-next swiper-next-01x d-inline-block"> NXT<i class="bi bi-chevron-right"></i></div>

         </div>
         <div class="swiper swiper-container" data-swiper-options='{
           "slidesPerView": 1,
           "spaceBetween": 20,
           "autoHeight":false,
           "loop": true,
           "autoplay":true,
           "breakpoints": {
    
    "640": {
      "slidesPerView": 1,
      "spaceBetween": 20
    },
 
    "768": {
      "slidesPerView": 2,
      "spaceBetween": 20
    },
   
    "1024": {
      "slidesPerView": 4,
      "spaceBetween": 20
    }
  },
           "navigation": {
           "nextEl": ".swiper-next-01x",
           "prevEl": ".swiper-prev-01x"
           },
           "autoplay": {
           "delay": 3000,
           "disableOnInteraction": false
           }
           }'>
            <div class=" swiper-wrapper">


               @if($featured_courses->count() > 0)
               @foreach($featured_courses as $course)

               <div class="swiper-slide wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                  <div class="card hover-scale overflow-hidden hover-top">
                     <div class="position-relative hover-scale-in">
                        <a href="{{ route('courses.show', [$course->slug]) }}">
                           <img class="card-img-top img-live-class" src="{{asset('storage/uploads/'.$course->course_image)}}" onerror="this.src='/newassets/img/logo.webp'" title="{{$course->title}}" alt="{{$course->title}}" loading="lazy">
                        </a>

                     </div>
                     <div class="card-body p-3">

                        <?php

                        $cp = Coupon::find($course->coupon_id);
                        ?>

                        <h5 class="mb-3">
                           @if($cp)
                           <a class="text-dark stretched-link" href="{{ route('courses.show', [$course->slug]) }}?coupon={{$cp->code}}">{{$course->title}}</a>
                           @else
                           <a class="text-dark stretched-link" href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>

                           @endif
                        </h5>

                        <div class="course-hours d-block"><i class="bi-people-fill"></i>

                           <?php $bd = Board::find($course->board_id); ?>

<?php
$catx = Category::find($course->category->parent);

?>
                           @if($bd)
                           {{$bd->name}}<div class="dot"></div>
                          @else
                             @if($catx)
                        {{$catx->name}} <div class="dot"></div>
                        @endif
                         @endif
                           {{ $course->category->name}}
                        </div>
                        @if($course->duration_text)
                        <div class="course-hours d-block"><i class="bi-clock"></i>




                           {{$course->duration_text}}
                        </div>
                        @endif


                     </div>
                     
                     <div class="card-footer d-flex flex-wrap align-items-center">


                        <?php

                        $cop = new Coupon;
                        $price = $cop->applyCoupon($course->coupon_id, $course->price);
                        $monthly_price = $cop->applyCoupon($course->coupon_id_monthly_price, $course->monthly_price);
                        ?>

                        @if($course->monthly_price)

                        @if($monthly_price<$course->monthly_price)
                           <div class="course-fees" style="    text-align: left;">
                              <span class="old_price">₹{{round($course->monthly_price)}}</span>
                              <span class="price">₹{{round($monthly_price)}}</span>
                              <div class="price_type">Subscription</div>
                           </div>
                           @else
                           <div class="course-fees" style="    text-align: left;">
                              <span class="price">₹{{round($course->monthly_price)}}</span>
                              <div class="price_type">Subscription</div>
                           </div>
                           @endif

                           @endif

                           @if($course->price)

                           @if($price<$course->price)
                              @if($course->monthly_price)
                              <div class="course-fees" style="    text-align: right;">
                                 @else
                                 <div class="course-fees" style="    text-align: left;">
                                    @endif
                                    <span class="old_price">₹{{round($course->price)}}</span>
                                    <span class="price">₹{{round($price)}}</span>
                                    <div class="price_type">Full Course</div>
                                 </div>
                                 @else
                                 @if($course->monthly_price)
                                 <div class="course-fees" style="    text-align: right;">
                                    @else
                                    <div class="course-fees" style="    text-align: left;">
                                       @endif
                                       <span class="price">₹{{round($course->price)}}</span>
                                       <div class="price_type">Full Course</div>
                                    </div>
                                    @endif

                                    @endif

                        <div class="mt-2 w-100">
                           @if($cp)
                           <a href="{{ route('courses.show', [$course->slug]) }}?coupon={{$cp->code}}" class="btn btn-book-now btn-sm">View Course</a>
                           @else
                           <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btn-book-now btn-sm">View Course</a>
                           @endif
                        </div>

                     </div>



                     @endforeach
                     @endif

                  </div>

               </div>
            </div>

         </div>
      </div>
   </section>
   <!-- End Section -->


   <section class="section why-choose-section">
      <div class="container">
         <div class="row align-items-center justify-content-between ">
            <div class="col-lg-6 my-3 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInLeft;">
               <h2 class="h2">Why Choose Our Olympiad, Science & Maths, NTSE, SOF, and Other Exams</h2>
               <p class="lead" style="text-align: justify;">Our Math & Science Olympiad online classes provide expert-led coaching with a comprehensive syllabus, interactive live sessions, and personalized learning plans to help students excel. Learn from experienced faculty, access 24/7 study materials, and practice with mock tests designed to simulate real Olympiad exams. With a structured approach and tailored guidance, our program ensures concept clarity and problem-solving skills for top performance. Enroll today and boost your Olympiad success!</p>
               <ul class="list-type-03 mb-4 list-unstyled">
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Learn from certified experts</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Flexible Learning</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Easy To Understand</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Practice questions</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Detailed solutions</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Performance analysis</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Live interactive classes</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Secure Payment Gateway and Flexible payment options</li>
               </ul>

               <a class="btn btn-hero-join btn-lg" href="/about">READ MORE</a>

            </div>
            <div class="col-lg-6 my-3 wow fadeInRight text-center" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInRight;"><img src="{{asset('newassets/img/about/about01.gif')}}" loading="lazy" width="400" height="400" title="" alt="About vaagaacademy"></div>
         </div>
      </div>
   </section>

   <section>
      <div class="container containerVideo">
         <div class="row justify-content-center d-flex">
            <div class="col-md-12 Videox" style="text-align:center;">
                @if($link && isset($link->link))
                <div data-href="https://www.youtube.com/embed/{{$link->link}}"  class="vplay" >
    <img src="/yt-vaaga.webp"  loading="lazy"  height="500" style="object-fit: cover;" alt="Youtube Math and Science Olympiad Classes" />
    </div>
                @else
                <div class="vplay" >
    <img src="/yt-vaaga.webp"  loading="lazy"  height="500" style="object-fit: cover;" alt="Youtube Math and Science Olympiad Classes" />
    </div>
                @endif
              
            </div>
         </div>
      </div>
   </section>

   <section class="section py-5 py-lg-6 branding-block">
      <div class="container text-center">
         <h2 class="branding-title mb-2">Vaaga ACADEMY</h2>
         <p class="branding-tagline mb-0">Nurturing The Future Online</p>
      </div>
   </section>

   <section class="section  effect-section" style="background: #f7f7f759;">

      <div class="container">
         <div class="row">
            <div class="col-md-12 mb-5 text-center">
               <h3 class="h1 bg-000-after after-50px section-title-orange">What Makes Us Unique</h3>

            </div>
            <div class="col-md-12 text-center">
               <p>
                  Our Math & Science Olympiad online classes stand out with expert faculty, interactive learning, personalized study plans, and real-time mock tests to ensure top performance. With 24/7 access to study materials, doubt-solving sessions, and a structured curriculum, we provide a holistic approach to Olympiad success. Join us today and experience the difference! 🚀 </p>
            </div>

            <div class="col-md-12 text-center">
               <h4>India's Expert Tutors for Online Math and Science Olympiad</h4>
            </div>

            <?php

            $irls = array(
               "24/7 Mentoring Support",
               "Learn At Your Pace",
               "Accurate & Reliable Data",
               "Regular Doubt Sessions",
               "Unlimited Practice",
               "Affordable Fees",
               "Dedicated Mentors"
            );

            $colorsClass = array(
               "bg-info", "bg-primary", "bg-warning", "bg-black", "bg-success", "bg-danger", "bg-secondary"
            );

            $iconsClass = array(
               "fa fa-headphones", "fa fa-clock", "fa fa-chart-line", "fa fa-comments", "fa fa-repeat", "fa fa-rupee-sign", "fa fa-user-graduate"
            );
            ?>

            @foreach($irls as $k=>$ir)
            <div class="col-md-4 col-sm-12 col-12 mb-3">
               <div class="d-flex bg-body shadow-sm p-3 rounded-3">
                  <div class="icon-lg {{$colorsClass[$k]}} text-white rounded-circle"><i class="{{$iconsClass[$k]}}"></i></div>
                  <div class="col ps-3" style="padding-top:16px;">
                     <h5 class="h6 mb-1">{{$ir}}</h5>
                  </div>
               </div>
            </div>
            @endforeach


         </div>
      </div>

   </section>
   <section class="section bg-gray-100 effect-section">
      <div class="particles-box" id="particles-box-01"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>
      <!-- <div class="position-absolute top-0 end-0 start-0 bottom-0 bg-cover bg-no-repeat opacity-1 bg-fixed" style="background-image: url(/public/newassets/img/effect/ef-bg-2.png);"></div> -->
      <div class="container">

         <div class="row section-heading justify-content-center text-center wow fadeInUp" data-wow-duration="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-name: fadeInUp;">
            <div class="col-lg-8 col-xl-6">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3 text-warning">Why Choose {{env('PROJECT_NAME')}}?</h3>
            </div>
         </div>

         <div class="row card-height">
            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-000 text-white rounded-3 mb-4"><i class="bi bi-microsoft-teams"></i></div>
                     <h5 class="dark-color mb-2 h5x">Live Personalised Private and Group classes</h5>
                     <p class="info-text">Customised interactive online learning sessions for better understanding of concepts.</p>

                  </div>
               </div>
            </div>

            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-primary text-white rounded-3 mb-4"><i class="fa fa-chalkboard-user"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Interactive Classroom</h5>
                     <p class="info-text">Whiteboard, Online quizzes and assignments for high student engagement and effective learning.</p>

                  </div>
               </div>
            </div>
            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.2s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-secondary  text-white rounded-3 mb-4"><i class="fa fa-brain"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Concept Building</h5>
                     <p class="info-text">Focus on concept clarity and instant doubt clearing during the class to ensure good result.</p>

                  </div>
               </div> 
            </div>



            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-black  text-white rounded-3 mb-4"><i class="fa fa-globe"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Anytime and anywhere</h5>
                     <p class="info-text">No hassle to travel long distances, take classes at comfort of your home and as per your time preference.</p>

                  </div>
               </div>
            </div>
            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-info  text-white rounded-3 mb-4"><i class="fa fa-chart-simple"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Regular progress update</h5>
                     <p class="info-text">Regular assignments helps to track the progress that will be shared with parents on regular basis.</p>

                  </div>
               </div>
            </div>




            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-warning text-white rounded-3 mb-4"><i class="fa fa-person-chalkboard"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Experienced and Expert Tutors</h5>
                     <p class="info-text">Rigorous selection process of Tutor ensures experienced and expert Tutors from all over the country.</p>

                  </div>
               </div>
            </div>

            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-danger text-white rounded-3 mb-4"><i class="fa fa-rectangle-list"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Comprehensive Subject Coverage</h5>
                     <p class="info-text">Wide variety of courses are available to meet all your academic requirements.</p>

                  </div>
               </div>
            </div>



            <div class="col-lg-3 my-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
               <div class="card hover-top">
                  <div class="card-body">
                     <div class="icon-md bg-success  text-white rounded-3 mb-4"><i class="fa fa-video"></i></div>
                     <h5 class="dark-color  mb-2 h5x">Recorded sessions</h5>
                     <p class="info-text">All class recordings are available for later view and reference in case of any doubt or for revision.</p>

                  </div>
               </div>
            </div>



         </div>
      </div>
   </section>



   <section class="section">

      <div class="container">
         <div class="row">
            <div class="col-md-12 mb-5 text-center">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3 section-title-orange">How Our Classes Work</h3>
            </div>
            <div class="col-md-12">

               <div class="main-timeline4">
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" data-wow-mobile="true">
                     <span class="timeline-content">
                        <span class="year"><i class="bi-mortarboard-fill" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">REGISTER WITH US</h3>
                           <p class="description">Sign Up for free & Get Start.</p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-people" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">JOIN THE CLASS</h3>
                           <p class="description">Attend your class with no hassle.</p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.4s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-clipboard-check" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">COMPLETE HOMEWORK</h3>
                           <p class="description">Revise the lessons and do your tasks.</p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.8s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-award" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">GET CERTIFICATE</h3>
                           <p class="description">Receive a certificate of completion.</p>
                        </div>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </section>

   @if(count($testimonials)>0)
   <!-- Section -->
   <section class="section" style="padding-bottom: 0px;">
      <div class="container">
         <div class="row section-heading justify-content-center text-center wow fadeInUp" data-wow-duration="0.5s">
            <div class="col-lg-8 col-xl-6">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3 section-title-gold">Browse Reviews from Students</h3>

            </div>
            
         </div>
         <div class="swiper swiper-container wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" data-swiper-options='{
                 "slidesPerView": 1,
                 "spaceBetween": 24,
                 "pagination": {
                 "el": ".swiper-pagination",
                 "clickable": true
                 },
                 "breakpoints": {
                 "650": {
                 "slidesPerView": 2
                 },
                 "991": {
                 "slidesPerView": 2
                 },
                 "1024": {
                 "slidesPerView": 3
                 }
                 }
                 }'>
             
             <style>
                 .show-less 
{
    max-height: 110px;
    margin-bottom: 0px;
    overflow: hidden;
    font-size: 13px;
    line-height: 25px;
    transition: all 1s;
 }
             </style>
            <div class="swiper-wrapper">
               @foreach($testimonials as $te)
               <div class="swiper-slide">
                  <div class="border text-center mt-5 mb-4 rounded-3">
                     <!--<div class="icon-md bg-000 text-white rounded-circle mt-n5">-->
                     <!--    <i class="bi bi-quote"></i></div>-->
                     
                         <div class="pt-2">
                          <a href="#">
                       <iframe width="360px" height="200" src="https://www.youtube.com/embed/{{$te->video_link}}" title="YouTube video player"  allowfullscreen></iframe>
                       
                    </a>
                    </div>
                    
                   
                    <div class="p-4 pt-1">
                        <p>{{$te->content}} </p>
                        <!--<p>{{Str::limit($te->content, 115, '...')}}<span class="text-danger">Read more</span>-->
                       
                        </p>
                        <h5 class="h6 m-0">{{$te->name}}</h5>
                        <label class="fw-600 small m-0">{{$te->occupation}} </label>
                     </div>
                  </div>
               </div>
               @endforeach
           

               
               <div class="swiper-pagination position-relative mt-2"></div>
            </div>
            <div class="pb-5 text-center"><a class="btn btn-hero-join" href="{{route('home.testimonials')}}">VIEW ALL</a></div>
         </div>
   </section>
   <!-- End Section -->
   @endif
   
  


   <!-- <section class="section bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url(/banner.webp);" id="demo">
      <div class="container">
         <div class="row">
            <div class="col-lg-6 col-xl-5 ">
               <div class="card">
                  <div class="card-body text-center">
                     <h3 class="bg-light-after after-50px pb-3 mb-3">Book Free Demo Class</h3>
                     <p>Are you ready to take the next step towards achieving your career goals? Let VaaGaa Academy be your trusted partner</p>
                     <form class="rdx-mailform" method="post" action="{{route('home.demorequest')}}">
                        @csrf
                        <div class="mb-3">
                           <input id="contact-name" type="text" value="{{old('name')}}" required name="name" placeholder="Full Name" class="form-control @if($errors->has('name')) is-invalid @endif">
                           @if($errors->has('name'))
                           <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                           @endif
                        </div>
                        <div class="mb-3">
                           <input id="contact-email" type="email" value="{{old('email')}}" required name="email" placeholder="Email" class="form-control @if($errors->has('email')) is-invalid @endif">
                           @if($errors->has('email'))
                           <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                           @endif
                        </div>


                        <div class="mb-3">
                           <input id="contact-phone" type="text" value="{{old('phone')}}" required name="phone" placeholder="Phone Number" class="form-control @if($errors->has('phone')) is-invalid @endif">
                           @if($errors->has('phone'))
                           <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                           @endif

                           <input type="hidden" name="course_id" id="demo_course_id" />
                        </div>
                        <div class="mb-3">
                           <?php $cats1 = Category::where('parent', 0)->where('status', '1')->get();


                           ?>
                           <select class="form-control form-select courseCategory l1 @if($errors->has('course_id')) is-invalid @endif" required id="courseCategory">
                              <option value="">Select Course Category</option>
                              @foreach($cats1 as $ct)
                              <option value="{{$ct->id}}">{{$ct->name}}</option>

                              @endforeach

                           </select>
                           @if($errors->has('course_id'))
                           <div class="invalid-feedback">{{ $errors->first('course_id') }}</div>
                           @endif
                        </div>
                        <div class="demo-course-list">

                        </div>

                        <div class="mb15">
                        <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div> 

                         <button class="btn btn-primary w-100" type="submit">Submit</button> 
                        <div>
                           <button class="btn btn-primary w-100" data-sitekey="6Le-sVUmAAAAAI9X2Oe9oN6ldmWo6-J1EeuJp081" data-callback='onSubmit' data-action='submit' type="submit" id="submitDemo" style="display:none;" name="send">Submit</button> 

                           <button class="btn btn-primary w-100" type="submit" id="submitDemo" style="display:none;" name="send">Submit</button>

                        </div>
                     </form>
                     <button class="btn btn-warning rounded-0" type="submit">Contact Now</button>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </section> -->

   <?php

   $tutors = User::where("is_star", '1')->get();

   ?>

   @if(count($tutors)>0)

   <section class="section star-tutors-section effect-section">
      <div class="particles-box" id="particles-box-01"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>

      <div class="container">

         <div class="row section-heading justify-content-between align-items-center wow fadeInUp" data-wow-duration="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-name: fadeInUp;">
            <div class="col-auto">
               <h3 class="h1 bg-000-after after-50px text-white mb-0">Star Tutors</h3>
            </div>
            <div class="col-auto">
               <a href="/become-tutor" class="text-white fw-semibold">View All</a>
            </div>
         </div>

         <div class="arrow text-white">
            <div class=" swiper-prev swiper-prev-01x d-inline-block"><i class="bi bi-chevron-left"></i>PRV </div>
            <div class=" swiper-next swiper-next-01x d-inline-block"> NXT<i class="bi bi-chevron-right"></i></div>

         </div>
         <div class="swiper swiper-container" data-swiper-options='{
           "slidesPerView": 1,
           "spaceBetween": 20,
           "autoHeight":false,
           "loop": true,
           "autoplay":true,
           "breakpoints": {
    
    "640": {
      "slidesPerView": 1,
      "spaceBetween": 20
    },
 
    "768": {
      "slidesPerView": 2,
      "spaceBetween": 20
    },
   
    "1024": {
      "slidesPerView": 4,
      "spaceBetween": 20
    }
  },
           "navigation": {
           "nextEl": ".swiper-next-01x",
           "prevEl": ".swiper-prev-01x"
           },
           "autoplay": {
           "delay": 3000,
           "disableOnInteraction": false
           }
           }'>
            <div class=" swiper-wrapper">

               @foreach($tutors as $t)

               <?php

               $tp = TeacherProfile::where("user_id", $t->id)->first();
               ?>

               <div class="swiper-slide wow fadeInUp" data-wow-duration=".4s" data-wow-delay="0.05s" style="visibility: visible; animation-duration: 0.4s; animation-delay: 0.05s; animation-name: fadeInUp;">


                  <div class=" shadow rounded-3 position-relative  bg-body p-4 text-center ">
                     <img @if($t->avatar_location) src="/storage/{{$t->avatar_location}}" @else src='https://www.gravatar.com/avatar/9ecb4da50f5fec8857717862e2dbcaea.jpg?s=80&d=mm&r=g' @endif onerror="this.src='https://www.gravatar.com/avatar/9ecb4da50f5fec8857717862e2dbcaea.jpg?s=80&d=mm&r=g'" style="height:90px;width:90px;border-radius: 50%;" title="" alt="{{$t->name}}" loading="lazy" > 
                     <p class="fw-700 dark-color mb-1">{{$t->name}}</p>
                     @if($tp)
                     <small>{{$tp->hig_qualification}}</small><br>
                     @if($tp->subject)
                     <small>{{$tp->subject}}</small><br>
                     @endif
                     <small>{{$tp->total_exp}} Years Experience</small>

                     @endif

                     @if($tp)
                     <br>
                     @for($i=1;$i<=$tp->star_rating;$i++)
                        <i class="bi-star-fill text-warning"></i>
                        @endfor

                        @for($i=$tp->star_rating;$i<5;$i++) <i class="bi-star"></i>
                           @endfor

                           @endif

                           <!--  @if($tp)

           @foreach(json_decode($tp->subject_teach,true) as $st)
         <small>{{$st}}</small>
         @endforeach
         @endif -->

                  </div>

               </div>


               @endforeach

            </div>
         </div>


      </div>
   </section>

   @endif
   <section class="counter section">
      <div class="container">

         <div class="row section-heading justify-content-center text-center wow fadeInUp" data-wow-duration="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-name: fadeInUp;">
            <div class="col-lg-8 col-xl-6">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3 section-title-orange">Vaaga Academy Achievement</h3>
            </div>
         </div>

         <div class="row justify-content-center">
            <div class="col-6 col-lg col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #ffbe3d4a;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-laptop"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement ? $achievement->courses_offered : 0}}">{{$achievement ? $achievement->courses_offered : 0}}</span>+</h6>
                  <span>Courses offered </span>
               </div>
            </div>
            <div class="col-6 col-lg col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #4a2a514f;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-people-fill"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement ? $achievement->happy_students : 0}}">{{$achievement ? $achievement->happy_students : 0}}</span>+</h6>
                  <span>Students Enrolled </span>
               </div>
            </div>
            <div class="col-6 col-lg col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #50b5ff30;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-person"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement ? $achievement->expert_tutor : 0}}">{{$achievement ? $achievement->expert_tutor : 0}}</span>+</h6>
                  <span>Expert Tutors </span>
               </div>
            </div>
            <div class="col-6 col-lg col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #5cc9a747;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-chat"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement ? $achievement->hours_taught : 0}}">{{$achievement ? $achievement->hours_taught : 0}}</span>+</h6>
                  <span>Hours taught</span>
               </div>
            </div>
            <div class="col-6 col-lg col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #ffb3474a;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-award"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement && isset($achievement->certificates_issued) ? $achievement->certificates_issued : 500}}">{{$achievement && isset($achievement->certificates_issued) ? $achievement->certificates_issued : 500}}</span>+</h6>
                  <span>Certificates Issued</span>
               </div>
            </div>
         </div>
      </div>
   </section>

   <a class="book-demo-button d-none" href="#demo">
      <div>
         <i class="bi bi-mouse2-fill"></i> Book Free Demo
      </div>
   </a>

</main>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
    <div class="modal-content">
    
      <div class="modal-body vplay-data">
      
      </div>
    
    </div>
  </div>
</div>

@stop 
@section('page_js')

<!-- Theme JS -->
<?php if (Session::has('success')) { ?>

   <script>
      $(document).ready(function() {


         Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: 'Thank You For Choosing VaaGa Academy.',
            text: '',
            showConfirmButton: true,
            timer: 4500
         })

      });
   </script>
<?php } ?>

<script>
  $(document).ready(function() {
   var board_id = '';
   
   $(document).on("click",".vplay",function(){
       
       var href=$(this).data("href");
       $(".vplay-data").html('<iframe src="'+href+'?autoplay=1" style="width:100%;height:450px;" />')
       $("#exampleModalCenter").modal('show')
   })

   function onSubmit(token) {
      console.log(token);
      //  document.getElementById("demo-form").submit();
   }

   var course_id = '';
   var category_id = '';
   $(document).on("change", ".courseCategory", function() {
      var cat_id = $(this).val();
      category_id = cat_id;
      $("#demo_course_id").val("");
      console.log(cat_id);
      // $(".demo-course-list").html(""); 
      $("#submitDemo").hide();

      if (cat_id != '') {
         $.ajax({
            url: '/get-demo-course',
            data: {
               cat_id: cat_id
            },
            success: function(res) {
               $(".demo-course-list").append(res);
            }
         })
      }

   });
   $(document).on("change", ".l2", function() {

      $(".l5").remove();
      $(".l4").remove();

   });


   $(document).on("change", ".l1", function() {

      $(".l2").remove();
      $(".l3").remove();
      $(".l4").remove();
      $(".l5").remove();
   });

   $(document).on("change", ".l5", function() {


      $(".l4").remove();



   });
   $(document).on("change", "#select_board", function() {
      board_id = $(this).val();
      console.log(board_id);
      // $(".demo-course-list").html("");  
      $("#demo_course_id").val("");
      $("#submitDemo").hide();

      if (category_id != '') {
         $.ajax({
            url: '/get-demo-course',
            data: {
               board_id: board_id,
               category_id: category_id
            },
            success: function(res) {
               $(".demo-course-list").append(res);
            }
         })
      }

   });



   $(document).on("change", "#select_course", function() {

      var cid = $(this).val();
      if (cid == "") {
         $("#submitDemo").hide();
      } else {
         $("#submitDemo").show();
      }


      course_id = cid;

      $("#demo_course_id").val(course_id);
      console.log(cid);

   })

   var swiper = new Swiper($("#course_slider"));

   wow = new WOW({
      boxClass: 'wowx', // default
      animateClass: 'animated', // default
      offset: 0, // default
      mobile: true,
      live: true // default
   })
   wow.init();
   
  });
</script>


@stop