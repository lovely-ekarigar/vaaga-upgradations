<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() == 'ar') dir="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if(config('favicon_image') != "")
            <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/logos/'.config('favicon_image'))}}"/>
        @endif
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="keywords" content="@yield('meta_keywords', '')">

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
     
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
          
    <link href="{{asset('ng/css/bootstrap.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/font-awesome.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/line-awesome.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/animate.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/owl.carousel.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/owl.theme.default.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/bootstrap-select.min.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/magnific-popup.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/fancybox.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/tooltipster.bundle.css')}}" rel="alternate stylesheet" type="text/css">
    <link href="{{asset('ng/css/style.css')}}" rel="alternate stylesheet" type="text/css">
              
    
              

        @yield('css')
        @stack('after-styles')

        @if(config('onesignal_status') == 1)
            {!! config('onesignal_data') !!}
        @endif

        @if(config('google_analytics_id') != "")
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{config('google_analytics_id')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{config('google_analytics_id')}}');
        </script>
            @endif


    </head>
    <body class="{{config('layout_type')}}">

    <div id="app">
    {{--<div id="preloader"></div>--}}
    @include('frontend.layouts.modals.loginModal')


    <!-- Start of Header section
        ============================================= -->
   <header class="header-menu-area">
    <div class="header-menu-fluid">
        <div class="header-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-widget header-widget1">
                            <ul class="contact-info d-flex align-items-center">
                                <li><a href="#"><span class="la la-phone"></span> +91 </a> </li>
                                <li><a href="#"><span class="la la-envelope-o"></span> info@radhetutorial.com</a></li>
                            </ul>
                        </div><!-- end header-widget -->
                    </div><!-- end col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="header-widget header-widget2 d-flex align-items-center justify-content-end">
                            <div class="header-right-info">
                                <ul class="social-profile d-flex align-items-center">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <div class="header-right-info d-flex align-items-center">
                                <ul class="user-cart d-flex align-items-center ">
                                  
                                </ul>
                                <ul class="user-action d-flex align-items-center">
                                    <li><a href="login">Login</a></li>
                                    <li><span>or</span></li>
                                    <li><a href="">Register</a></li>
                                </ul>
                            </div>
                        </div><!-- end header-widget -->
                    </div><!-- end col-lg-6 -->
                </div><!-- end row -->
            </div><!-- end container-fluid -->
        </div><!-- end header-top -->
        <div class="header-menu-content">
            <div class="container-fluid">
                <div class="main-menu-content">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-3">
                            <div class="logo-box">
                                <a href="" class="logo" title=""><img src="assets/tdlogo.png" alt="logo" style="width:230px;height: 41px;"></a>
                                
                            </div>
                        </div><!-- end col-lg-3 -->
                        <div class="col-lg-9">
                            <div class="menu-wrapper">
                               
                                <nav class="main-menu">
                                    <ul>
                                        <li><a href="">Home</a></li>
                                        <li><a href="">About</a></li>

                                        <li><a href="">Contact</a></li>
                                        
                                        
                                        
                                    </ul><!-- end ul -->
                                </nav><!-- end main-menu -->
                                <div class="logo-right-button">
                                    <ul>
                                        <li><a href="" class="theme-btn">Admission</a></li>
                                    </ul>
                                    <div class="side-menu-open">
                                        <i class="la la-bars"></i>
                                    </div>
                                </div><!-- end logo-right-button -->
                                <div class="side-nav-container">
                                    <div class="humburger-menu">
                                        <div class="humburger-menu-lines side-menu-close"></div><!-- end humburger-menu-lines -->
                                    </div><!-- end humburger-menu -->
                                    <div class="side-menu-wrap">
                                        <ul class="side-menu-ul">
                                            
                                            
                                            
                                    <li class="sidenav__item"><a href="">Home</a></li>
                                        <li class="sidenav__item"><a href="">About</a></li>

                                        <li class="sidenav__item"><a href="">Contact</a></li>
                                            
                                            
                                            
                                         
                                        </ul>
                                        <div class="side-btn-box">
                                            <a href="login" class="theme-btn">login</a>
                                            <span>or</span>
                                            <a href="register" class="theme-btn">register</a>
                                        </div>
                                    </div><!-- end side-menu-wrap -->
                                </div><!-- end side-nav-container -->
                            </div><!-- end menu-wrapper -->
                        </div><!-- end col-lg-9 -->
                    </div><!-- end row -->
                </div>
            </div><!-- end container-fluid -->
        </div><!-- end header-menu-content -->
    </div><!-- end header-menu-fluid -->
</header><!-- end header-menu-area -->
        <!-- Start of Header section
            ============================================= -->


        @yield('content')
        @include('cookieConsent::index')


        @include('frontend.layouts.partials.footer')

    </div><!-- #app -->

    <!-- Scripts -->

    @stack('before-scripts')

    <!-- For Js Library -->


    <script src="{{asset('ng/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('ng/js/popper.min.js')}}"></script>
    <script src="{{asset('ng/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('ng/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('ng/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('ng/js/magnific-popup.min.js')}}"></script>
    <script src="{{asset('ng/js/isotope.js')}}"></script>
    <script src="{{asset('ng/js/waypoint.min.js')}}"></script>
    <script src="{{asset('ng/js/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('ng/js/particles.min.js')}}"></script>
    <script src="{{asset('ng/js/particlesRun.js')}}"></script>
    <script src="{{asset('ng/js/fancybox.js')}}"></script>
    <script src="{{asset('ng/js/wow.js')}}"></script>
    <script src="{{asset('ng/js/date-time-picker.js')}}"></script>
    <script src="{{asset('ng/js/jquery.filer.min.js')}}"></script>
    <script src="{{asset('ng/js/emojionearea.min.js')}}"></script>
    <script src="{{asset('ng/js/smooth-scrolling.js')}}"></script>
    <script src="{{asset('ng/js/tooltipster.bundle.min.js')}}"></script>
    <script src="{{asset('ng/js/main.js')}}"></script>
    


    <script>
        @if(request()->has('user')  && (request('user') == 'admin'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('admin@lms.com')
        $('#loginForm').find('#password').val('secret')

        @elseif(request()->has('user')  && (request('user') == 'student'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('student@lms.com')
        $('#loginForm').find('#password').val('secret')

        @elseif(request()->has('user')  && (request('user') == 'teacher'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('teacher@lms.com')
        $('#loginForm').find('#password').val('secret')

        @endif
    </script>


    <script src="{{asset('assets/js/script.js')}}"></script>
    <script>
        @if((session()->has('show_login')) && (session('show_login') == true))
        $('#myModal').modal('show');
                @endif
        var font_color = "{{config('font_color')}}"
        setActiveStyleSheet(font_color);
    </script>

    @yield('js')

    @stack('after-scripts')

    @include('includes.partials.ga')
    </body>
    </html>
