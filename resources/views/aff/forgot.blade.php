
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    
  
    <title>Password Recovery</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/icheck.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/custom.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/components.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/login-register.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/style.css">
    <!-- END: Custom CSS-->

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="row flexbox-container">
  <div class="col-12 d-flex align-items-center justify-content-center">
    <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
      <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
        <div class="card-header border-0 pb-0">
          <div class="card-title text-center">
            <img src="https://livetutorials.in/ltlogo.png" alt="branding logo">
          </div>
          <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>Password Recovery</span></h6>
        </div>
        <div class="card-content">
          <div class="text-center">
          
       
          <div class="card-body">
            <div class="alert alert-danger mb-2" role="alert">
              <strong>Oh snap!</strong> Change a few things up and submit again.
            </div>
            <form class="form-horizontal" method="post" novalidate>
              {{csrf_field()}}
            
              <fieldset class="form-group position-relative has-icon-left">
                <input type="email" class="form-control" id="user-email" name="email" placeholder="Your Email Address" required>
                <div class="form-control-position">
                  <i class="la la-envelope"></i>
                </div>
              </fieldset>
             

             
             
              <button type="submit" class="btn btn-outline-info btn-block"><i class="la la-user"></i> Recover Password</button>
            </form>
          </div>
          <div class="card-body">
            <a href="/affiliate/login" class="btn btn-outline-danger btn-block"><i class="ft-unlock"></i>
              Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

        </div>
      </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="/aff/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="/aff/js/jqBootstrapValidation.js"></script>
    <script src="/aff/js/icheck.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="/aff/js/app-menu.min.js"></script>
    <script src="/aff/js/app.min.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="/aff/js/form-login-register.min.js"></script>
    <!-- END: Page JS-->

  </body>
  <!-- END: Body-->
</html>