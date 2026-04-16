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
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $metaTitle }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:url" content="{{URL::to('/courses')}}/{{$course->slug}}" />
<meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
<meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-0 days',strtotime($course->created_at)))}}" />
<meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-0 days',strtotime($course->updated_at)))}}" />
<meta property="og:image" content="https://www.vaagaacademy.com/storage/uploads/{{$course->course_image}}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="{{ $metaTitle }}" />
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
   /* border: 1px solid red; */
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
   /*    padding: 3px 5px;*/
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
   /*    font-weight: 700;*/
   }
   span.price {
   color: #15db95;
   font-weight: 700;
   font-size: 15px;
   /* margin-top: -11px; */
   }
   .fn18{
   font-size: 18px;
   }
   /* Extra small devices (phones, 600px and down) */
   @media only screen and (max-width: 600px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   /* Small devices (portrait tablets and large phones, 600px and up) */
   @media only screen and (min-width: 600px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   /* Medium devices (landscape tablets, 768px and up) */
   @media only screen and (min-width: 768px) {
   .accordion-body ul li a, .accordion-button{
   font-size: 14px;
   }
   }
   /* Large devices (laptops/desktops, 992px and up) */
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
.heading_meta_h1 {
    color: #fff !important;
    font-size: 34px;
    margin-bottom: -5% !important;
    margin-top: 5%;
}
</style>

<main>
   <!-- Section -->
   <section class="section bg-gray-100 border-bottom">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8 text-center pb-10 wow fadeInUp pt-8" data-wow-duration="0.5s">
                <h1 class="h1 mb-3 text-warning heading_meta_h1">{{ $metaTitle }}</h1>

               <!--<h1 class="h1 mb-3 text-warning">  -->
               <!--   @if($bd)-->
               <!--   {{$bd->name}} | -->
               <!--   @endif-->
               <!--   @if($pcategory)-->
               <!--   {{$pcategory->name}}-->
               <!--   @else-->
               <!--   {{$category->name}}-->
               <!--   @endif-->
               <!--   | {{$course->title}}-->
               <!--</h1>-->
            </div>
         </div>
      </div>
   </section>
   <!-- End Section --><!-- Section -->
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
                              @if($course)
                              {!! $course->description !!}
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>
                  @if(count($clist)  > 0)
                  <div class="col-md-12 pt-5">
                     <h3 class="mb-3 fn18">Course Content</h3>
                     <div class="accordion shadow" id="accordionExample_03">
                        @php $count = 0; @endphp
                        @foreach($clist as $ct)
                        @php $count++ @endphp
                        <div class="accordion-item">
                           <p class="m-0 accordion-header" id="heading_03_{{$count}}">
                              <button class="py-3 accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="true" aria-controls="collapse_03_1">{{$ct->title}}</button>
                           </p>
                           <div id="" class="accordion-collapse collapse " aria-labelledby="heading_03_{{$count}}" data-bs-parent="#accordionExample_03">
                              <div class="accordion-body">
                                 <ul style="list-style: none;">
                                    @foreach($ct->lessons as $cl)
                                    <li class="mb-3">
                                       <a href="javascript:void(0)" style="cursor:auto;" class="d-flex align-items-center justify-content-between">
                                       <span>
                                       <img src="/frontend/assets/img/icon/play.svg" alt="" class="me-2">
                                       {{$cl->title}}
                                       </span>
                                       <span>
                                       <?php 
                                          if($cl->duration){
                                          $dur=explode(":", $cl->duration);
                                           if($dur[0]!="00"){
                                            echo $dur[0].":".$dur[1].":".$dur[2];
                                           }else{
                                            echo $dur[1].":".$dur[2];
                                           }
                                          }
                                          
                                            ?>
                                       </span>
                                       </a>
                                    </li>
                                    @endforeach
                                 </ul>
                              </div>
                           </div>
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
                              @if(1)
                              @if(1)
                              @if(!$purchased_course)
                              <div class="cus-prie pt-2 pb-2" style="text-align: center;">
                                 <?php
                                    $priceFound=false;
                                    
                                    $cop = new Coupon;
                                    $price = $cop->applyCoupon($course->coupon_id,$course->price); 
                                    $price_1 = $cop->applyCoupon($course->coupon_id_1,$course->price_1); 
                                    $monthly_price = $cop->applyCoupon($course->coupon_id_monthly_price,$course->monthly_price); 
                                    $regular_monthly = $course->regular_monthly;
                                    $regular_monthly_1 = $course->regular_monthly_1;
                                    $monthly_price_1 = $cop->applyCoupon($course->coupon_id_monthly_price_1,$course->monthly_price_1); 
                                    $cp = Coupon::find($course->coupon_id);
                                    ?>
                                 @if($cp)
                                 <input type="hidden" name="coupon" value="{{$cp->code}}">
                                 @endif
                              </div>
                              <div class="row">
                                 @if($course->price_1 || $course->monthly_price_1 || $course->regular_monthly_1)
                                 <?php   $priceFound=true; ?>
                                 <div class="col-md-6 col-sm-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="pricing-box">
                                       <div class="pricing-box-head">
                                          <div class="bgx">
                                             <img src="/user.png">
                                          </div>
                                          1-on-1 Classes

                                       </div>
                                       <div class="pricing-box-body">
                                          <ul class="price_list_ul">
                                             @if($course->price_1)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id_1);
                                                ?>
                                             <li>
                                                <label>
                                                <input type="radio" required class="form-check-input" data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="onetoone_full"> 
                                                @if($price_1<$course->price_1)
                                                <span class="old_price">₹{{round($course->price_1)}}</span>
                                                <span class="price">₹{{round($price_1)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->price_1)}}</span>
                                                @endif
                                                <br>
                                                <span class="pricing_type">Full Course</span>
                                                </label>
                                             </li>
                                             @endif
                                             @if($course->monthly_price_1)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id_monthly_price_1);
                                                ?>
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input"  data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="onetoone_monthly">
                                                @if($monthly_price_1<$course->monthly_price_1)
                                                <span class="old_price">₹{{round($course->monthly_price_1)}}</span>
                                                <span class="price">₹{{round($monthly_price_1)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->monthly_price_1)}}</span>
                                                @endif
                                                <br>
                                                <span class="pricing_type">Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                              @if($course->regular_monthly_1)
                                             <li>
                                                <label>
                                                <input type="radio" required class="form-check-input" data-coupon="" name="course_mode" value="regular_monthly_1">
                                                <span class="price">₹{{round($regular_monthly_1)}}</span>
                                                <br>
                                                <span class="pricing_type">1:1 Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 @endif
                                 @if($course->price || $course->monthly_price || $course->regular_monthly)
                                 <?php   $priceFound=true; ?>
                                 <div class="col-md-6 col-sm-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="pricing-box">
                                       <div class="pricing-box-head">
                                            <div class="bgx">
                                          <img src="/people.png">
                                         </div>
                                         Group Classes

                                       </div>
                                       <div class="pricing-box-body">
                                          <ul class="price_list_ul">
                                                      @if($course->full_price)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id_full_price);
                                                ?>
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input"  data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="full"> 
                                                @if($monthly_price<$course->monthly_price)
                                                <span class="old_price">₹{{round($course->monthly_price)}}</span>
                                                <span class="price">₹{{round($monthly_price)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->full_price)}}</span>
                                                @endif
                                                <br>
                                                <span class="pricing_type">Full Course</span>
                                                </label>
                                             </li>
                                             @endif
                                             @if($course->price)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id);
                                                ?>
                                             <li>
                                                <label>
                                                <input type="radio" required class="form-check-input"  data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="onetomany_full">
                                                @if($price<$course->price)
                                                <span class="old_price">₹ {{round($course->price)}}</span>
                                                <span class="price">₹{{round($price)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->price)}}</span>
                                                @endif <br>
                                                <span class="pricing_type">Full Course</span>
                                                </label>
                                             </li>
                                             @endif
                                             @if($course->monthly_price)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id_monthly_price);
                                                ?>
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input"  data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="monthly"> 
                                                @if($monthly_price<$course->monthly_price)
                                                <span class="old_price">₹{{round($course->monthly_price)}}</span>
                                                <span class="price">₹{{round($monthly_price)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->monthly_price)}}</span>
                                                @endif
                                                <br>
                                                <span class="pricing_type">Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                              @if($course->regular_monthly)
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input" data-coupon="" name="course_mode" value="regular_monthly">
                                                <span class="price">₹{{round($regular_monthly)}}</span>
                                                <br>
                                                <span class="pricing_type">Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 @endif
                                 @if($course->price || $course->quarterly_price)
                                 <?php   $priceFound=true; ?>
                                 <div class="col-md-6 col-sm-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="pricing-box">
                                       <div class="pricing-box-head">
                                               <div class="bgx">
                                          <img src="/people.png">
                                         </div>
                                          Group Classes
                                       </div>
                                       <div class="pricing-box-body">
                                          <ul class="price_list_ul">
                                            
                                             @if($course->quarterly_price)
                                             <?php 
                                                $cp = Coupon::find($course->coupon_id_quarterly_price);
                                                ?>
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input"  data-coupon="@if($cp) {{$cp->code}} @endif" name="course_mode" value="quarterly"> 
                                                @if($monthly_price<$course->monthly_price)
                                                <span class="old_price">₹{{round($course->monthly_price)}}</span>
                                                <span class="price">₹{{round($monthly_price)}}</span>
                                                @else
                                                <span class="price">₹{{round($course->quarterly_price)}}</span>
                                                @endif
                                                <br>
                                                <span class="pricing_type">Quarterly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                                                  @endif
                                 {{-- @if($course->regular_monthly || $course->regular_monthly_1)
                                 <?php   $priceFound=true; ?>
                                 <div class="col-md-6 col-sm-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="pricing-box">
                                       <div class="pricing-box-head">
                                          <div class="bgx">
                                             <img src="/people.png">
                                          </div>
                                          Regular Classes
                                       </div>
                                       <div class="pricing-box-body">
                                          <ul class="price_list_ul">
                                             @if($course->regular_monthly_1)
                                             <li>
                                                <label>
                                                <input type="radio" required class="form-check-input" data-coupon="" name="course_mode" value="regular_monthly_1">
                                                <span class="price">₹{{round($regular_monthly_1)}}</span>
                                                <br>
                                                <span class="pricing_type">1:1 Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                             @if($course->regular_monthly)
                                             <li class="bg-dif">
                                                <label>
                                                <input type="radio" required class="form-check-input" data-coupon="" name="course_mode" value="regular_monthly">
                                                <span class="price">₹{{round($regular_monthly)}}</span>
                                                <br>
                                                <span class="pricing_type">Monthly Subscription</span>
                                                </label>
                                             </li>
                                             @endif
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 @endif --}}
                                 <!--@if($course->price || $course->full_price)-->
                                 <?php   $priceFound=true; ?>
                                 <!--<div class="col-md-6 col-sm-6" style="padding-left: 0px;padding-right: 0px;">-->
                                 <!--   <div class="pricing-box">-->
                                 <!--      <div class="pricing-box-head">-->
                                 <!--        Full Course-->
                                 <!--      </div>-->
                                 <!--      <div class="pricing-box-body">-->
                                 <!--         <ul class="price_list_ul">-->
                                           
                            
                                 <!--         </ul>-->
                                 <!--      </div>-->
                                 <!--   </div>-->
                                 <!--</div>-->
                                 <!--@endif-->
                              </div>
                              <div style="text-align: center;">
                                 @if($priceFound)
                                 <input type="submit" name="" class="btn btn-warning w-100" value="Buy this course">
                                 @else
                                 <p>You can not purchase this course</p>
                                 @endif
                              </div>
                              @else
                              <div style="text-align: center;
                                 margin-top: 15px;">
                                 <h5>You have already purchased this course</h5>
                                 <a href="/user/dashboard" class="btn btn-warning btn-sm">Visit Dashboard</a>
                              </div>
                              @endif
                              @endif
                              @else
                              <br>
                              <p class="text-center"><br><a href="/userlogin?redirect={{env('APP_URL')}}/courses/{{$course->slug}}" class="btn btn-sm btn-warning">Enquire Now</a></p>
                              @endif
                              @if($course->duration_text)
                              <!--<div style="text-align: center;margin-bottom: 15px;">-->
                              <!--   Duration : <span style="font-weight: 700;">{{$course->duration_text}}</span>-->
                              <!--   <br>-->
                              <!--</div>-->
                              @endif
                           </form>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12" id="demo">
                     <div class="card shadow-lg mt-5">
                        <div class="card-body">
                           <h3 class="h5 mb-4">Book Free Demo Class</h3>
                           <form class="rd-mailformx" id="demo-form" method="post" action="{{ route('courses.demo', $course->slug) }}" >
                              @csrf 
                              <input type="hidden" name="course_id" value="{{$course->id}}" />
                              <div class="mb-3">
                                 <input id="contact-name" type="text" name="name" placeholder="Full Name" value="{{old('name')}}" class="form-control @if($errors->has('name')) is-invalid @endif">
                                 @if($errors->has('name'))
                                 <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                 @endif
                              </div>
                              <div class="mb-3">
                                 <input id="contact-email" type="email" name="email" placeholder="Email"  value="{{old('email')}}" class="form-control  @if($errors->has('email')) is-invalid @endif">
                                 @if($errors->has('email'))
                                 <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                 @endif
                              </div>
                              <div class="mb-3">
                                 <input id="contact-phone" tyype="tel" value="{{old('phone')}}"  class="form-control  @if($errors->has('phone')) is-invalid @endif" 
                                 oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" pattern="\d{10}" placeholder="Phone Number" name="phone" required>
                                 @if($errors->has('phone'))
                                 <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                 @endif
                              </div>
                              <div class="mb15">
                                 <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                              </div>
                              <div>
                                 <button class="btn btn-primary w-100 " type="submit">Submit</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
   </section>
   <!-- End Section -->
</main>
@stop
@section('page_js')
<script>
   function onSubmit(token) {
         console.log(token);
       document.getElementById("demo-form").submit();
     }
   $('input[name="course_mode"]').on('click',function(){
      $('input[name="coupon"]').val($(this).data('coupon'));
   })
   
</script>
<?php if(Session::has('success')) { ?>
<script>
   $(document).ready(function(){
   
   
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
@stop
