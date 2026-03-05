<?php
   use App\Models\Category;
   use App\Models\Board;
   use App\Models\Coupon;
   ?>
<?php 
   $bd = $course->board_id ? Board::find($course->board_id) : null;
   
   // Generate meta data dynamically if not set in database
   $courseTitle = $course->title;
   $metaTitle = $course->meta_title;
   $metaDescription = $course->meta_description;
   $metaKeywords = $course->meta_keywords;
   
   // If meta is empty, generate from course title
   if (empty($metaDescription)) {
       // Extract class number and subject from title
       $classNum = '';
       $subject = '';
       $exam = '';
       
       if (preg_match('/Class\s+(\d+)/i', $courseTitle, $matches)) {
           $classNum = $matches[1];
       }
       
       if (stripos($courseTitle, 'Maths') !== false) {
           $subject = 'Maths';
           $exam = 'IMO';
       } elseif (stripos($courseTitle, 'Science') !== false) {
           $subject = 'Science';
           $exam = 'NSO';
       } elseif (stripos($courseTitle, 'English') !== false) {
           $subject = 'English';
           $exam = 'IEO';
       }
       
       if ($classNum && $subject) {
           $metaTitle = "{$subject} Olympiad for Class {$classNum} - VaaGa Academy";
           $metaDescription = "VaaGa Academy offers expert {$subject} Olympiad coaching for Class {$classNum} students. Prepare for {$exam} exams with interactive live classes, mock tests, and personalized feedback.";
           $metaKeywords = "{$subject} Olympiad Class {$classNum}, {$exam} Class {$classNum}, {$subject} Olympiad preparation, {$subject} Olympiad coaching online, {$exam} preparation Class {$classNum}, {$subject} Olympiad training, {$subject} Olympiad mock tests, SOF Olympiad, Olympiad exams Class {$classNum}, Online Olympiad coaching, Live Olympiad classes, VaaGa Academy, Best Olympiad coaching, Olympiad study material";
       } else {
           // Fallback
           $metaTitle = $courseTitle . ' - VaaGa Academy';
           $metaDescription = 'VaaGa Academy offers expert Olympiad coaching for students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.';
           $metaKeywords = 'Olympiad coaching, IMO preparation, NSO preparation, IEO preparation, Online Olympiad classes, VaaGa Academy';
       }
   }
   ?>
