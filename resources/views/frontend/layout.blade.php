<?php
use App\Models\Resource;
use App\Models\Category;
use App\Models\Course;

if(isset($_GET["aff_id"])){
Cookie::queue('affiliate_code', $_GET["aff_id"], 60 * 24*365);
}
?>
@section('header')

<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="description" content="@yield('seo_des')">
    <meta name="keywords" content="@yield('seo_key')"/>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="/nglive/images/favicon.png">
<!--load amimate.css from CDN-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.4.0/animate.min.css"> 

<!--load WOW js from CDN-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
  
  
  
    <!-- inject:css -->
    <link rel="stylesheet" href="/nglive/css/bootstrap.min.css">
    <link rel="stylesheet" href="/nglive/css/line-awesome.css">
    <link rel="stylesheet" href="/nglive/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/nglive/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/nglive/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/nglive/css/fancybox.css">
    <link rel="stylesheet" href="/nglive/css/tooltipster.bundle.css">
    <link rel="stylesheet" href="/nglive/css/animated-headline.css">
    <link rel="stylesheet" href="/nglive/css/style.css">
        <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <style>
   .autolist {
    position: absolute;
    background: #fdfdfd;
    z-index: 99;
    list-style: none;
    padding-left: 0px;
    max-height: 300px;
    overflow: auto;
    display:none;
}
    ul.autolist li{
       text-transform: uppercase;
    border-bottom: 1px solid #d6d0d05c;
    padding: 4px 9px;
    }
  ul.autolist li  a{
        color: #000000c4;
    width: 100%;
    display: block;
    }

    .autolist1 {
    position: absolute;
    background: #fdfdfd;
    z-index: 99;
    list-style: none;
    padding-left: 0px;
    max-height: 300px;
    overflow: auto;
    display:none;
}
    ul.autolist1 li{
       text-transform: uppercase;
    border-bottom: 1px solid #d6d0d05c;
    padding: 4px 9px;
    }
  ul.autolist1 li  a{
        color: #000000c4;
    width: 100%;
    display: block;
    }

.icon-element svg{
    padding-top: 15px;
}
.icon-element-sm {
    padding-top: 12px;
}
#scroll-top{
    padding-top: 11px;
}
.owl-action-styled .owl-nav div.owl-prev {
   
    padding-top: 12px;
}

