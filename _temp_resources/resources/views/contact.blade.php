@extends('frontend.layout.sub-master')
@section('title')
<title>Live Online Learning Classes for School Students | {{env('APP_NAME')}}</title>
<meta name="description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Contact | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!" />
    <meta property="og:url" content="{{URL::to('/contact')}}" />
    <meta property="og:site_name" content="Live Online Learning Classes for School Students | {{env('APP_NAME')}}" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Live Online Learning Classes for School Students | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="Are You Looking For Online Live Learning Platform for School Students? Access interactive Online Classes designed for Student Success. Enroll Now!" />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/contact')}}">

@stop
@section('content')
 
<style type="text/css">
   .bg-gray-101 {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-gray-100-rgb), var(--bs-bg-opacity)) !important;
}
@media  only screen and (max-width: 600px) {
        .mtp-20{
  margin-top: 20px !important;
}
}
</style>

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1 ">Contact Us</h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
              </ol>
            </div>
         </div>
      </div>
   </section>
 

   <section class="section ">
   <div class="container">
      <div class="row gy-5">
         <div class="col-md-12">
                              <h6 class="h6">
                                 Team VaaGa is here to provide you the needed support and understand all your needs.  Please feel free to connect with us for any query, feedback or support.
                              </h6>
                              <p>Fill in your details in the contact form below, and our team will contact you soon.</p>
                            </div>
         <div class="col-lg-6">
            <div class="card">
               <div class="card-body p-lg-4">
                  <h3 class="h3 mb-3">Contact Us</h3>
                 
                 <form class="" method="post" action="{{route('contact.send')}}" id="demo-form" >
    {{csrf_field()}}
                  @if(Session::has("flash_message"))
                  <div class="alert alert-success">
                     {!! Session::get("flash_message") !!}
                  </div>
                  @endif

                   @if(Session::has("flash_error"))
                  <div class="alert alert-danger">
                     {!! Session::get("flash_error") !!}
                  </div>
                  @endif

                        <div class="row">

                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label class="form-label rd-input-label focus not-empty">Name</label> 
                              <input type="text" class="form-control" value="{{old('name')}}"  name="name" placeholder="Enter your first Name">
                              
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group mb-3"><label class="form-label rd-input-label focus not-empty">Email</label> <input type="text" class="form-control" name="email" required placeholder="Enter your email address" value="{{old('email')}}" >
                              
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label class="form-label rd-input-label focus not-empty">Phone</label> 
                              <!-- <input type="number" class="form-control" required name="phone" value="{{old('phone')}}"  placeholder="Enter your Phone number"> -->
                            
                              <input type="text" class="form-control" placeholder="Enter your Phone number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');" name="phone" maxlength="10" pattern="\d{10}" value="{{old('phone')}}"/>
                            
                           </div>
                        </div> 
                        <div class="col-md-12">
                           <div class="form-group mb-3">
                              <label class="form-label rd-input-label focus not-empty">Description</label> 
                              <textarea class="form-control" name="message" required placeholder="Write down here" rows="4">{{old('message')}}</textarea>
                             
                           </div>
                        </div>
                        
                       <div class="mb15">
                        <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div> 

                        <div class="col-md-12 pt-2">
                         
                           <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>

                        </div>
 
                  </form> 
               </div>
            </div>
         </div>
         <div class="col-lg-6 ps-xl-12 pl-lg-6">
            <h3 class="h2 mb-2">Get in Touch</h3>
           
            <ul class="list-unstyled mb-0 pt-3 border-bottom pb-2 mb-2" >
               <li class="py-2 px-2 d-flex mb-3  shadow" style="border-radius: 6px;">
                  <a href="tel:+91 98203 80469" style="display:inline-flex;">
                  <div class="icon icon-md text-primary bg-gray-101 rounded-circle dots-icon"><i class="bi bi-phone"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="ps-3">
                     <h6 class="mb-1">Phone</h6>
                     <span>+91 98203 80469</span>
                  </div>
               </a>
               </li>
               <li class="py-2 px-2 d-flex  mb-3  shadow" style="border-radius: 6px;">
                    <a href="mailto:info@vaagaacademy.com" style="display:inline-flex;">
                  <div class="icon icon-md text-primary bg-gray-101 rounded-circle dots-icon"><i class="bi bi-envelope"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="ps-3">
                     <h6 class="mb-1">Email</h6>
                     <span>info@vaagaacademy.com</span>
                  </div></a>
               </li>
               <li class="py-2 px-2 d-flex   mb-3 shadow" style="border-radius: 6px;">
              <div class="icon icon-md text-primary bg-gray-101 rounded-circle dots-icon"><i class="bi bi-chat-square-text"></i> <span class="dots"><i class="dot dot1"></i><i class="dot dot2"></i><i class="dot dot3"></i></span></div>
                  <div class="ps-3">
                      <h6 class="mb-1">Follow us </h5>
                     <div class="nav">
                        <a class="icon-sm bg-light rounded-circle me-2" href="https://www.facebook.com/profile.php?id=100095502854507"  target="_blank"><i style="    font-size: 20px;color:#1877f2" class="bi-facebook"></i> </a>
                        <a class="icon-sm bg-light rounded-circle me-2" href="https://www.instagram.com/vaagaacademy/" target="_blank"><i style="    font-size: 20px;color:#d74d5d" class="bi-instagram"></i> </a>
                        <a class="icon-sm bg-light rounded-circle me-2" href="https://www.linkedin.com/company/96911976/admin/feed/posts/"  target="_blank"><i style="    font-size: 20px;color:#0a66c2" class="bi-linkedin"></i></a>
                        <a class="icon-sm  rounded-circle me-2" href="https://www.youtube.com/@VaaGaAcademy" target="_blank"><i style="    font-size: 25px;color:#d74d5d" class="bi-youtube"></i></a>
                         <a class="icon-sm  rounded-circle me-2" href="https://twitter.com/VaaGaAcademy" target="_blank"><i style="font-size: 25px; color:black" class="bi bi-twitter-x"></i></a>

                     </div>
                  </div>
               </li>
              
               
            </ul>
            <h3 class="h5 mb-2">Address</h3>
            <p>Sector-86, Gurugram, Haryana
Pin: 122004
</p>
<div class="row">
   <div class="col-12">
      <div class="embed-responsive embed-responsive-16by9">
         <iframe
            class="embed-responsive-item w-100"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14038.753565893761!2d76.93077559278743!3d28.39847823830795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d3d936c24165f%3A0xe9ea51f237f41f40!2sSector%2086%2C%20Gurugram%2C%20Haryana%20122505!5e0!3m2!1sen!2sin!4v1690605693809!5m2!1sen!2sin"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
         ></iframe>
      </div>
   </div>
</div>

            {{-- <div class="pt-1">
              
               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14038.753565893761!2d76.93077559278743!3d28.39847823830795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d3d936c24165f%3A0xe9ea51f237f41f40!2sSector%2086%2C%20Gurugram%2C%20Haryana%20122505!5e0!3m2!1sen!2sin!4v1690605693809!5m2!1sen!2sin" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div> --}}
         </div>
      </div>
   </div>
</section>
</main>

@stop 


@section('page_js') 
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>

 function onSubmit(token) {
       console.log(token);
     document.getElementById("demo-form").submit();
   }


</script>
@stop