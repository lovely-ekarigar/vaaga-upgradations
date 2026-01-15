<?php
use App\Models\Category;
use App\Models\Board;
use App\Models\Coupon;
?>
@extends('frontend.layout.sub-master')
@section('title')
<title>{{$find_category_id->meta_title}} | {{env('APP_NAME')}}</title> 
<meta name="description" content="{{$find_category_id->meta_description}}">
<meta name="keywords" content="{{$find_category_id->meta_keyword}}">
@stop
@section('page_css')
<style type="text/css">
  .card-bodyx {
    padding: 12px 0px 12px 0px !important;
}
.dot {
    display: inline-block;
    height: 9px;
    width: 8px;
    background: #0000009e;
    border-radius: 50%;
    margin: 0px 3px;
}
.card-footer {
    padding: 10px 10px;
}
.course-hours {
/*    float: left;*/
    font-size: 12px;
}
.course-fees {
    /* display: inline-block; */
    float: right;
    text-align: center;
        width: 50%;
}
span.old_price {
    text-decoration: line-through;
    font-size: 12px;
}
.price_type {
    font-size: 12px;
    font-weight: 500;
    color: #FF9800;
    line-height: 1;
    margin-bottom: 6px;
}
span.price {
    color: #15db95;
    font-weight: 700;
/*    display: block;*/
/*    margin-top: -11px;*/
font-size: 13px;
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
               <h1 class="text-white h1">{{$find_baords_id->name}} | {{$find_category_id->name}}</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{$categories_parent_data->name}}</li>
                <li class="breadcrumb-item active">{{$find_baords_id->name}}</li>
                <li class="breadcrumb-item active">{{$find_category_id->name}}</li>
              </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-9 my-3">
            <div class="row">
                 <div class="col-lg-12 my-3">
             <div class="card card-body" style="padding: 20px 20px 20px 20px !important;">
                <h5>{{$find_category_id->name}}</h5>
                <p>{!!$find_category_id->description!!}</p>
             </div>
          </div>
              @if($courses->count() > 0 || count($categories_data)>0)
                @foreach($categories_data as $cat)

              <div class="col-sm-6 col-lg-4 mb-sm-3 pb-3">
                  <div class="card hover-scale overflow-hidden hover-top ">
                    <div class="position-relative hover-scale-in text-center py-3" style="padding-top: 0px !important;">
                      <a href="{{route('academic-course',['slug' =>$cat->slug])}}">
                        <img class="card-img-top" src="@if($cat->course_image) {{asset('storage/uploads/'.$cat->course_image)}} @else /newassets/img/logo.png @endif" onerror='this.src="/newassets/img/logo.png"' style="width: 100%;height: 110px;" title="{{$cat->name}}" alt="{{$cat->name}}">
                      </a>
                     
                    </div>
                    <div class="card-body card-bodyx text-center">
                      <h3 class="h3">
                        <a class="text-reset stretched-link" href="{{route('category',['slug' =>$cat->slug])}}">{{$cat->name}}</a>
                      </h3>
                    </div> 
                  </div>
                </div> 
             
              @endforeach 
              
              @foreach($courses as $course)

               <div class="col-lg-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                <div class="card hover-scale overflow-hidden hover-top">
                  <div class="position-relative hover-scale-in">
                    <a href="{{ route('courses.show', [$course->slug]) }}">
                      <img class="card-img-top img-live-class" src="{{asset('storage/uploads/'.$course->course_image)}}" onerror="this.src='/newassets/img/logo.png'" title="{{$course->title}}" alt="{{$course->title}}">
                    </a>
                   
                  </div>
                   <div class="card-body p-3">

                    <?php

                    $cp = Coupon::find($course->coupon_id);
                    ?>
                    
                    <h5 class="mb-3">
                        @if($cp)
                      <a class="text-dark stretched-link" href="{{ route('courses.show', [$course->slug]) }}?coupon={{$cp->code}}">{{$course->title}}</a>
                      @else
 <a class="text-dark stretched-link" href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>

                      @endif
                    </h5>
                    
                   <div class="course-hours"><i class="bi-people-fill"></i> 

                        <?php $bd = Board::find($course->board_id); ?>

                       
                        @if($bd)
                        {{$bd->name}} <div class="dot"></div>
                        @endif  
                        {{ $course->category->name}}</div>

                            @if($course->duration_text)
 <div class="course-hours d-block"><i class="bi-clock"></i> 

                     

                       
                       {{$course->duration_text}}</div>
                       @endif
                   
                  </div>

                    @if(Auth::user())
                  @if(Auth::user()->hasRole('student'))

                  <div class="card-footer" style="display: flex;
    align-items: center;">
                   

                         <?php

                            $cop = new Coupon;
                            $price = $cop->applyCoupon($course->coupon_id,$course->price); 
                            $monthly_price = $cop->applyCoupon($course->coupon_id_monthly_price,$course->monthly_price); 
                         ?>

                         @if($course->monthly_price)

                            @if($monthly_price<$course->monthly_price)
                                <div class="course-fees" style="    text-align: left;">
 <span class="old_price">₹{{round($course->monthly_price)}}</span>
<span class="price">₹{{round($monthly_price)}}</span>
  <div class="price_type">Subscription</div>
</div>
                            @else
                            <div class="course-fees" style="    text-align: left;">
<span class="price">₹{{round($course->monthly_price)}}</span>
  <div class="price_type">Subscription</div>
</div>
                            @endif
                          
                         @endif

                          @if($course->price)

                            @if($price<$course->price)
                              @if($course->monthly_price) 
                                <div class="course-fees" style="    text-align: right;">
                                  @else
 <div class="course-fees" style="    text-align: left;">
                                  @endif
 <span class="old_price">₹{{round($course->price)}}</span>
<span class="price">₹{{round($price)}}</span>
 <div class="price_type">Full Course</div>
</div>
                            @else
                            @if($course->monthly_price) 
                                <div class="course-fees" style="    text-align: right;">
                                  @else
 <div class="course-fees" style="    text-align: left;">
                                  @endif
<span class="price">₹{{round($course->price)}}</span>
 <div class="price_type">Full Course</div>
</div>
                            @endif

                         @endif

                       

                  </div>
                  @endif
                  @endif
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
          <div class="col-lg-3 my-3">
           
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