<!doctype html>
<html lang="zxx" class="dark">
  <head>
    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="keywords" content="">
    <meta name="description" content="1">
   @yield('title')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('newassets/img/favicon.png')}}">
    <!-- CSS Template -->
    <link href="/public/newassets/css/theme.css" rel="stylesheet">
    <link href="https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css" rel="stylesheet">
@yield('page_css')
<style>
  .btn-secondary-cus {
--bs-btn-color: #fff;
    --bs-btn-bg: #03c;
    --bs-btn-border-color: #03c;
    --bs-btn-hover-color: #fff; 
    --bs-btn-hover-bg: #0033ccc2;
    --bs-btn-hover-border-color: #0033ccc2;
    --bs-btn-focus-shadow-rgb: 56,224,165;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #0033ccc2;
    --bs-btn-active-border-color: #0033ccc2;
    --bs-btn-active-shadow: unset;
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #03c;
    --bs-btn-disabled-border-color: #03c;
}

hr {
    
    width: 100%;
}
.mtp-20{
  margin-top: 20px;
}


.bg-cover{
        background-position-x: center;
        background-position-y: -78%;
}


  </style>
  </head>
  <body>
    <!-- Skippy & Prload -->
    <!-- skippy -->
   <!--  <a id="skippy" class="skippy visually-hidden-focusable overflow-hidden" href="#content">
      <div class="container">
        <span class="u-skiplink-text">Skip to main content</span>
      </div>
    </a> -->
    <!-- End skippy -->
    <!-- Preload -->
    <div id="loading" class="loading-preloader">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <!-- End Preload -->
    <!-- Edn Skippy & Prload -->
    <!-- 
    ========================
        Wrapper 
    ========================
    -->
    <div class="wrapper">
      <!--  -->
      <!-- Header -->
      <!-- Header -->
      
       @include("frontend.include.header")
      <!-- End Header -->
      <!-- End Header -->
      <!-- Main -->
       @yield('content')
      <!-- End Main -->
      <!-- Footer -->
      
       @include("frontend.include.footer")
      <!-- End Footer -->
    </div>
    <!-- 
    ========================
       End Wrapper 
    ========================
    -->
    <!-- script start -->
    <!-- Theme JS -->
    <script src="/public/newassets/js/jquery-3.5.1.min.js"></script>
    <!--bootstrap-->
    <script src="/public/newassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- headroom JS -->
    <script src="/public/newassets/vendor/headroom/headroom.min.js"></script>
    <!-- swiper JS -->
    <script src="/public/newassets/vendor/swiper/swiper-bundle.min.js"></script>
    <!-- purecounter JS -->
    <script src="/public/newassets/vendor/purecounter/purecounter_vanilla.js"></script>
    <!-- isotope JS -->
    <script src="/public/newassets/vendor/isotope/isotope.pkgd.min.js"></script>
    <!-- magnific JS -->
    <script src="/public/newassets/vendor/magnific/jquery.magnific-popup.min.js"></script>
    <!-- magnific JS -->
    <script src="/public/newassets/vendor/highlight/highlight.min.js"></script>
    <!-- magnific JS -->
    <script src="/public/newassets/vendor/typed/typed.js"></script>
    <!-- svginjector JS -->
    <script src="/public/newassets/vendor/svginjector/svg-injector.min.js"></script>
    <!-- wow JS -->
    <script src="/public/newassets/vendor/wow/wow.min.js"></script>
    <!-- wow JS -->
    <script src="/public/newassets/vendor/easy-pie-chart/jquery.easypiechart.min.js"></script>
    <!-- countdown JS -->
    <script src="/public/newassets/vendor/count-down/jquery.countdown.min.js"></script>
    <!-- one-page JS -->
    <script src="/public/newassets/vendor/one-page/scrollIt.min.js"></script>
    <!-- working form -->
    <script src="/public/newassets/vendor/mail/js/form.min.js"></script>
    <script src="/public/newassets/vendor/mail/js/script.js"></script>
    <!-- Theme JS -->
    <script src="/public/newassets/js/theme-jquery.js"></script>
    <!-- Theme JS -->
    <script src="/public/newassets/js/theme.js"></script>
    <!-- End script start -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-S2LP3EM8C4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-S2LP3EM8C4');
</script>
    @yield('page_js')
  </body>
 
</html>