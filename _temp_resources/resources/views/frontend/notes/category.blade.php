@extends('frontend.layout.sub-master')
@section('title')
<title>Categories | {{env('APP_NAME')}}</title>
<meta name="description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Categories | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!" />
    <meta property="og:url" content="{{URL::to('/contact')}}" />
    <meta property="og:site_name" content="Live Online Learning Classes for School Students | {{env('APP_NAME')}}" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Categories | {{env('APP_NAME')}}" />
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
               <h1 class="text-white h1 ">Notes</h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
              </ol>
            </div>
         </div>
      </div>
   </section>
   
   
    <!--<section id="blog-item" class="py-6 section" style="background-color:#f9f9f9 !important">-->
    <!--    <div class="container">-->
    <!--        <div class="row pt-5 g-4">-->
                
                 
    <!--           @foreach($notes as $note)-->
    <!--            <div class="col-12 col-sm-12 col-md-8">-->
    <!--                        <div class="card shadow-xs">-->
    <!--                            <div class="card">-->

    <!--                          <div class="card-body">-->
    <!--                            <h5 class="card-title">-->
    <!--                                {{$note->name}}-->
    <!--                                </h5>-->
    <!--                            <div class="row">-->
    <!--                                <div class="col-md-8">-->
    <!--                                    @if($note->category)-->
    <!--                                    {{$note->category->name}}-->
    <!--                                    @endif-->
    <!--                                </div>-->
    <!--                                <div class="col-md-4 text-end">-->
    <!--                                    <a href="#" >View More...</a>-->
    <!--                                </div>-->
    <!--                            </div>-->
                                
                                
    <!--                          </div>-->
    <!--                        </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                @endforeach-->
                        
    <!--            </div>-->
                
   
    <!--        </div>-->
    <!--        </section>-->
            
   
   
   <section id="blog-item" class="py-6 section" style="background-color:#f9f9f9 !important">
        <div class="container">
            <div class="row pt-5 g-4">
                
                  @foreach($categories as $item)
               
                <div class="col-12 col-sm-12 col-md-4">
                            <div class="card shadow-xs">
                                <a href="{{route('frontend.note.categoryDetails',['slug'=> $item->slug])}}">
                                    <img class="card-img-top" src="/{{$item->image}}"  onerror="this.src='https://www.vaagaacademy.com/newassets/img/logo.webp'"  style="height: 250px;" title="{{$item->name}}" alt="{{$item->name}}">
                                </a> 
                                <div class="card-body p-3">
                                    <!--<h6 class="text-primary fw-500 mb-3 d-flex text-uppercase fs-xs letter-spacing-1"> </h6>-->
                                    <h3 class="h5 mb-3">
                                        <!--<a class="text-reset" href="#">{{$item->name}}</a>-->
                                        <a class="text-reset" href="{{route('frontend.note.categoryDetails',['slug'=> $item->slug])}}">{{$item->name}}</a>
                                    </h3>
                                   
                                   
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                </div>
                
   
            </div>
            </section>
   
   </main>

@stop


@section('page_js')

@stop