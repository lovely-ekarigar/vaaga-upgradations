@extends('frontend.layout.sub-master')
@section('title')
<title>  {{$category->name}} | {{env('APP_NAME')}}</title>
<meta name="description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">


@stop
@section('content')


<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1 ">
                  {{$category->name}}
               </h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item "><a href="#"> Notes</a> </li>
                <li class="breadcrumb-item active"> Categories</li>
              </ol>
            </div>
         </div>
      </div>
   </section>
   
   <section id="blog-item" class="py-6 section" style="background-color:#f9f9f9 !important">
        <div class="container">
            <div class="row pt-5 g-4">
                  @foreach($notes as $item)
                <div class="col-12 col-sm-6 col-md-4">
                            <div class="card shadow-xs">
                                <a href="{{route('frontend.note.noteDeatails',['slug'=> $item->slug])}}">
                                    <img class="card-img-top" src="/{{$item->image}}"  onerror="this.src='https://www.vaagaacademy.com/newassets/img/logo.webp'"  style="height: 250px;" title="{{$item->name}}" alt="{{$item->name}}">
                                </a> 
                                <div class="card-body p-3">
                                    <h3 class="h5 mb-3">
                                        <a class="text-reset" href="{{route('frontend.note.noteDeatails',['slug'=> $item->slug])}}">{{$item->name}}</a>
                                    </h3>
                                   
                                   
                                </div>
                            </div>
                        </div>
                        @endforeach
                
                        
                </div>
                
            </div>
            </section>
            
            
<section id="blog-item" class="py-6" style="background-color:#f9f9f9 !important">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="card shadow-lg">
               <div class="card-body">
                  <figure class="figure lightbox-gallery">
                     <!--<img alt="" src="/{{$category->image}}"  onerror="this.src='https://www.vaagaacademy.com/newassets/img/logo.webp'"  class="img-fluid shadow rounded">-->
                  </figure>
                  <!--<h2>{{$category->name}}</h2>-->
                  <div class="row d-flex w-100 justify-content-between">
                      
                  </div>
                  {!! $category->description !!}
                  <br>
              
                  
               </div>
            </div>
            <br><br>
         </div>
         
             
                    
      </div>
   </div>
</section>

   </main>

@stop


@section('page_js')

@stop