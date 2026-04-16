<?php
use App\Models\Category;
use App\Models\Board;
use App\Models\Coupon;

// Generate meta data dynamically if not set in database
$catName = $find_parent_category->name;
$catSlug = $find_parent_category->slug;
$metaTitle = $find_parent_category->meta_title;
$metaDescription = $find_parent_category->meta_description;
$metaKeywords = $find_parent_category->meta_keyword;

// If meta is empty, generate from category name/slug
if (empty($metaTitle) || empty($metaDescription)) {
    // Extract class number from slug or name
    $classNum = '';
    if (preg_match('/class-?(\d+)/i', $catSlug, $matches) || preg_match('/class\s+(\d+)/i', $catName, $matches)) {
        $classNum = $matches[1];
    }
    
    // Check if main olympiad category
    if ($catSlug == 'olympiad' || stripos($catName, 'olympiad') !== false && empty($classNum)) {
        $metaTitle = 'Olympiad Online Coaching for Class 2–8 - VaaGa Academy';
        $metaDescription = 'VaaGa Academy offers expert Olympiad coaching for Class 2–8 students in Maths, Science & English. Prepare for IMO, NSO & IEO exams with live sessions, mock tests, and personalized feedback.';
        $metaKeywords = 'Olympiad coaching, Olympiad online classes, IMO preparation, NSO preparation, IEO preparation, Maths Olympiad, Science Olympiad, English Olympiad, Class 2-8 Olympiad, SOF Olympiad, VaaGa Academy, Live Olympiad classes, Olympiad mock tests, Online Olympiad coaching';
    } elseif ($classNum) {
        $metaTitle = "Olympiad Classes for Class {$classNum} - VaaGa Academy";
        $metaDescription = "VaaGa Academy offers expert Olympiad coaching for Class {$classNum} students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.";
        $metaKeywords = "Class {$classNum} Olympiad, Class {$classNum} IMO, Class {$classNum} NSO, Class {$classNum} IEO, Olympiad coaching Class {$classNum}, Olympiad preparation Class {$classNum}, Online Olympiad classes Class {$classNum}, Maths Science English Olympiad Class {$classNum}, SOF Olympiad Class {$classNum}, VaaGa Academy Olympiad, Best Olympiad coaching online, Live Olympiad classes, Olympiad mock tests Class {$classNum}";
    } else {
        // Fallback
        $metaTitle = $catName . ' - VaaGa Academy';
        $metaDescription = 'VaaGa Academy offers expert online Olympiad coaching for students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.';
        $metaKeywords = 'Olympiad coaching, Online Olympiad classes, IMO preparation, NSO preparation, IEO preparation, VaaGa Academy';
    }
}
?>
@extends('frontend.layout.sub-master')
@section('title')
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta property="og:title" content="{{ $metaTitle }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:type" content="website" />
<meta name="twitter:title" content="{{ $metaTitle }}" />
<meta name="twitter:description" content="{{ $metaDescription }}" />
@stop

@section('page_css')
<style type="text/css">
  .card-body {
    padding: 8px 0px 6px 0px !important;
}
.dot {
    display: inline-block;
    height: 9px;
    width: 8px;
    background: #0000009e;
    border-radius: 50%;
    margin: 0px 3px;
}
  .card-bodyx {
    padding: 8px 0px 5px 10px !important;
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
        .mtp-43{
  margin-top: 43px !important;
}
}
</style>
@stop
@section('content')
<?php
$catx = Category::find($find_parent_category->parent);

?>
<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-43" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
   {{-- <section class="bg-primary effect-section page-heading-pad bg-no-repeat bg-cover mtp-20" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}});"> --}}
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <!--<h1 class="text-white h1">{{$find_parent_category->name}}</h1>-->
               <h1 class="text-white h1">{{ $metaTitle }}</h1>

               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                @if($catx)
                 <li class="breadcrumb-item"><a href="/category/{{$catx->slug}}">{{$catx->name}}</a></li>
                @endif
                <li class="breadcrumb-item active">{{$find_parent_category->name}}</li>
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
                <h5>{{$find_parent_category->name}}</h5>
                <p>{!!$find_parent_category->description!!}</p>
             </div>
          </div>
               @if($categories_list->count() > 0 || count($courses)>0)
              @foreach($categories_list as $cat)

              <div class="col-sm-6 col-lg-4 mb-sm-3 pb-3">
                  <div class="card hover-scale overflow-hidden hover-top ">
                    <div class="position-relative hover-scale-in text-center py-3" style="padding-top: 0px !important;">
                      <a href="{{route('category',['slug' =>$cat->slug])}}">
                        <img class="card-img-top" src="{{asset('storage/uploads/'.$cat->course_image)}}" onerror='this.src="/newassets/img/logo.png"' style="    width: 100%;
    height: 110px;" title="{{$cat->name}}" alt="{{$cat->name}}">
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
                   <div class="card-body card-bodyx  p-3">

                    <?php

                    $cp = Coupon::find($course->coupon_id);
                    ?>
                    
                    <h5 class="m-0">
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
                        @if($catx)
                        {{$catx->name}} <div class="dot"></div>
                        @endif
                        {{ $course->category->name}}</div>
                            @if($course->duration_text)
 <div class="course-hours d-block"><i class="bi-clock"></i> 

                     

                       
                       {{$course->duration_text}}</div>
                       @endif
                   
                  </div>
                     <!-- @ if(Auth::user())
                  @ if(Auth::user()->hasRole('student')) -->
                     @if(1)
                  @if(1)
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
 <span class="old_price">₹{{$course->monthly_price}}</span>
<span class="price">₹{{$monthly_price}}</span>
  <div class="price_type">Subscription</div>
</div>
                            @else
                            <div class="course-fees" style="    text-align: left;">
<span class="price">₹{{$course->monthly_price}}</span>
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
 <span class="old_price">₹{{$course->price}}</span>
<span class="price">₹{{$price}}</span>
 <div class="price_type">Full Course</div>
</div>
                            @else
                               @if($course->monthly_price) 
                                <div class="course-fees" style="    text-align: right;">
                                  @else
 <div class="course-fees" style="    text-align: left;">
                                  @endif
<span class="price">₹{{$course->price}}</span>
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
          <!-- <div class="col-lg-3 my-3 ">
           
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
           
          </div> -->
         
        </div>
      </div>
    </section>
</main>

@stop