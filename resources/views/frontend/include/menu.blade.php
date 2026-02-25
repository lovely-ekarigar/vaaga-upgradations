<?php
   use App\Models\Category;
   use App\Models\Board;
   ?>
   <style>
       @media (min-width: 992px){
.navbar-expand-lg .navbar-nav .dropdown-menu .dropdown>.dropdown-item {
    position: relative;
    padding-right: 36px;
}}
   </style>
<!-- Mobile Toggle -->
<div class="header-search ms-lg-4 ms-auto pe-lg-5">
   <button class="dropdown-toggle" aria-label="Search Courses" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-search"></i></button>
   <form action="/courses/#">
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
         <!--<div class="input-group"><input type="text" class="form-control form-control-sm" name="key" value="{{request()->key}}" placeholder="Search" aria-label="search" aria-describedby="basic-addon1"> <button class="input-group-text" type="submit" id="basic-addon1" aria-label="Search Courses"><i class="bi bi-search"></i></button></div>-->
      </div>
   </form>
</div>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="toggler-icon"></span>
</button>
<!-- End Mobile Toggle -->
<!-- Menu -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">
   <ul class="navbar-nav m-auto">
      <li class="nav-item active"><a href="/" class="nav-link">Home</a></li>
      
     
     <li class="nav-item dropdown">
   <a href="#" class="nav-link">Courses</a> <label class="px-dropdown-toggle mob-menu bi bi-chevron-down"></label>
   <ul class="dropdown-menu left list-unstyled">

       <?php $cats1 = Category::where('parent',0)->where('status','1')->orderBy('sort_order','asc')->get(); ?>

         @foreach($cats1 as $c1)
         <?php $boards = Board::get(); ?>
               <?php $cats2 = Category::where('parent',$c1->id)->where('status','1')->orderBy('sort_order','asc')->get(); ?>
          @if($c1->is_board=='1')     
      <li class="nav-item  dropdown">
         <a class="dropdown-item" href="/category/{{$c1->slug}}">{{$c1->name}}</a> <label class="px-dropdown-toggle mob-menu bi bi-chevron-down"></label>
         <div class="dropdown-menu dropdown-menu-sub">
            <ul class="list-unstyled">

 @foreach($boards as $b)
             
             <?php $cats3 = Category::where('parent',$c1->id)->where("board_id",$b->id)->where('status','1')->orderBy('sort_order','asc')->get(); ?>
             @if(count($cats3)>0)
<li class="nav-item dropdown">
         <a class="dropdown-item" href="{{route('academic',['slug'=>$b->slug,'cat'=>$c1->slug])}}">{{$b->name}}</a> <label class="px-dropdown-toggle mob-menu bi bi-chevron-down"></label>
         <div class="dropdown-menu  dropdown-menu-subx">
            <ul class="list-unstyled">
                  @foreach($cats3 as $c3)
               <li><a class="dropdown-item" href="{{route('academic-course',['slug'=>$c3->slug,'board'=>$b->slug])}}">{{$c3->name}}</a></li>
                @endforeach
            </ul>
         </div>
      </li> 

      @else
    
         

               <li><a class="dropdown-item" href="/academic/{{$b->slug}}?cat={{$c1->slug}}">{{$b->name}}</a></li>
               @endif


 @endforeach

            </ul>
         </div>
      </li> 
 @else
  <?php $cats2 = Category::where('parent',$c1->id)->where('status','1')->orderBy('sort_order','asc')->get(); ?>
               @if(count($cats2)>0)
             <li class="nav-item  dropdown">  <a class="dropdown-item" href="/category/{{$c1->slug}}">{{$c1->name}}</a> <label class="px-dropdown-toggle mob-menu bi bi-chevron-down"></label>
               <div class="dropdown-menu dropdown-menu-sub">
                  <ul class="list-unstyled">
                     @foreach($cats2 as $c2)
                     <li>
                        <a class="dropdown-item" href="/category/{{$c2->slug}}">{{$c2->name}}</a>
                     </li>
                     @endforeach
                  </ul>
               </div>
               </li>
               @else
             <li>  <a class=" dropdown-item" href="/category/{{$c1->slug}}">{{$c1->name}}</a> </li>
               @endif
               
              
               
               
               @endif
       @endforeach
   </ul>
</li>


      <li class="nav-item active"><a href="/test-series/olympiad" class="nav-link">Test Series</a></li>  
      <!--<li class="nav-item active"><a href="/our-classes" class="nav-link">Our Classes</a></li>-->
      <li class="nav-item active"><a href="/become-tutor" class="nav-link">Join as Tutor</a></li>
      <li class="nav-item active"><a href="/about" class="nav-link">About Us</a></li>
      <!--<li class="nav-item active"><a href="{{ route('frontend.note.categories') }}" class="nav-link">Study Material</a></li>-->
      <li class="nav-item active"><a href="/contact" class="nav-link">Contact Us</a></li>
      @if(auth()->check())
      <li class="nav-item active d-xl-none d-lg-none"><a href="/user/dashboard" class="nav-link">Dashboard</a></li>
      <li class="nav-item active d-xl-none d-lg-none"><a href="/user/account" class="nav-link">My Profile</a></li>
      <li class="nav-item active d-xl-none d-lg-none"><a href="{{ route('frontend.auth.logout') }}" class="nav-link text-danger">Logout</a></li>
      @else
      <li class="nav-item active d-xl-none d-lg-none"><a href="/userlogin" class="nav-link">Login</a></li>
      <li class="nav-item active d-xl-none d-lg-none"><a href="/userregister" class="nav-link">Register</a></li>
      @endif
     
   </ul>
</div>
<!-- End Menu -->
<div class="nav flex-column flex-lg-row d-none d-lg-flex">
   <ul class="navbar-nav ms-auto align-items-center">
      @if(auth()->check())
      <li class="nav-item">
         <a href="/user/dashboard" class="btn btn-sm btn-primary mb-0 ms-2 text-nowrap">Dashboard</a>
      </li>
      @else
      <li class="nav-item">
         <a href="/userlogin" class="btn btn-sm btn-primary mb-0 ms-2 text-nowrap">Login</a>
      </li>
      <li class="nav-item">
         <a href="/userregister" class="btn btn-sm btn-warning mb-0 ms-2 text-nowrap" style="color: #000;">Register</a>
      </li>
      @endif
   </ul>
</div>