.hero-slider-item {
   
    padding-left: 80px;
        padding-top: 100px ;
    padding-bottom: 100px;
}
.owl-action-styled .owl-nav div.owl-next {
   
    padding-top: 12px;
}

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .mobile{
        display: block;
    }
    .icon-element-sm {
    display: inline-block;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
     .mobile{
        display: block;
    }
    .icon-element-sm {
    display: inline-block;
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
     .mobile{
        display: none;
    }
       .icon-element-sm {
    display: none;
}
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
     .mobile{
        display: none;
    }
        .icon-element-sm {
    display: none;
}
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
     .mobile{
        display: none;
    }
        .icon-element-sm {
    display: none;
}
}

</style>
    
    
    
    <!-- end inject -->
</head>
<body>
<?php
$rlist=Resource::all();

$menucats=Category::where("parent",0)->get();

$menulist=array();

foreach($menucats as $ct){

$menuco=Course::where("category_id",$ct->id)->where("published",'1')->get();
$ct->courses=$menuco;
$menulist[]=$ct;

}


 ?>
<!-- start cssload-loader -->

<!-- end cssload-loader -->

<!--======================================
        START HEADER AREA
    ======================================-->
<header class="header-menu-area bg-white">
    
    <div class="header-menu-content pr-150px pl-150px bg-white">
        <div class="container-fluid">
            <div class="main-menu-content">
                <a href="#" class="down-button"><i class="la la-angle-down"></i></a>
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="logo-box">
                            <a href="/" class="logo wow pulse" data-wow-iteration="infinite" data-wow-duration="1000ms"><img src="/ltlogo.png" style="width:200px" alt="logo"></a>
                            <div class="user-btn-action">
                                <div class=" icon-element icon-element-sm shadow-sm mr-2 mobile" data-toggle="tooltip" data-placement="top" title="Login">
                                    <a href="/userlogin"><i class="la la-user"></i></a>
                                </div>
                                
                                <div class="off-canvas-menu-toggle cat-menu-toggle icon-element icon-element-sm shadow-sm mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Categories">
                                    <i class="la la-th-large"></i>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col-lg-2 -->
                    <div class="col-lg-10">
                        <div class="menu-wrapper" style="padding-top: 5px;
    padding-bottom: 5px;">
                           <div class="menu-category">
                                <ul>
                                    <li>
                                        <a href="#">Categories <i class="la la-angle-down fs-12"></i></a>
                                        <ul class="cat-dropdown-menu">

                                            @foreach($menulist as $ml)
                                            <li>
                                                <a href="/category/{{$ml->slug}}/courses">{{$ml->name}} <i class="la la-angle-right"></i></a>
                                                <ul class="sub-menu">
                                                    @foreach($ml->courses as $mc)
                                                    <li><a href="/course/{{$mc->slug}}">{{$mc->title}}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            @endforeach
                                           
                                         
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <form method="get">
                                <div class="form-group mb-0 autokey1u">
                                    <input class="form-control form--control pl-3 autokey1" type="text" name="search" placeholder="Search for anything">
                                    <span class="la la-search search-icon"></span>
                                </div>
                                <ul class="autolist1">
                    
                </ul>
                            </form>
                            <nav class="main-menu">
                                <ul>
                              
                                    <li>
                                       
                                        
              
            <li><a class="" href="http://livetutorials.alphaexam.in/">Online Exam</a></li>
               
               <li>
                                        <a href="#">Resources <i class="la la-angle-down fs-12"></i></a>
                                        <ul class="dropdown-menu-item">
                                    @foreach($rlist as $r)    
                    <li><a href="/resource/{{$r->slug}}">{{$r->name}} </a></li>
                                       @endforeach

                    
                                        </ul>
                                    </li>
                                        
                                    </li>
                                </ul><!-- end ul -->
                            </nav><!-- end main-menu -->
                          
                            <div class="nav-right-button">
                                @if(Auth::user())

 <a href="/user" class="btn theme-btn d-none d-lg-inline-block">Dashboard</a>
                                @else
 <a href="/userregister" class="btn theme-btn d-none d-lg-inline-block wow pulse" data-wow-iteration="infinite" data-wow-duration="1000ms" style="background-color: #28a745;"><i class="la la-user-plus mr-1"></i> Enroll Now</a>
                                
                                 <a href="/userlogin" class="btn theme-btn d-none d-lg-inline-block"><i class="la la-user-plus mr-1"></i>Login</a>

                                @endif
                               
                                
                            </div><!-- end nav-right-button -->
                        </div><!-- end menu-wrapper -->
                    </div><!-- end col-lg-10 -->
                </div><!-- end row -->
            </div>
        </div><!-- end container-fluid -->
    </div><!-- end header-menu-content -->
    <div class="off-canvas-menu custom-scrollbar-styled main-off-canvas-menu">
        <div class="off-canvas-menu-close main-menu-close icon-element icon-element-sm shadow-sm" data-toggle="tooltip" data-placement="left" title="Close menu">
            <i class="la la-times"></i>
        </div><!-- end off-canvas-menu-close -->
        <ul class="generic-list-item off-canvas-menu-list pt-90px">
          <li>
                                      
                                         <li><a class="" href="https://livetutorials.in/">Home</a></li>
              <li><a class="" href="https://livetutorials.in/about">About</a></li>
              <li><a class="" href="https://livetutorials.in/contact">Contact</a></li>
               <li><a class="" href="http://livetutorials.alphaexam.in/">Online Exam</a></li>
        </ul>
    </div><!-- end off-canvas-menu -->
  <div class="off-canvas-menu custom-scrollbar-styled category-off-canvas-menu">
        <div class="off-canvas-menu-close cat-menu-close icon-element icon-element-sm shadow-sm" data-toggle="tooltip" data-placement="left" title="" data-original-title="Close menu" aria-describedby="tooltip563981">
            <i class="la la-times"></i>
        </div><!-- end off-canvas-menu-close -->
        <ul class="generic-list-item off-canvas-menu-list pt-90px">
             @foreach($menulist as $ml)
            <li>
                <a href="/category/{{$ml->slug}}/courses">{{$ml->name}}</a>
                <ul class="sub-menu">
                    @foreach($ml->courses as $mc)
                    <li><a href="/course/{{$mc->slug}}">{{$mc->title}}</a></li>
                    @endforeach
                </ul>
            </li>
            @endforeach
           
        </ul>
    </div>
    <div class="mobile-search-form">
        <div class="d-flex align-items-center">
            <form method="post" class="flex-grow-1 mr-3">
                <div class="form-group mb-0">
                    <input class="form-control form--control pl-3" type="text" name="search" placeholder="Search for anything">
                    <span class="la la-search search-icon"></span>
                </div>
            </form>
            <div class="search-bar-close icon-element icon-element-sm shadow-sm">
                <i class="la la-times"></i>
            </div><!-- end off-canvas-menu-close -->
        </div>
    </div><!-- end mobile-search-form -->
    <div class="body-overlay"></div>
</header><!-- end header-menu-area -->
<!--======================================
        END HEADER AREA
======================================-->




@show

@yield('content')


@section('footer')



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "9934407000", // WhatsApp number
            call: "9934407000", // Call phone number
            call_to_action: "Message us", // Call to action
            button_color: "#FF318E", // Color of button
            position: "right", // Position may be 'right' or 'left'
            order: "whatsapp,call", // Order of buttons
        };
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
 
 <style>
