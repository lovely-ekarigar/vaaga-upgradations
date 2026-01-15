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
  <link rel="shortcut icon" href="{{asset('newassets/img/favicon.png')}}">
  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"> -->

  <!-- CSS Template -->
  <link href="/newassets/css/theme.css" rel="stylesheet">

  <link href="https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css" rel="stylesheet" defer>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" defer />

  @yield('page_css')
  <style>
    .main-header.headroom--unpinned {
      transform: translateY(0%) !important;
    }
    .bg-cover{
        object-fit: cover;
    }
.logo-mob-sub-header{
    object-fit: cover;
}
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
  <!-- Theme JS -->
  <script src="/newassets/js/jquery-3.5.1.min.js"></script>
  <!--bootstrap-->
  <script src="/newassets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
  <!-- headroom JS -->
  <script src="/newassets/vendor/headroom/headroom.min.js" defer></script>
  <!-- swiper JS -->
  <script src="/newassets/vendor/swiper/swiper-bundle.min.js" defer></script>
  <!-- purecounter JS -->
  <script src="/newassets/vendor/purecounter/purecounter_vanilla.js" defer></script>
  <!-- isotope JS -->
  <script src="/newassets/vendor/isotope/isotope.pkgd.min.js" defer></script>
  <!-- magnific JS -->
  <script src="/newassets/vendor/magnific/jquery.magnific-popup.min.js" defer></script>
  <!-- magnific JS -->
  <script src="/newassets/vendor/highlight/highlight.min.js" defer></script>
  <!-- magnific JS -->
  <script src="/newassets/vendor/typed/typed.js" defer></script>
  <!-- svginjector JS -->
  <script src="/newassets/vendor/svginjector/svg-injector.min.js" defer></script>
  <!-- wow JS -->
  <script src="/newassets/vendor/wow/wow.min.js" defer></script>
  <script src="/newassets/vendor/one-page/scrollIt.min.js" defer></script>

  <script src="/newassets/js/theme-jquery.js" defer></script>
  <!-- Theme JS -->
  <script src="/newassets/js/theme.js"></script>
  <!-- End script start -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" defer></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <script>
            $(document).on("click",".enquiry",function(){
         
         $("#enquiryModal").modal('show')   
            
        })
        $(document).on("click",".close",function(){
            $("#enquiryModal").modal('hide')   
            
        })
        
        
//         document.addEventListener("DOMContentLoaded", () => {
//     setTimeout(() => {
//         const myModal = new bootstrap.Modal(document.getElementById('enquiryModal'));
//         myModal.show();
//     }, 1000); 
//     $(document).on('click','.close', function(){
//       <?php Session::put('modelClose',true) ?>
//       console.log('now Session stored');
//       $('#enquiryModal').modal('hide');
//     });
// }); 
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