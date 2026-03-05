@extends('frontend.layout.sub-master')
@section('title')
<title>{{ $page->meta_title ?? $page->title . ' | ' . env('APP_NAME') }}</title>
<meta name="description" content="{{ $page->meta_description ?? '' }}">
<meta name="keywords" content="{{ $page->meta_keywords ?? '' }}">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $page->meta_title ?? $page->title }}" />
<meta property="og:description" content="{{ $page->meta_description ?? '' }}" />
<meta property="og:url" content="{{ URL::to('/' . $page->slug) }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />
<meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{ env('TWITTER_HANDLE') }}" />
<meta name="twitter:title" content="{{ $page->meta_title ?? $page->title }}" />
<meta name="twitter:description" content="{{ $page->meta_description ?? '' }}" />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{ URL::to('/' . $page->slug) }}">
@stop
@section('page_css')
<style>
    
@media  only screen and (max-width: 600px) {
        .mtp-46{
  margin-top: 46px !important;
}
}
</style>
@stop
@section('content')

<main>

   <section class="bg-primary effect-section page-heading-pad mtp-46" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8 ">
               <h2 class="text-white h1">{{$page->title}}</h2>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{$page->title}}</li>
              </ol>
            </div>
         </div>
      </div>
   </section>

    <section class="section">
   <div class="container">
      <div class="row align-items-center justify-content-between ">
         <div class="col-lg-12 my-3 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.1s; animation-name: fadeInLeft;">
           
           
            @if($page->image != "")
                        <center>
                                <img src="{{asset('storage/uploads/'.$page->image)}}" alt="" style="width:100%;height:100%">
                         </center>
                         <br>
                    @endif
              
              
              @include('includes.editorjs-parser', ['content' => $page->content])

         </div>
        
      </div>
   </div>
</section>
        


@stop