.enquiry-to-zorgers {
    position: fixed;
    right: 0;
    top: 148px;
    z-index: 2;
}
</style>


<div class="enquiry-to-zorgers">
		<a href="tel:9934407000" class="enquiry-to-zorgers"><img src="https://www.zorgers.com/assets1.0/images/components/enquiry-form-with-banner/enquiry-to-zorger-btn.png" alt="Need " class="img-responsive"></a>
	</div>



<section class="footer-area pt-100px">
   
   
   <div class="container">
        <div class="row">
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold pb-2">About</h3>
                    <div class="divider border-bottom-0"><span></span></div>
                    <ul class="generic-list-item">
                        <li><a href="/about">About us</a></li>
                        <li><a href="/contact">Contact us</a></li>
                        <li><a href="/contact">Become a Teacher</a></li>
                        <li><a href="/">Support</a></li>
                      
                    </ul>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold pb-2">Courses</h3>
                    <div class="divider border-bottom-0"><span></span></div>
                    <ul class="generic-list-item">
                        <li><a href="/category/banking/courses">Banking</a></li>
                        <li><a href="/category/insurance/courses">INSURANCE</a></li>
                        <li><a href="/category/jaiibcaiib/courses">JAIIB/CAIIB</a></li>
                        <li><a href="/category/upsc/courses">UPSC</a></li>
                        
                    </ul>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold pb-2">Download App</h3>
                    <div class="divider border-bottom-0"><span></span></div>
                    <div class="mobile-app">
                        <p class="pb-3 lh-24">Download our mobile app and learn on the go.</p>
                        
                        <a href="#" class="d-block hover-s"><img src="https://agm.cpci.ca/images/download-android.png" style="height:70px" alt="Google play store" class="img-fluid"></a>
                    </div>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
            <div class="col-lg-3 responsive-column-half">
                <div class="footer-item">
                    <h3 class="fs-20 font-weight-semi-bold pb-2">Newsletter</h3>
                    <div class="divider border-bottom-0"><span></span></div>
                    <form method="post" class="subscriber-form">
                        <p class="pb-3 lh-24">Want us to email you about special offers & updates?</p>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form--control pl-3" placeholder="Enter email address">
                            <button class="btn theme-btn w-100 mt-3" type="button">Subscribe <i class="la la-arrow-right icon ml-1"></i></button>
                        </div>
                    </form>
                </div><!-- end footer-item -->
            </div><!-- end col-lg-3 -->
        </div><!-- end row -->
    </div><!-- end container -->
    <div class="section-block"></div>
   
    <div class="copyright-content py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="copy-desc">&copy; 2021 .  <a href="">livetutorials</a></p>
                </div><!-- end col-lg-6 -->
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap align-items-center justify-content-end">
                        <ul class="generic-list-item d-flex flex-wrap align-items-center fs-14">
                            <li class="mr-3"><a href="/terms-and-conditions">Terms & Conditions</a></li>
                            <li class="mr-3"><a href="/privacy">Privacy Policy</a></li>
                             <li class="mr-3"><a href="/refund-policy">Refund Policy</a></li>
                        </ul>
                        <div class="select-container select-container-sm">
                            <select class="select-container-select">
                                <option value="1">English</option>
                               
                            </select>
                        </div>
                    </div>
                </div><!-- end col-lg-6 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end copyright-content -->
</section><!-- end footer-area -->
<!-- ================================
          END FOOTER AREA
================================= -->

<!-- start scroll top -->
<div id="scroll-top">
    <i class="la la-arrow-up" title="Go top"></i>
</div>
<!-- end scroll top -->

