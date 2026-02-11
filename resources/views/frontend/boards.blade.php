@extends('frontend.layout.sub-master')
@section('title')
<title>{{$find_parent_category->meta_title}}| {{env('APP_NAME')}}</title>

<meta name="description" content="{{$find_parent_category->meta_description}}">
<meta name="keywords" content="{{$find_parent_category->meta_keyword}}">

<meta property="og:locale" content="en_US" /> 
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Boards| {{env('APP_NAME')}}" />
    <meta property="og:description" content="{{$find_parent_category->meta_description}}" />
    <meta property="og:url" content="{{URL::to('/')}}/category/{{$find_parent_category->slug}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Boards| {{env('APP_NAME')}}" />
<meta name="twitter:description" content="{{$find_parent_category->meta_description}}" /> 
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/')}}/category/{{$find_parent_category->slug}}">


@stop
@section('page_css')
<style type="text/css">
  .card-body {
    padding: 8px 0px 6px 0px !important;
}
@media  only screen and (max-width: 600px) {
        .mtp-20{
  margin-top: 20px !important;
}
}
</style>
@stop
@section('content')

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1">{{$find_parent_category->name}}</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li> 
                <li class="breadcrumb-item active">{{$find_parent_category->name}}</li>
              </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 my-3">
            <div class="row">
           
               @if($boards->count() > 0)
               @foreach($boards as $board)

              <div class="col-sm-4 col-lg-4 mb-sm-3 pb-3">
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="position-relative hover-scale-in text-center py-3" style="padding-top: 0px !important;">
                      <a href="{{route('academic',['slug' =>$board->slug,'cat' =>$find_parent_category->slug])}}">
                        <img class="card-img-top" src="{{asset('storage/uploads/'.$board->board_image)}}" onerror='this.src="/newassets/img/logo.png"' style="    width: 100%;
    height: 110px;" title="{{$board->name}}" alt="{{$board->name}}">
                      </a>
                    </div>
                    <div class="card-body text-center">
                      <h3 class="h3">
                        <a class="text-reset stretched-link" href="{{route('academic',['slug' =>$board->slug,'cat' =>$find_parent_category->slug])}}">{{$board->name}}</a>
                      </h3>
                    </div>
                  </div>
                </div>

              @endforeach 
              @else

              <div class="col-sm-12 col-lg-12">
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="card-body text-center">
                      <div class="py-4">
                        <img src="{{asset('newassets/img/no-data-found.png')}}">
                      </div>
                        <h4 class="h4 pb-4 ">No Course Found</h2>
                    </div>
                  </div>
                </div>

              @endif
                

            </div>
          </div>
          <div class="col-lg-4 my-3">
            <div class="card">
              <div class="card-header bg-transparent p-3">
                <span class="h5 m-0">Categories</span>
              </div>
             <div class="list-group list-group-flush">
                @foreach($categories as $catego)
                <a href="{{route('category',['slug'=>$catego->slug])}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
                  <div>
                    <span>{{$catego->name}}</span>
                  </div>
                  <div>
                    <i class="bi bi-chevron-right"></i>
                  </div>
                </a>
                @endforeach
              </div> 
            </div>
          </div>
          <div class="col-lg-12 my-3">
             <div class="card card-body" style="padding: 20px 20px 20px 20px !important;">
                <h5>{{$find_parent_category->name}}</h5>
                <p>{!!$find_parent_category->description!!}</p>
             </div>
          </div>
        </div>
      </div>
    </section>
</main>

@stop