@extends('frontend.layout.sub-master')
@section('title')
<title>{{ $metaTitle }} | {{env('APP_NAME')}}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $metaTitle }} | {{env('APP_NAME')}}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:url" content="{{URL::to('/courses')}}/{{$course->slug}}" />
<meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
<meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-0 days',strtotime($course->created_at)))}}" />
<meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-0 days',strtotime($course->updated_at)))}}" />
<meta property="og:image" content="https://www.vaagaacademy.com/storage/uploads/{{$course->course_image}}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="{{ $metaTitle }} | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="{{ $metaDescription }}" />
<meta name="twitter:image" content="https://www.vaagaacademy.com/storage/uploads/{{$course->course_image}}" />
<link rel="canonical" href="{{URL::to('/courses')}}/{{$course->slug}}">
@stop
@section('content')
<style type="text/css">
   .accordion-body ul {
   margin-left: 0px !important;
   padding-left: 0px;
   }
   .pricing-box {
   padding: 0px 3px;
   margin-bottom: 15px;
   }
   .pricing-box-head {
   background: #4A2A51;
   text-align: center;
   padding: 6px 0px;
   text-transform: uppercase;
   color: #fff;
   border-top-left-radius: 6px;
   border-top-right-radius: 6px;
   font-size: 14px;
   }
   .pricing-box-body {
   background: #f7f7f7;
   }
   .price_list_ul{
   text-align: left;
   list-style: none;
   padding-left: 0;
   }
   .bg-dif{
   background: #0000000d;
   }
   ul.price_list_ul li {
   cursor: pointer;
   padding: 3px 3px;
   }
   ul.price_list_ul{
   margin-bottom: 0px;
   }
   ul.price_list_ul li label {
   cursor: pointer;
   }
   ul.price_list_ul li input {
   margin-right: 6px;
   }
   span.saved {
   color: #4CAF50;
   }
   span.old_price {
   text-decoration: line-through;
   font-size: 15px;
   }
   span.pricing_type {
   font-size: 14px;
   color: #979491;
   }
   span.price {
   color: #15db95;
   font-weight: 700;
   font-size: 15px;
   }
   .fn18{
   font-size: 18px;
   }
   @media only screen and (max-width: 600px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   @media only screen and (min-width: 600px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   @media only screen and (min-width: 768px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   @media only screen and (min-width: 992px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 16px;
   }
   }
   .bgx {
   display: inline-block;
   width: 25px;
   height: 25px;
   background: #fff;
   padding: 1px 3px;
   margin-right: 6px;
   border-radius: 24px;
   }
   .bgx img{
   margin-top: -4px;
   }
   .rc-anchor-normal .rc-anchor-content {
   height: 74px;
   width: 52% !important;
   }
   .rc-anchor-normal .rc-anchor-checkbox-label {
   width: auto !important;
   }
   .rc-anchor-normal .rc-anchor-pt {
   margin: 2px 11px 0 0;
   padding-right: 2px;
   position: absolute;
   right: 50%;
   text-align: right;
   width: auto !important;
   }
   .rc-anchor-light {
   background: #f9f9f9;
   color: #bf1e1e !important;
   }
   p{
      color:#000;
   }
   .accordion-button::after {
    flex-shrink: 0;
    width: unset;
    height: var(--bs-accordion-btn-icon-width);
    margin-left: auto;
    content: "";
    background-image: var(--bs-accordion-btn-icon);
    background-repeat: no-repeat;
    background-size: var(--bs-accordion-btn-icon-width);
    transition: var(--bs-accordion-btn-icon-transition);
}
</style>

<main>
   <section class="section bg-gray-100 border-bottom">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8 text-center pb-10 wow fadeInUp pt-8" data-wow-duration="0.5s">
               <h1 class="h1 mb-3 text-warning">  
                  @if($bd) {{$bd->name}} | @endif
                  @if($pcategory) {{$pcategory->name}} @else {{$category->name}} @endif
                  | {{$course->title}}
               </h1>
            </div>
         </div>
      </div>
   </section>
   <section class="section pt-4">
      <div class="container mt-0">
         <div class="row align-items-start gy-4">
            <div class="col-lg-7 col-xxl-8 wow fadeInUp" data-wow-duration="0.5s">
               <div class="row">
                  <div class="col-md-12">
                     <div class="card shadow-lg">
                        <div class="card-body">
                           <h5 class="mb-3 pt-2">Overview</h5>
                           <div class="nav mb-3">
                              @if($course) {!! $course->description !!} @endif
                           </div>
                        </div>
                     </div>
                  </div>
                  @if(count($clist) > 0)
                  <div class="col-md-12 pt-5">
                     <h3 class="mb-3 fn18">Course Content</h3>
                     <div class="accordion shadow" id="accordionExample_03">
                        @php $count = 0; @endphp
                        @foreach($clist as $ct)
                        @php $count++ @endphp
                        <div class="accordion-item">
                           <p class="m-0 accordion-header" id="heading_03_{{$count}}">
                              <button class="py-3 accordion-button fw-bold" type="button" data-bs-toggle="collapse" aria-expanded="true">{{$ct->title}}</button>
                           </p>
                        </div>
                        @endforeach
                     </div>
                  </div>
                  @endif
               </div>
            </div>
            <div class="col-lg-5 col-xxl-4 sticky-lg-top sticky-lg-top-header wow fadeInUp" data-wow-duration="0.5s">
               <div class="row">
                  <div class="col-md-12">
                     <div class="card shadow-lg">
                        <div class="card-body">
                           <form action="/courses/{{$course->slug}}/buy" method="get" class="priceForm">
                              <div>
                                 <img class="" src="{{asset('storage/uploads/'.$course->course_image)}}" onerror='this.src="/newassets/img/logo.png"' alt="{{$course->title}}" style="height: 200px;">
                              </div>
                              @if(!$purchased_course)
                              <div style="text-align: center; margin-top: 15px;">
                                 <a href="/userlogin?redirect={{env('APP_URL')}}/courses/{{$course->slug}}" class="btn btn-warning w-100">Enquire Now</a>
                              </div>
                              @else
                              <div style="text-align: center; margin-top: 15px;">
                                 <h5>You have already purchased this course</h5>
                                 <a href="/user/dashboard" class="btn btn-warning btn-sm">Visit Dashboard</a>
                              </div>
                              @endif
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@stop
