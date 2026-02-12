<?php
use App\Models\Board;
?>
@extends('frontend.layout.sub-master')
@section('title')
<title>Courses | {{env('APP_NAME')}}</title> 
@stop
@section('page_css')
<style type="text/css">
  .card-body {
    padding: 12px 0px 12px 0px !important;
}

.card-footer {
    padding: 10px 10px;
}
.course-hours {
    float: left;
    font-size: 12px;
}
.course-fees {
    /* display: inline-block; */
    float: right;
    text-align: center;
}
span.old_price {
    text-decoration: line-through;
    font-size: 12px;
}
span.price {
    color: #15db95;
    font-weight: 700;
    display: block;
/*    margin-top: -11px;*/
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
.feature-icon img{
   height: 110px !important;
   width: 100% !important;
}
.border-2 {
    border: 1.5px solid;
}
</style>
@stop
@section('content')

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad bg-no-repeat bg-cover" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h2 class="text-white h1">Courses</h2>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Courses</li>              
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
                 @if($courses->count() > 0)
               
              
              @foreach($courses as $course)

              <div class="col-lg-4 wow fadeInUp mb-3" data-wow-duration="0.5s" data-wow-delay="0.1s">
                <div class="card hover-scale overflow-hidden hover-top">
                  <div class="position-relative hover-scale-in">
                    <a href="{{ route('courses.show', [$course->slug]) }}">
                      <img class="card-img-top img-live-class" src="{{asset('storage/uploads/'.$course->course_image)}}" title="" alt="">
                    </a>
                    
                  </div>
                   <div class="card-body p-3 text-center">
                    
                    <h5 class="">
                      <a class="text-dark stretched-link" href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>
                    </h5>
                    
                    
                   
                  </div>
                  <div class="card-footer">
                     <div class="course-hours"><i class="bi-people-fill"></i>
                        <?php
                        $bd = Board::find($course->board_id);
                         ?>
                         @if($bd)
                         {{$bd->name}}

                         @endif

                      {{ $course->category->name}}</div>
                     <div class="course-fees">
                        <!-- <span class="old_price">₹13000</span> -->
                        @if($course->free == 1)
                        <span class="price">₹ Free</span>
                        @else
                        <span class="price">₹ {{$course->price}}</span>
                        @endif
                                                          
                     </div>
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
                        <h4 class="h4 pb-4 ">No Course Found</h4>
                    </div>
                  </div>
                </div>
              

              @endif
              

            </div>
          </div>
          <div class="col-lg-4 my-3">
           
            <div class="card mt-5">
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
        </div>
      </div>
    </section>

   
</main>

@stop
