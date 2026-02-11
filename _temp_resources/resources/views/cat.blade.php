@extends('frontend.layout.sub-master')
@section('title')
<title>{{$category->name}} courses| {{env('APP_NAME')}}</title>
@stop
@section('content')
<div class="breadcrumb-bar">
<div class="container">
<div class="row">
<div class="col-md-12 col-12">
<div class="breadcrumb-list">
<nav aria-label="breadcrumb" class="page-breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="/">Home</a></li>
<li class="breadcrumb-item" aria-current="page">Category</li>
<li class="breadcrumb-item " aria-current="page">{{$category->name}}</li>
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
<a href="?view=grid" class="grid-view"><i><img src="/frontend/assets/img/icon/grid.svg"></i></a>
<a href="?view=list" class="list-view active"><i class="fas fa fa-list"></i></a>
</div>
<div class="show-result">
<h4>Showing {{ $courses->firstItem() }}-{{ $courses->lastItem() }} of {{ $courses->total() }} courses</h4>

</div>
</div>
</div>
<div class="col-lg-6">
<div class="show-filter add-course-info ">
<form action="#">
<div class="row gx-2 align-items-center">
<div class="col-md-6 col-item">
<div class=" search-group">
<i class="feather-search"></i>

<input type="text" class="form-control" name="key" value="{{request()->key}}" placeholder="Search our courses">
</div>
</div>
<div class="col-md-6 col-lg-6 col-item">
<div class="form-group select-form mb-0">
<input type="submit" class="btn btn-primary btn-sm" value="Search" />
</div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>

<div class="row">
@foreach($courses as $crs)
<div class="col-lg-12 col-md-12 d-flex">
<div class="course-box course-design list-course d-flex">
<div class="product">
<div class="product-img">

<a href="{{ route('courses.show', ['slug'=>$crs->slug]) }}">

<img class="img-fluid" src="{{asset('storage/uploads/'.$crs->course_image)}}" style="height:150px;" data-src="{{asset('storage/uploads/'.$crs->course_image)}}" onerror="this.src=''" alt="">
</a>
<!--  <div class="price">
<h3>$300 </h3>
</div> -->
</div>
<div class="product-content">
<div class="head-course-title">
<h3 class="title"><a href="{{ route('courses.show', ['slug'=>$crs->slug]) }}">{{$crs->title}}</a></h3>
<div class="all-btn all-category d-flex align-items-center">
<a href="{{ route('courses.show', ['slug'=>$crs->slug]) }}" class="btn btn-primary">Details</a>
</div>
</div>
<div class="course-info border-bottom-0 pb-0 d-flex align-items-center">
<div class="rating-img d-flex align-items-center">
<img src="/frontend/assets/img/icon/icon-01.svg" alt="">
<p>{{count($crs->lessons)}} Lessons</p>
</div>
<div class="course-view d-flex align-items-center">
<img src="/frontend/assets/img/icon/icon-02.svg" alt="">
<p><?php

$totalSec=0;
foreach($crs->lessons as $l){

$totalSec += (strtotime("2020-10-10 ".$l->duration) - strtotime("2020-10-10 00:00:00"));

}
$hrs = (int)($totalSec/3600);
$mins = (int)(($totalSec-$hrs*3600)/60);
 if($hrs>0){

echo $hrs." hrs ";
 }
 if($mins>0){

echo $mins." mins";
 }

?></p>
</div>
</div>
<div class="rating">
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star filled"></i>
<i class="fas fa-star"></i>
<span class="d-inline-block average-rating"><span>4.0</span></span>
</div>
<div class="course-group d-flex mb-0">
<div class="course-group-img d-flex">
<a href="#"><img src="assets/img/user/user1.jpg" alt="" class="img-fluid"></a>
<!-- <div class="course-name">
<h4><a href="instructor-profile.html">Rolands R</a></h4>
<p>Instructor</p>
</div> -->
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach

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
<div class="clear-text">
<!-- <p>CLEAR</p> -->
</div>
</div>

<div class="card search-filter categories-filter-blk">
<div class="card-body">
<div class="filter-widget mb-0">
<div class="categories-head d-flex align-items-center">
<h4>Course categories</h4>
</div>
<div>
    @foreach($categories as $ct)
    @if($ct->parent==0)
<a href="/category/{{$ct->slug}}/courses">
    <p>{{$ct->name}}</p>
</a>

@endif
@endforeach
</div>

</div>
</div>
</div>
</div>
</div>

</div>
</div>
</section>

@stop