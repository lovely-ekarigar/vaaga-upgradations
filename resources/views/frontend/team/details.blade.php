@extends('frontend.layout.sub-master')
@section('title')
<title>{{$team->name}} | {{env('APP_NAME')}}</title>
<meta name="description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Our Teams | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!" />
    <meta property="og:url" content="{{URL::to('/contact')}}" />
    <meta property="og:site_name" content="Live Online Learning Classes for School Students | {{env('APP_NAME')}}" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Our Teams | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!" />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/our-teams')}}">

@stop
@section('content')


<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1 ">{{$team->name}}</h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <!--<li class="breadcrumb-item active">Our Teams</li>-->
              </ol>
            </div>
         </div>
      </div>
   </section>
   
   
    <section id="blog-item" class="py-6 section" style="background-color:#f9f9f9 !important">
        <div class="container">
           
            <div class="row pt-5 g-4 justify-content-center">
                
                <div class="col-12 col-sm-6 col-md-4 px-4 mx-auto text-center">
                            
                               <img class="img-fluid rounded-circle" src="/{{$team->image}}" style="height: 250px; width:250px;" title="{{$team->name}}" alt="{{$team->name}}">
                            
                </div>
                
                 <div class="col-12 col-sm-6 col-md-8 px-4 px-md-2 align-items-center text-center text-md-start ">
                     <h2>{{$team->name}}</h2>
                     <h6>{{$team->designation}}</h6>
                     
                     <div class="pb-4">
                         <div class="">
                        <a class="icon-sm  rounded-circle me-2" href="{{$team->facebook}}" target="_blank"><i style="font-size: 20px;color:#1877f2" class="bi-facebook"></i> </a>
                        <a class="icon-sm  rounded-circle me-2" href="{{$team->instagram}}" target="_blank"><i style="font-size: 20px;color:#d74d5d" class="bi-instagram"></i> </a>
                        <a class="icon-sm  rounded-circle me-2" href="{{$team->linkdin}}" target="_blank"><i style="font-size: 20px;color:#0a66c2" class="bi-linkedin"></i></a>
                        <a class="icon-sm  rounded-circle me-2" href="{{$team->youtube}}" target="_blank"><i style="    font-size: 25px;color:#d74d5d" class="bi-youtube"></i></a>
                        <a class="icon-sm  rounded-circle me-2" href="{{$team->twitter}}" target="_blank"><i style="font-size: 22px;" class="bi bi-twitter-x"></i></a>
                     </div>
                     </div>
                     <div>
                         {!! $team->about  !!}
                     </div>
                           
                </div>
                    
                        
                </div>
             
            </div>
            </section>
   
      </main>

@stop
@section('page_js')
@stop
   