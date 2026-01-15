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
<link href="/public/style.css" rel="stylesheet">

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
   <!-- Home Banner -->
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
            <div class="bg-cover bg-no-repeat effect-section slider-height-responsx slide-image" style="background-image: url({{asset('/storage/uploads/'.$sl->bg_image)}} );background-position: center center; ">
               <div class="mask bg-blackx opacity-5"></div>
               <div class="container position-relative px-5 px-lg-3 mobile">
                  <div class="row align-items-center py-8 justify-content-center">
                     <div class="col-lg-8 slider-content-padding text-center ">
                        <p class="display-4 lh-sm text-white mb-3">{{$slider_data->hero_text}}</p>
                        <div class="w-lg-90 mx-auto slider-p-padding mb-2">
                           <p class="lead text-white text-opacity-65">{{$slider_data->sub_text}}</p>
                        </div>
                        @if($slider_data->hero_text!="" && $slider_data->sub_text!="")
                        <div class="pt-3">
                           <a class="btn btn-outline-white me-3" href="/about">About</a>
                           <a class="btn btn-outline-white" href="#our-courses">Our Courses</a>
                        </div>
                        @endif
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



   <section class="section pt-5 pb-5">
      <div class="container">
         <div class="row justify-content-center section-heading">
            <div class="col-lg-8 text-center text-lg-left">
             
            </div>
         </div>
         <div class="row justify-content-center gy-4">
            <div class="col-md-6">
               <div class="card hover scale">
                 <div class="card-body p-3">
                     <a href="javascript:void(0)">
                   <img src="{{asset('/frontend/assets/img/home/1.png')}}" loading="lazy" class="img-fluid enquiry" alt="Science Olympiad">
                   </a>
                 </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="card hover scale">
                 <div class="card-body p-3">
                      <a href="javascript:void(0)">
                   <img src="{{asset('/frontend/assets/img/home/2.png')}}" loading="lazy" class="img-fluid enquiry" alt="Math Olympiad">
                   </a>
                 </div>
               </div>
            </div>
         </div>
   </section>
   <section class="section pt-5 pb-5">
      <div class="container">
         <div class="row justify-content-center section-heading">
            <div class="col-lg-8 text-center text-lg-left">
               <h1 class="h1 mb-0 text-center text-lg-left f24">Excel in Math & Science Olympiads with Expert Online Classes</h1>
            </div>
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
   <section class="section bg-gray-100 effect-section" id="our-courses">
      <div class="particles-box" id="particles-box"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>
      <div class="container">
         <div class="row section-heading justify-content-center text-center wow fadeInUp mb-2" data-wow-duration="0.5s" data-wow-delay="0.1s">
            <div class="col-lg-8 col-xl-6">
               <h3 class="h1 bg-000-after after-50px pb-3 mb-0 text-warning">Trending Courses</h3>

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
                     
                     <div class="card-footer" style="display: flex;
    align-items: center;">


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



                                 </div>
                                 
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


   <section class="section">
      <div class="container">
         <div class="row align-items-center justify-content-between ">
            <div class="col-lg-6 my-3 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInLeft;">
               <h2 class="h2"> Why Choose Our Math & Science Olympiad Online Classes</h2>
               <p class="lead" style="text-align: justify;">Our Math & Science Olympiad online classes provide expert-led coaching with a comprehensive syllabus, interactive live sessions, and personalized learning plans to help students excel. Learn from experienced faculty, access 24/7 study materials, and practice with mock tests designed to simulate real Olympiad exams. With a structured approach and tailored guidance, our program ensures concept clarity and problem-solving skills for top performance. Enroll today and boost your Olympiad success! 🚀</p>
               <ul class="list-type-03 mb-4 list-unstyled">
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Personalised and interactive Live Private and Group classes</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Notes and Assessments for every topic</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Video recordings to view later</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Experienced and Well qualified Tutors</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Tutor replacement guarantee</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Best education at comfort and safety of your home</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Comprehensive Learning Programs</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Regular progress reporting to parents</li>
                  <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Secure Payment Gateway and Flexible payment options</li>
               </ul>



               <a class="btn btn-primary" href="/about">About</a>
               <a class="btn btn-warning" href="/contact">Contact</a>

            </div>
            <div class="col-lg-6 my-3 wow fadeInRight text-center" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInRight;"><img src="{{asset('newassets/img/about/about01.gif')}}" loading="lazy" width="400" height="400" title="" alt="About vaagaacademy"></div>
         </div>
      </div>
   </section>

   <section>
      <div class="container containerVideo">
         <div class="row justify-content-center d-flex">
            <div class="col-md-12 Videox" style="text-align:center;">
                <div data-href="https://www.youtube.com/embed/{{$link->link}}"  class="vplay" >
    <img src="/yt-vaaga.webp"  loading="lazy"  height="500" style="object-fit: cover;" alt="Youtube Math and Science Olympiad Classes" />
    </div>
              
            </div>
         </div>
      </div>
   </section>

   <section class="section  effect-section" style="background: #f7f7f759;">

      <div class="container">
         <div class="row">
            <div class="col-md-12 mb-5 text-center">
               <h3 class="h1 bg-000-after after-50px  text-warning"> What Makes us Unique</h3>

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
               "Online Classes for Every Student",        "Recorded Video Sessions",
               "Online Tuitions for Class 2 to 12",           "All Subjects & Boards ",
               "Live Private and Group Classes ",        "Comprehensive Subject coverage"

            );

            $colorsClass = array(

               "bg-info", "bg-primary", "bg-warning", "bg-black", "bg-success", "bg-danger"
            );

            $iconsClass = array(

               "fa fa-chalkboard-user", "fa fa-video", "bi bi-microsoft-teams", "fa fa-rectangle-list", "fa fa-group-arrows-rotate", "fa fa-compress"
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
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3 text-warning"> How Our Classes Work</h3>
            </div>
            <div class="col-md-12">

               <div class="main-timeline4">
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" data-wow-mobile="true">
                     <span class="timeline-content">
                        <span class="year"><i class="bi-mortarboard-fill" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Register with us</h3>
                           <p class="description">
                              Join the world of fantastic resources and strong mentor support that will greatly enrich your academic journey.
                           </p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-book-half" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Choose the subjects</h3>
                           <p class="description">
                              Choose the subjects for your academic journey to shape your future in a way that aligns with your passions and strengths. </p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.4s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-clipboard-check" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Book a Free Demo</h3>
                           <p class="description">
                              Book a free demo now to experience personalized learning, expert guidance, and the tools you need for academic success.
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.8s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-bag-heart" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Purchase the Course</h3>
                           <p class="description">
                              It’s your key to success, providing essential knowledge and skills to excel in academics and beyond.
                           </p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="1.2s">
                     <span class="timeline-content">
                        <span class="year"><i class="bi bi-calendar2-week" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Expert Tutor Assigned</h3>
                           <p class="description">

                              Assigning an expert Tutor to provide personalized guidance and boosting academic performance to excel. </p>
                        </div>
                     </span>
                  </div>
                  <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="1.6s">
                     <span class="timeline-content">
                        <span class="year"> <i class="bi bi-rocket-takeoff" style="font-size: 26px;"></i></span>
                        <div class="inner-content">
                           <h3 class="title">Let’s start your Learning Journey!!</h3>
                           <p class="description">
                              Join us today to kickstart your exciting learning adventure with VaaGa Academy and let's make it truly remarkable! 🚀 </p>
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
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3">Recent Reviews from Students</h3>

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
            <div class="pb-5 text-center"><a class="btn btn-primary" href="{{route('home.testimonials')}}">View All</a></div>
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

   <section class="section bg-gray-100 effect-section ">
      <div class="particles-box" id="particles-box-01"><canvas class="particles-js-canvas-el" width="1343" height="1054" style="width: 100%; height: 100%;"></canvas></div>

      <div class="container">

         <div class="row section-heading justify-content-center text-center wow fadeInUp" data-wow-duration="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-name: fadeInUp;">
            <div class="col-lg-8 col-xl-6">
               <h3 class="h1 bg-000-after after-50px text-warning">Star Tutors</h3>
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
               <h3 class="h1 bg-000-after after-50px pb-3 mb-3">{{env('PROJECT_NAME')}} Achievement</h3>
            </div>
         </div>

         <div class="row">
            <div class="col-6 col-lg-3 col-md-6 my-3">
               <!-- <div class="border-2 bg-white line-hover p-4 rounded text-center"> -->
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #ffbe3d4a;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-laptop"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement->courses_offered}}">{{$achievement->courses_offered}}</span>+</h6>
                  <span>Courses offered </span>
               </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #4a2a514f;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-people-fill"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement->happy_students}}">{{$achievement->happy_students}}</span>+</h6>
                  <span>Happy Students </span>
               </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #50b5ff30;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-person"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement->expert_tutor}}">{{$achievement->expert_tutor}}</span>+</h6>
                  <span>Expert Tutors </span>
               </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 my-3">
               <div class="line-hover hover-top p-4 rounded text-center" style="background-color: #5cc9a747;">
                  <div class="only-icon only-icon-lg d-inline-block mb-3"><i class="bi-chat"></i></div>
                  <h6 class=" h3 mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{$achievement->hours_taught}}">{{$achievement->hours_taught}}</span>+</h6>
                  <span>Hours taught</span>
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