<div class="tooltip_templates">
    <div id="tooltip_content_1">
        <div class="card card-item">
            <div class="card-body">
                <p class="card-text pb-2">By <a href="teacher-detail.html">Jose Portilla</a></p>
                <h5 class="card-title pb-1"><a href="course-details.html">The Business Intelligence Analyst Course 2021</a></h5>
                <div class="d-flex align-items-center pb-1">
                    <h6 class="ribbon fs-14 mr-2">Bestseller</h6>
                    <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">November 2020</span></p>
                </div>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                    <li>23 total hours</li>
                    <li>All Levels</li>
                </ul>
                <p class="card-text pt-1 fs-14 lh-22">The skills you need to become a BI Analyst - Statistics, Database theory, SQL, Tableau – Everything is included</p>
                <ul class="generic-list-item fs-14 py-3">
                    <li><i class="la la-check mr-1 text-black"></i> Become an expert in Statistics, SQL, Tableau, and problem solving</li>
                    <li><i class="la la-check mr-1 text-black"></i> Boost your resume with in-demand skills</li>
                    <li><i class="la la-check mr-1 text-black"></i> Gather, organize, analyze and visualize data</li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="btn theme-btn flex-grow-1 mr-3"><i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart</a>
                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist"><i class="la la-heart-o"></i></div>
                </div>
            </div>
        </div><!-- end card -->
    </div>
</div><!-- end tooltip_templates -->
<div class="tooltip_templates">
    <div id="tooltip_content_2">
        <div class="card card-item">
            <div class="card-body">
                <p class="card-text pb-2">By <a href="teacher-detail.html">Jose Portilla</a></p>
                <h5 class="card-title pb-1"><a href="course-details.html">Ultimate Adobe Photoshop Training: From Beginner to Pro</a></h5>
                <div class="d-flex align-items-center pb-1">
                    <h6 class="ribbon fs-14 mr-2">Bestseller</h6>
                    <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">November 2020</span></p>
                </div>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                    <li>23 total hours</li>
                    <li>All Levels</li>
                </ul>
                <p class="card-text pt-1 fs-14 lh-22">The skills you need to become a BI Analyst - Statistics, Database theory, SQL, Tableau – Everything is included</p>
                <ul class="generic-list-item fs-14 py-3">
                    <li><i class="la la-check mr-1 text-black"></i> Become an expert in Statistics, SQL, Tableau, and problem solving</li>
                    <li><i class="la la-check mr-1 text-black"></i> Boost your resume with in-demand skills</li>
                    <li><i class="la la-check mr-1 text-black"></i> Gather, organize, analyze and visualize data</li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="btn theme-btn flex-grow-1 mr-3"><i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart</a>
                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist"><i class="la la-heart-o"></i></div>
                </div>
            </div>
        </div><!-- end card -->
    </div>
</div><!-- end tooltip_templates -->
<div class="tooltip_templates">
    <div id="tooltip_content_3">
        <div class="card card-item">
            <div class="card-body">
                <p class="card-text pb-2">By <a href="teacher-detail.html">Jose Portilla</a></p>
                <h5 class="card-title pb-1"><a href="course-details.html">The Complete WordPress Website Business Course</a></h5>
                <div class="d-flex align-items-center pb-1">
                    <h6 class="ribbon fs-14 mr-2">Bestseller</h6>
                    <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">November 2020</span></p>
                </div>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                    <li>23 total hours</li>
                    <li>All Levels</li>
                </ul>
                <p class="card-text pt-1 fs-14 lh-22">The skills you need to become a BI Analyst - Statistics, Database theory, SQL, Tableau – Everything is included</p>
                <ul class="generic-list-item fs-14 py-3">
                    <li><i class="la la-check mr-1 text-black"></i> Become an expert in Statistics, SQL, Tableau, and problem solving</li>
                    <li><i class="la la-check mr-1 text-black"></i> Boost your resume with in-demand skills</li>
                    <li><i class="la la-check mr-1 text-black"></i> Gather, organize, analyze and visualize data</li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="btn theme-btn flex-grow-1 mr-3"><i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart</a>
                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist"><i class="la la-heart-o"></i></div>
                </div>
            </div>
        </div><!-- end card -->
    </div>
</div><!-- end tooltip_templates -->


<!-- template js files -->
<script src="/nglive/js/jquery-3.4.1.min.js"></script>
<script src="/nglive/js/bootstrap.bundle.min.js"></script>
<script src="/nglive/js/bootstrap-select.min.js"></script>
<script src="/nglive/js/owl.carousel.min.js"></script>
<script src="/nglive/js/isotope.js"></script>
<script src="/nglive/js/waypoint.min.js"></script>
<script src="/nglive/js/jquery.counterup.min.js"></script>
<script src="/nglive/js/fancybox.js"></script>
<script src="/nglive/js/datedropper.min.js"></script>
<script src="/nglive/js/emojionearea.min.js"></script>
<script src="/nglive/js/tooltipster.bundle.min.js"></script>
<script src="/nglive/js/animated-headline.js"></script>
<script src="/nglive/js/jquery.lazy.min.js"></script>
<script src="/nglive/js/main.js"></script>


