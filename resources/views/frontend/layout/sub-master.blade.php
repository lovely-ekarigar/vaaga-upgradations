<!doctype html>
<html lang="zxx" class="dark">
<head>

  <meta charset="utf-8">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
   @yield('title')
  @yield('meta')
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="google-site-verification" content="xR0mS6EihuUzIvoKKOFDXdBCvh4JP8tstqV9Ea7tVyI" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('newassets/img/favicon.png') }}">
  <!-- Bootstrap 5 CSS (fallback when newassets is missing) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- CSS Template (theme - loads when public/newassets exists) -->
  <link href="{{ asset('newassets/css/theme.css') }}" rel="stylesheet">
  @if(file_exists(public_path('css/frontend.css')))
  <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
  @endif

  <link href="https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css" rel="stylesheet" defer>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" defer />

  @yield('page_css')
  <style>
    /* === Single source: layout & CSS fixes (do not duplicate elsewhere) === */
    .wrapper { min-height: 100vh; display: flex; flex-direction: column; }
    main { flex: 1; padding-top: 76px; }
    .main-header .container { display: flex; flex-wrap: wrap; align-items: center; }
    .main-header.headroom--unpinned {
      transform: translateY(0%) !important;
    }
    .bg-cover{ object-fit: cover; }
    .logo-mob-sub-header{ object-fit: contain; max-height: 50px; }
    /* Footer when theme.css missing */
    .footer { background: #2d3748; color: #e2e8f0; }
    .footer .link-white, .footer a.link-white { color: #e2e8f0; text-decoration: none; }
    .footer .link-white:hover { color: #fff; }
    .btn-outline-white { color: #fff; border-color: #fff; background: transparent; }
    .btn-outline-white:hover { color: #000; background: #fff; border-color: #fff; }
    .bg-cover {
      background-position-x: center;
      background-position-y: -78%;
    }

    .mtp-20 {
      margin-top: 0px;
    }

    hr {

      width: 100%;
    }

    .btn-secondary-cus {
      --bs-btn-color: #fff;
      --bs-btn-bg: #ffbe3d;
      --bs-btn-border-color: #ffbe3dc4;
      --bs-btn-hover-color: #fff;
      --bs-btn-hover-bg: #ffbe3dc4;
      --bs-btn-hover-border-color: #ffbe3dc4;
      --bs-btn-focus-shadow-rgb: 56, 224, 165;
      --bs-btn-active-color: #fff;
      --bs-btn-active-bg: #ffbe3dc4;
      --bs-btn-active-border-color: #ffbe3dc4;
      --bs-btn-active-shadow: unset;
      --bs-btn-disabled-color: #fff;
      --bs-btn-disabled-bg: #ffbe3d;
      --bs-btn-disabled-border-color: #ffbe3d;
    }

    /*.bg-gray-100 {*/
    /*    background: #4a2a51 !important;*/
    /*}*/
    /* Extra small devices (phones, 600px and down) */
    @media only screen and (max-width: 600px) {
      .mtp-20 {
        margin-top: 20px;
      }

      .page-heading-pad {
        padding-top: 6rem !important;
        padding-bottom: 2rem !important;
      }

      .logo-mob-sub-header {
        width: 30% !important;
      }

      .cus-bec-tecg-h3 {
        color: #fff !important;
      }

    }

    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {

      .mtp-20 {
        margin-top: 20px;
      }

      .page-heading-pad {
        padding-top: 6rem !important;
        padding-bottom: 2rem !important;
      }

      .logo-mob-sub-header {
        width: 30% !important;
      }
    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
      .mtp-20 {
        margin-top: 20px;
      }

      .page-heading-pad {
        padding-top: 6rem !important;
        padding-bottom: 2rem !important;
      }

      .logo-mob-sub-header {
        width: 30% !important;
      }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
      .mtp-20 {
        margin-top: 0px;
      }

      .page-heading-pad {
        padding-top: 12rem !important;
        padding-bottom: 6rem !important;
      }

      .logo-mob-sub-header {
        width: 16% !important;
      }
    }

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {
      .mtp-20 {
        margin-top: -40px;
      }

      .page-heading-pad {
        padding-top: 12rem !important;
        padding-bottom: 6rem !important;
      }

      .logo-mob-sub-header {
        width: 16% !important;
      }
    }

    .bg-0000_ {
      background-color: #535b6994 !important;
    }
  </style>
  
  <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-S2LP3EM8C4"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-S2LP3EM8C4'); </script>
  
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7R35RVN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

  <div class="wrapper">
    <!--  -->
    <!-- Header -->
    <!-- Header -->

    @include("frontend.include.sub-header")
    <!-- End Header -->
    <!-- End Header -->
    <!-- Main -->
    @yield('content')
    <!-- End Main -->
    <!-- Footer -->

    @include("frontend.include.footer")
    @include("frontend.layouts.modals.enquiry")
    @if(!Session::has('modelClose'))
    @endif
    <!-- End Footer -->
  </div>
  <!-- 
    ========================
       End Wrapper 
    ========================
    -->
  <!-- script start -->
  <!-- jQuery & Bootstrap from CDN (works when newassets folder is missing) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <!-- Theme JS from newassets (optional - when folder exists) -->
  <script src="{{ asset('newassets/vendor/headroom/headroom.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/swiper/swiper-bundle.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/purecounter/purecounter_vanilla.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/isotope/isotope.pkgd.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/magnific/jquery.magnific-popup.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/highlight/highlight.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/typed/typed.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/svginjector/svg-injector.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/wow/wow.min.js') }}" defer></script>
  <script src="{{ asset('newassets/vendor/one-page/scrollIt.min.js') }}" defer></script>
  <script src="{{ asset('newassets/js/theme-jquery.js') }}" defer></script>
  <script src="{{ asset('newassets/js/theme.js') }}"></script>
  <!-- End script start -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" defer></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <script>
    $(document).on("click", ".enquiry", function(){
      var el = document.getElementById('enquiryModal');
      if (el && typeof bootstrap !== 'undefined') {
        var m = bootstrap.Modal.getOrCreateInstance(el);
        m.show();
      }
    });
    $(document).on("click", ".close", function(){
      var el = document.getElementById('enquiryModal');
      if (el && typeof bootstrap !== 'undefined') {
        var m = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
      }
    });
  </script>
  <!--Start of Tawk.to Script-->

  <script type="text/javascript">
    $(document).ready(function() {
      $('.js-example-basic-multiple').select2();
    });
  </script>

  
  <!-- metas -->
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-T7R35RVN');
  </script>
  <!-- End Google Tag Manager -->
  
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async defer></script>
  <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
      OneSignal.init({
        appId: "8fc1ccb9-429d-42c7-b6f3-837d6aa2592f",
        safari_web_id: "web.onesignal.auto.5093406a-927f-4c57-a877-608b6718a7cc",
        notifyButton: {
          enable: true,
        },
      });
    });
  </script>
  @yield('page_js')
</body>

</html>