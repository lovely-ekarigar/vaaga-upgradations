@extends('frontend.layout.sub-master')
@section('title')
<title>Courses | {{env('APP_NAME')}}</title>
@stop
@section('content')
<div class="breadcrumb-bar">
<div class="container">
<div class="row">
<div class="col-md-12 col-12">
<div class="breadcrumb-list">
<nav aria-label="breadcrumb" class="page-breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.html">Home</a></li>
<li class="breadcrumb-item" aria-current="page">Courses</li>
<li class="breadcrumb-item active" aria-current="page">All Courses</li>
</ol>
</nav>
</div>
</div>
</div>
</div>
</div>


<section class="course-content">
<div class="container">
<div class="row">
<div class="col-lg-9">

<div class="showing-list">
<div class="row">
<div class="col-lg-6">
<div class="d-flex align-items-center">
<div class="view-icons">
<a href="?view=grid" class="grid-view active"><i><img src="/frontend/assets/img/icon/grid.svg"></i></a>
<a href="?view=list" class="list-view "><i class="fas fa fa-list"></i></a>
</div>
<div class="show-result">
<h4>Showing {{ $courses->firstItem() }}-{{ $courses->lastItem() }} of {{ $courses->total() }} courses</h4>
</div>
</div>
</div>
<div class="col-lg-6">
<div class="show-filter add-course-info">
<form action="#">
<div class="row gx-2 align-items-center">
<div class="col-md-6 col-item">
<div class=" search-group">
<i class="feather-search"></i>
<input type="hidden" value="grid" name="view">
<input type="text" class="form-control" name="key" value="{{request()->key}}" placeholder="Search our courses">
</div>
</div>
<div class="col-md-6 col-lg-6 col-item">
<div class="form-group select-form mb-0">
<input type="submit" class="btn btn-primary btn-sm" value="Search">
</div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>

<div class="row">
	@if($courses->count() > 0)
    @foreach($courses as $course)
<div class="col-lg-4 col-md-6 d-flex">
<div class="course-box course-design d-flex ">
<div class="product">
<div class="product-img">
<a href="{{ route('courses.show', [$course->slug]) }}">
<img class="img-fluid" alt="" src="{{asset('storage/uploads/'.$course->course_image)}}" style="    height: 125px;">
</a>
 <div class="price">
@if($course->free == 1)
    <span>{{trans('labels.backend.courses.fields.free')}}</span>
        @else
<h3>{{$appCurrency['symbol'].' '.$course->price}}</h3>
@endif
</div>
</div>
<div class="product-content">
<div class="course-group d-flex">
<!-- <div class="course-group-img d-flex">
<a href="instructor-profile.html"><img src="frontend/assets/img/user/user1.jpg" alt="" class="img-fluid"></a>
<div class="course-name">
<h4><a href="instructor-profile.html">Rolands R</a></h4>
<p>Instructor</p>
</div>
</div> -->
<div class="course-share d-flex align-items-center justify-content-center">
<!-- <a href="#rate"><i class="fa-regular fa-heart"></i></a> -->
</div>
</div>
<h3 class="title"><a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a></h3>
<div class="course-info d-flex align-items-center">
<div class="rating-img d-flex align-items-center">
<img src="frontend/assets/img/icon/icon-01.svg" alt="">
<p>{{count($course->lessons)}} Lessons</p>
</div>
<div class="course-view d-flex align-items-center">
<img src="frontend/assets/img/icon/people.svg" alt="">
<p>{{ $course->students()->count() }} @lang('labels.frontend.course.students')</p>
</div>
</div>
<!-- <div class="rating">
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star"></i>
<span class="d-inline-block average-rating"><span>4.0</span></span>
</div> -->
<div class="all-btn all-category d-flex align-items-center">
<a href="{{ route('courses.show', [$course->slug]) }}" class="btn btn-primary">Details </a>
</div>
</div>
</div>
</div>
</div>
@endforeach
  @else
  <h3>@lang('labels.general.no_data_available')</h3>
  @endif
</div>

<div class="row">
<div class="col-md-12">
<ul class="pagination lms-page">
{{ $courses->links() }}
</ul>
</div>
</div>

</div>
<div class="col-lg-3 theiaStickySidebar">
<div class="filter-clear">
<div class="clear-filter d-flex align-items-center">
<h4><i class="feather-filter"></i>Filters</h4>

</div>

<div class="card search-filter ">
<div class="card-body">
<div class="filter-widget mb-0">
<div class="categories-head d-flex align-items-center">
<h4>Price</h4>
<i class="fas fa-angle-down"></i>
</div>
<div>
<label class="custom_check custom_one">
<input type="radio" name="select_specialist">
<span class="checkmark"></span> All (18)
</label>
</div>
<div>
<label class="custom_check custom_one">
<input type="radio" name="select_specialist">
<span class="checkmark"></span> Free (3)
</label>
</div>
<div>
<label class="custom_check custom_one mb-0">
<input type="radio" name="select_specialist" checked>
<span class="checkmark"></span> Paid (15)
</label>
</div>
</div>
</div>
</div>

<!-- <div class="card search-filter categories-filter-blk">
<div class="card-body">
<div class="filter-widget mb-0">
<div class="categories-head d-flex align-items-center">
<h4>Course categories</h4>
<i class="fas fa-angle-down"></i>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Backend (3)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> CSS (2)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Frontend (2)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist" checked>
<span class="checkmark"></span> General (2)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist" checked>
<span class="checkmark"></span> IT & Software (2)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Photography (2)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
 <span class="checkmark"></span> Programming Language (3)
</label>
</div>
<div>
<label class="custom_check mb-0">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Technology (2)
</label>
</div>
</div>
</div>
</div>


<div class="card search-filter">
<div class="card-body">
<div class="filter-widget mb-0">
<div class="categories-head d-flex align-items-center">
<h4>Instructors</h4>
<i class="fas fa-angle-down"></i>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Keny White (10)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> Hinata Hyuga (5)
</label>
</div>
<div>
<label class="custom_check">
<input type="checkbox" name="select_specialist">
<span class="checkmark"></span> John Doe (3)
</label>
</div>
<div>
<label class="custom_check mb-0">
<input type="checkbox" name="select_specialist" checked>
<span class="checkmark"></span> Nicole Brown
</label>
</div>
</div>
</div>
</div> -->


</div>
</div>
</div>
</div>
</section>

@stop