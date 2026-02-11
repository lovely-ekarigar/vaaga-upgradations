@extends('frontend.layout.sub-master')
@section('title')
<title>Blogs | {{env('APP_NAME')}}</title>

<meta name="description" content="Empower your education journey with live online classes on VaaGa Academy, India's top learning platform for school students.">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Blogs | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Empower your education journey with live online classes on VaaGa Academy, India's top learning platform for school students." />
    <meta property="og:url" content="{{URL::to('/blog')}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="VaaGa Academy | Online Learning Platforms For School Students" />
<meta name="twitter:description" content="Empower your education journey with live online classes on VaaGa Academy, India's top learning platform for school students." />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/blog')}}">

@stop
@section('page_css')
    <style>
        .couse-pagination li.active {
            color: #333333!important;
            font-weight: 700;
        }
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
        }
        .cat-item.active{
            background: black;
            color: white;
            font-weight: bold;
        }
        li.page-item {
    display: inline-block;
}
    </style>
@stop
@section('content')

<section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url(https://www.vaagaacademy.com/newassets/img/bg/bg-page-header.jpeg); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1 ">@if($category) {{$category->name}}  @else Blogs @endif</h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Blogs</li>
              </ol>
            </div>
         </div>
      </div> 
   </section> 
   
  
<section id="blog-item" class="py-6 section" style="background-color:#f9f9f9 !important">
        <div class="container">
            <div class="row pt-5 g-4">
                  @foreach($blogs as $item)
                <div class="col-12 col-sm-6 col-md-4">
                            <div class="card shadow-xs">
                                <a href="{{route('blogs.index',['slug'=> $item->slug])}}">
                                    <img class="card-img-top" src="{{asset('storage/uploads/'.$item->image)}}" style="height: 250px;" title="{{$item->title}}" alt="{{$item->title}}">
                                </a> 
                                <div class="card-body p-3">
                                    <h6 class="text-primary fw-500 mb-3 d-flex text-uppercase fs-xs letter-spacing-1"> {{$item->created_at->format('d M Y')}}</h6>
                                    <h3 class="h5 mb-3">
                                        <a class="text-reset" href="{{route('blogs.index',['slug'=> $item->slug])}}">{{$item->title}}</a>
                                    </h3>
                                   
                                   
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                </div>
                
                <div class="row">
                    <div class="col-md-12" style="text-align: center;
    margin: 26px 0px;">
                        {{$blogs->links()}}
                    </div>
                </div>
            </div>
            </section>
 

@endsection