<script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/graingert-wow/1.2.2/wow.js"></script>

  <script>
  var wow = new WOW(
  {
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       true,       // trigger animations on mobile devices (default is true)
    live:         true,       // act on asynchronously loaded content (default is true)
    callback:     function(box) {
      // the callback is fired every time an animation is started
      // the argument that is passed in is the DOM node being animated
    },
    scrollContainer: null // optional scroll container selector, otherwise use window
  }
);
wow.init();
  </script>
              
    <script>
        const player = new Plyr('#player');
$(document).on("click",".close",function(){
    player.pause();
})
       
    </script>

 <script type="text/javascript">
     $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
$(document).on('submit','#registerForm', function (e) {
                    e.preventDefault();
                    console.log('he')
                    var $this = $(this);

                    $.ajax({
                        type: $this.attr('method'),
                        url: "https://livetutorials.in/register",
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (data) {
                            $('#first-name-error').empty()
                            $('#last-name-error').empty()
                            $('#email-error').empty()
                            $('#password-error').empty()
                            $('#captcha-error').empty()
                            if (data.errors) {
                                if (data.errors.first_name) {
                                    $('#first-name-error').html(data.errors.first_name[0]);
                                }
                                if (data.errors.last_name) {
                                    $('#last-name-error').html(data.errors.last_name[0]);
                                }
                                if (data.errors.email) {
                                    $('#email-error').html(data.errors.email[0]);
                                }
                                if (data.errors.password) {
                                    $('#password-error').html(data.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (data.errors[captcha]) {
                                    $('#captcha-error').html(data.errors[captcha][0]);
                                }
                            }
                            if (data.success) {
                                $('#registerForm')[0].reset();
                                $('#register').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                $('#login').addClass('active').removeClass('fade')
                                $('.success-response').empty().html("Registration Successful. Please LogIn");
                            }
                        }
                    });
});      
 $('#loginForm').on('submit', function (e) {
                    e.preventDefault();

                    var $this = $(this);
                    $('.success-response').empty();
                    $('.error-response').empty();
 $('#login-email-error').empty();
                            $('#login-password-error').empty();
                            $('#login-captcha-error').empty();
                    $.ajax({
                        type: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (response) {
                            $('#login-email-error').empty();
                            $('#login-password-error').empty();
                            $('#login-captcha-error').empty();

                            if (response.errors) {
                                if (response.errors.email) {
                                    $('#login-email-error').html(response.errors.email[0]);
                                }
                                if (response.errors.password) {
                                    $('#login-password-error').html(response.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (response.errors[captcha]) {
                                    $('#login-captcha-error').html(response.errors[captcha][0]);
                                }
                            }
                            if (response.success) {
                                $('#loginForm')[0].reset();
                                if (response.redirect == 'back') {
                                  
                                    location.reload();
                                } else {
                                    window.location.href = "https://livetutorials.in/user/dashboard"
                                }
                            }
                        },
                        error: function (jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                            console.log(jqXHR)
                            if (response.message) {
                                $('#loginx').find('span.error-response').html(response.message)
                            }
                        }
                    });
                    return false;
                });
});


$(document).ready(function(){
  $(".autolist").css("width",$(".autokeyu").width());
  $(".autolist1").css("width",$(".autokey1u").width());
  
  $(document).on("keyup",".autokey",function(){
      var k=$(this).val();
      $.ajax({
          url:'/serach-course',
          type:'POST',
          data:{key:k,_token:$('meta[name="csrf-token"]').attr('content')},
          success:function(res){
              if(res.success){
                  var html="";
                  for(var i=0;i<res.data.length;i++){
                    html += "<li><a href='"+res.data[i].href+"'>"+res.data[i].name+"</a></li>"  
                  }
                  $(".autolist").html(html);
                  $(".autolist").show();
              }else{
                  $(".autolist").hide()
              }
          }
      })
  })


  $(document).on("keyup",".autokey1",function(){
      var k=$(this).val();
      $.ajax({
          url:'/serach-course',
          type:'POST',
          data:{key:k,_token:$('meta[name="csrf-token"]').attr('content')},
          success:function(res){
              if(res.success){
                  var html="";
                  for(var i=0;i<res.data.length;i++){
                    html += "<li><a href='"+res.data[i].href+"'>"+res.data[i].name+"</a></li>"  
                  }
                  $(".autolist1").html(html);
                  $(".autolist1").show();
              }else{
                  $(".autolist1").hide()
              }
          }
      })
  })
})
</script>

@yield('page_js')
</body>
</html>



@show