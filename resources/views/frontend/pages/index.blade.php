@extends('frontend.layout.sub-master')
@section('title')
<title>{{$page->title}} | {{env('APP_NAME')}}</title>
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
              
              
              {!! $page->content !!}

         </div>
        
      </div>
   </div>
</section>
        


@stop