@extends('frontend.layout.sub-master')
@section('title')
<title>Become a Tutor at VaaGa Academy - Teach Online Olympiad Classes | {{env('APP_NAME')}}</title>


<meta name="description" content="Join VaaGa Academy as an online tutor and teach Olympiad classes for Maths, Science & English. Share your expertise and help students prepare for IMO, NSO & IEO exams.">
<meta name="keywords" content="Become a tutor, Online teaching jobs, Olympiad tutor, Teach Olympiad online, VaaGa Academy tutor, Online tutor jobs, Maths tutor, Science tutor, English tutor, Olympiad coaching jobs, Teaching opportunities">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Become a Tutor at VaaGa Academy - Teach Online Olympiad Classes | {{env('APP_NAME')}}" />
    <meta property="og:description" content="Join VaaGa Academy as an online tutor and teach Olympiad classes for Maths, Science & English. Share your expertise and help students prepare for IMO, NSO & IEO exams." />
    <meta property="og:url" content="{{URL::to('/become-tutor')}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
    <meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" /> 
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Become a Tutor at VaaGa Academy - Teach Online Olympiad Classes | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="Join VaaGa Academy as an online tutor and teach Olympiad classes for Maths, Science & English. Share your expertise and help students prepare for IMO, NSO & IEO exams." />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />
<link rel="canonical" href="{{URL::to('/become-tutor')}}">
@stop
@section('page_css')

 <link href="/public/style.css" rel="stylesheet">
<style type="text/css">
	@media (min-width: 1200px)
.h3, h3 {
    font-size: 1.75rem !important;
}

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
	.slider-min-size {
    min-height: 50vh !important
}
.slider-min-size h2{
	font-size: 20px !important;
}
.slider-min-size p{
	font-size: 16px !important;
}
.text-white-black{
	color: #000 !important;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
	.slider-min-size {
    min-height: 50vh !important
}
.text-white-black{
	color: #000 !important;
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
	.slider-min-size {
    min-height: 75vh !important
}
.text-white-black{
	color: #000 !important;
}
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
	.slider-min-size {
    min-height: 100vh !important
}
.text-white-black{
	color: #fff !important;
}
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
	.slider-min-size {
    min-height: 100vh !important
}
.text-white-black{
	color: #fff !important;
}
}
</style>
@stop
@section('content')


<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-cover bg-no-repeat" style="background-image: url(/public/newassets/img/bg/bg-banner-8.png);background-position: right;">
		   <div class="container">
		      <div class="row align-items-center justify-content-center slider-min-size py-5 pt-lg-10 align-items-center">
		         <div class="col-lg-12 col-md-12 pe-xl-11 py-4">
		            <h2 class="display-2 fw-700 text-white">Welcome to <br>{{env('PROJECT_NAME')}}</h2>
		            <p class="lead fw-600 text-white text-opacity-85">Join Live and Interactive Online Classes with the best Tutors</p>
		            <div class="pt-2"><a class="btn btn-white me-2" href="#cus-enroll-tutor">Join Us</a> <a class="btn btn-outline-white" href="/contact">Contact Us</a></div>
		         </div>
		         
		      </div>
		   </div>
		</section>




<section class="section">

 <div class="container">
            <div class="row">
                <div class="col-md-12 mb-5 text-center">
                    <h3 class="h1 bg-000-after after-50px pb-3 mb-3 text-warning"> Join Us And Become A Tutor</h3>
                </div>
                <div class="col-md-12">

                    <div class="main-timeline4">
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s" data-wow-mobile="true">
                            <span class="timeline-content">
                                <span class="year"><i class="bi-mortarboard-fill" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Register with us</h3>
                                    <p class="description">
                                        Registering as a tutor with us offers flexible schedules, and a supportive community. It's a chance to enhance your career, increase your earnings, and make a global impact.
                                    </p>
                                </div>
                            </span>
                        </div>
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                            <span class="timeline-content">
                                <span class="year"><i class="bi bi-book-half" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Initial Tutor screening</h3>
                                    <p class="description">
										Our rigorous tutor screening process ensures only the best educators join us, guaranteeing top-quality education for students. Join our community committed to excellence.                               </p>
                                </div>
                            </span>
                        </div>
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.4s">
                            <span class="timeline-content">
                                <span class="year"><i class="bi bi-clipboard-check" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Congratulations! You are active now</h3>
                                    <p class="description">
										Congratulations! You are now an active member of our esteemed tutoring community.              </div>
                            </span>
                        </div>
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.8s">
                            <span class="timeline-content">
                                <span class="year"><i class="bi bi-person-badge" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Update your profile </h3>
                                    <p class="description">
										Upgrade your tutor profile to help students get to know you better. It builds trust and stronger connections for successful learning.                              
                                    </p>
                                </div>
                            </span>
                        </div>
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="1.2s">
                            <span class="timeline-content">
                                <span class="year"><i class="bi bi-calendar2-week" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Student is assigned</h3>
                                    <p class="description">

                                        Congratulations! You're now assigned a student. Get ready to make a positive impact on their learning journey. Good luck!                                  </p>
                                </div>
                            </span>
                        </div>
                        <div class="timeline wowx fadeInUp" data-wow-duration="0.5s" data-wow-delay="1.6s">
                            <span class="timeline-content">
                                <span class="year"> <i class="bi bi-rocket-takeoff" style="font-size: 26px;"></i></span>
                                <div class="inner-content">
                                    <h3 class="title">Let’s start your Teaching Journey!!</h3>
                                    <p class="description">
                                        Exciting times ahead! Let's kickstart your teaching journey with enthusiasm and dedication.                                    </p>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </section>




		<section class="effect-section bg-gray-100 section">
   <div class="shap-top-left">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 463.6 616" style="enable-background:new 0 0 463.6 616;" xml:space="preserve" class="injected-svg svg_img text-warning  h-100">
         <path d="M148.4,608.3C25.7,572.5-3.5,442.2,0.3,375.8s24.8-117,124.8-166.5s125.7-77.4,165-129.6 c43.2-57.4,96.5-94.4,127.9-73c63,43,53.9,280,14,358s-68.9,75.5-98.9,118.7S271,644,148.4,608.3z"></path>
      </svg>
   </div>
   <div class="shap-bottom-right">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 910.6 745.2" style="enable-background:new 0 0 910.6 745.2;" xml:space="preserve" class="injected-svg svg_img text-white h-100">
         <path d="M222.1,0c167.1,0,178,187.8,272.8,212.5s208,2.9,277.5,169.7s138.4,133.9,138.2,217.8 S788.2,745.2,611.8,745.2S446,724,341.2,724c-83.3,0-77.7,16.7-118.4,18.9c-106.6,0-303.1-110.3-187.6-296.3S55,0,222.1,0z"></path>
      </svg>
   </div>
   <div class="container position-relative">
      <div class="row justify-content-between align-items-center">
         <div class="col-lg-6"><img src="/public/newassets/img/home/ai-15.svg" title="" alt=""></div>
         <div class="col-lg-5">
            <div class="icon-lg bg-primary shadow rounded text-white mb-4"><i class="bi-person"></i></div>
            <h3 class="h3 mb-2 text-warning cus-bec-tecg-h3">Become Tutor@ VaaGa Academy</h3>
            <p class="lead mb-4 text-white">Are you passionate about teaching? Want to earn good money just by sharing your knowledge? If so, then VaaGa Academy is the perfect platform for you. Teachers are the building blocks of society who educate people and make them better human beings.</p>
           
               <p class="lead mb-4 text-white">VaaGa Academy is an online learning platform providing personalised Private interactive learning solution and Group classes to students with best learning experience.</p>
           
         
         </div>
      </div>
      <div class="row justify-content-between align-items-center flex-row-reverse section pb-0">
         <div class="col-lg-6"><img src="/public/newassets/img/home/ai-16.svg" title="" alt=""></div>
         <div class="col-lg-5">
            <div class="icon-lg bg-secondary shadow rounded text-white mb-4"><i class="bi-laptop"></i></div>
           <h3 class="h3 mb-2 text-warning">What VaaGa Academy is looking in Tutor</h3>
            
            <ul class="list-type-03 mb-4 list-unstyled">
               <li class="d-flex py-1 text-white-black"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Innovative ways to make learning fun </li>
               <li class="d-flex py-1 text-white-black"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Passionate about teaching</li>
               <li class="d-flex py-1 text-white-black"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Follow certain procedures for great teaching experience</li>
               <li class="d-flex py-1 text-white-black"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Dedicated and consistent availability on daily basis</li>
            </ul>
           
         </div>
      </div>
   </div>
</section>
        

     		<section class="section">
			   <div class="container">
			      <div class="row section-heading justify-content-center">
			         <div class="col-lg-8 col-xl-6 text-center">
			            <h3 class="h1 bg-primary-after after-50px pb-3 mb-3">Why to choose VaaGa Academy?</h3>
			         </div>
			      </div>
			      <div class="row g-3">
			         <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Personalized Approach</h6>
			               <p>Personalised Live online Private and Group classes </p>
			            </div>
			         </div>
			        <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Interactive learning tools</h6>
			               <p>Multimedia elements, Whiteboard, Interactive Quizzes, and real-time collaboration features</p>
			            </div>
			         </div> 
			         <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Connect with Students from anywhere</h6>
			               <p>Connect with Students across the globe</p>
			            </div>
			         </div>
			         
			         <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Choose your suitable time</h6>
			               <p>Flexible scheduling options, so you can book sessions at times that work best for you</p>
			            </div>
			         </div>
			         <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Real time live interaction</h6>
			               <p>Live video sessions for knowledge sharing and help students to achieve best results</p>
			            </div>
			         </div>
			      <!--    <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">No travelling time</h6>
			               <p>Provide expert guidance and support to the students without having to travel to any physical location</p>
			            </div>
			         </div> -->
			         <div class="col-sm-6 col-lg-4">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Flexible Payment Options</h6>
			               <p>Convenient payment modes available</p>
			            </div>
			         </div>
			      
			      </div>
			   </div>
			</section>

				<!-- Section -->
        <section class="section bg-gray-100" id="cus-enroll-tutor">
          <div class="container">
          	<div class="row section-heading justify-content-center">
		         <div class="col-lg-8 col-xl-6 text-center">
		            <h3 class="h1 bg-primary-after after-50px pb-3 mb-3 text-warning">Join us</h3>
		         </div>
		      </div>
            <div class="row align-items-center justify-content-center min-vh-100">
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                  <div class="card-body">
                   <!--  <div class="pb-4 text-center">
                      <h3 class="mb-2">Create Your Teacher Account</h3>
                    </div> -->
                    <form action="" method="post" enctype="multipart/form-data">
											{{csrf_field()}}
	
													 @if(Session::has("flash_message"))
													 <div class="form-group col-md-12" style="text-align:center;">
													                  <div class="alert alert-danger">
													                     {!! Session::get("flash_message") !!}
													                  </div>
													                  </div>
													                  @endif
													                  
													 @if(Session::has('success'))      
													<div class="form-group col-md-12" style="text-align:center;">
													<div class="alert alert-success">{!! Session::get('success') !!}</div>
													</div>
													@endif

                    		<div class="row mb-3">
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">First Name <span class="text-danger">*</span></label>
															<input type="text" value="{{old('first_name')}}" class="form-control" name="first_name" >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Middle Name </label>
															<input type="text" value="{{old('middle_name')}}" class="form-control" name="middle_name" >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Last Name <span class="text-danger">*</span></label>
															<input type="text" value="{{old('last_name')}}" class="form-control" name="last_name">
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Email <span class="text-danger">*</span></label>
															<input type="email" value="{{old('email')}}" class="form-control" name="email">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Phone <span class="text-danger">*</span></label>
															
															<input type="text" class="form-control" placeholder=" Phone " oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');" name="phone" maxlength="10" pattern="\d{10}" value=""/>

														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Date of Birth <span class="text-danger">*</span></label>
															<input type="date" value="{{old('dob')}}" class="form-control" name="dob" >
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<div class="col-md-3">
														<div class="form-group">
															<label class="form-control-label">Gender <span class="text-danger">*</span></label>
															<br>
																<div class="form-check form-check-inline checkbox checkbox-primary">
					                                  <input name="gender" value="male" type="radio" @if(old('gender') == 'male') checked @endif>
					                                  <label >Male</label>
					                                </div>
					                                <div class="form-check form-check-inline checkbox checkbox-primary">
					                                  <input name="gender" value="female" type="radio" @if(old('gender') == 'female') checked @endif>
					                                  <label >Female</label>
					                                </div>
														</div>

														

													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="form-control-label">Country <span class="text-danger">*</span></label>
															<input type="text" value="{{old('country')}}" class="form-control" name="country">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="form-control-label">State <span class="text-danger">*</span></label>
															<input type="text" value="{{old('state')}}" class="form-control" name="state">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="form-control-label">City <span class="text-danger">*</span></label>
															<input type="text" value="{{old('city')}}" class="form-control" name="city"  >
														</div>
													</div>
												</div>
												<div class="row mb-3">
													
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Highest Qualification <span class="text-danger">*</span></label>
															<input type="text" value="{{old('hig_qualification')}}" class="form-control" name="hig_qualification">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Total Experience <span class="text-danger">*</span></label>
															<input type="number" value="{{old('total_exp')}}" class="form-control" name="total_exp"  >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Relevant Experience </label>
															<input type="number" value="{{old('relevant_exp')}}" class="form-control" name="relevant_exp"  >
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<!-- <div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Subjects you would like to teach <span class="text-danger">*</span></label>
															<input type="text" class="form-control" name="subject_teach"  >
														</div>
													</div> -->
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Subjects you would like to teach <span class="text-danger">*</span></label>
															<select class="js-example-basic-multiple form-control" name="subject_teach[]" multiple="multiple">

@foreach($courses as $c)
<option value="{{$c->title}}"  @if (old('subject_teach')) @if (in_array($c->title,old('subject_teach'))) selected @endif
                                    @endif>{{$c->title}}</option>
                                    @endforeach

															  

															</select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Grades you would like to teach <span class="text-danger">*</span></label>
															<input type="text" value="{{old('grade_teach')}}" class="form-control" name="grade_teach"  >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="form-control-label">Language Spoken</label>
															<input type="text" value="{{old('lang_proficiency')}}" class="form-control" name="lang_proficiency"  >
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<div class="col-md-12">
														<div class="form-group">
															<label class="form-control-label">Upload latest CV <span class="text-danger">*</span></label>
															<input type="file" class="form-control" name="upload_cv" accept=".pdf,.docx"  >
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<div class="col-md-6">
														<div class="form-group">
															<label class="form-control-label">Password <span class="text-danger">*</span></label>
															<div class="pass-group" id="passwordInput">
															<input type="password" class="form-control pass-input @error('password') @enderror" name="password" placeholder="Password" >
															<!-- <span class="pass-checked"><i class="feather-check"></i></span> -->
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="form-control-label">Confirm Password <span class="text-danger">*</span></label>
															<div class="pass-group" id="passwordInput">
															<input type="password" class="form-control pass-input @error('password')  @enderror" name="password_confirmation" placeholder="Confirm password" required>
															<!--<span class="toggle-password feather-eye"></span>-->
															<!-- <span class="pass-checked"><i class="feather-check"></i></span> -->
															</div>
														</div>
													</div>
												</div>
												<div class="row mb-3">
													<div class="col-md-6">
														<div class="form-check">
														  <input class="form-check-input" type="checkbox" value="" required id="flexCheckDefault">
														  <label class="form-check-label" for="flexCheckDefault">I agree to the <a href="/tutor-terms-and-conditions" target="_blank">terms and conditions</a>
														  </label>
														</div>
													</div>
													<!-- <div class="col-md-6">
														<div class="form-check">
														  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
														  <label class="form-check-label" for="flexCheckDefault">I agree to the <a href="#">terms and conditions</a>
														  </label>
														</div>
													</div> -->
												</div>

												<div class="mb15">
                        <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div> 
						
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Create account</button>
                      </div>
                     
                      
                      
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End section -->

				<section class="section" id="faqs">
   <div class="container">
      <div class="row gy-4 align-items-center">
      	<div class="col-lg-12 text-center">
      		 <h3 class="h1 mb-1">Frequently Asked Questions</h3>
         	<p class="m-0 lead">Certainly! Here are some frequently asked questions (FAQs) about the Tutor Platform @ VaaGa Academy::</p>
      	</div>
         <div class="col-lg-6">
         	<!-- <img src="/public/newassets/img/home/ai-2.svg" title="" alt=""> -->
         	 <div class="accordion" id="home_accordion_02">
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingOne"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseOne" aria-expanded="true" aria-controls="home_accordion_02collapseOne"><span class="col ps-3 text-dark fw-600">What is the Tutor platform?</span></button></h2>
                  <div id="home_accordion_02collapseOne" class="accordion-collapse collapse show" aria-labelledby="home_accordion_02headingOne" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">It’s a dedicated platform within VaaGa Academy that allows tutors to manage their profiles, schedule classes, communicate with students, and access resources and tools to enhance their teaching experience.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingTwo"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseTwo" aria-expanded="false" aria-controls="home_accordion_02collapseTwo"><span class="col ps-3 text-dark fw-600">How do I become a Tutor at VaaGa Academy?</span></button></h2>
                  <div id="home_accordion_02collapseTwo" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingTwo" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">To become a Tutor with VaaGa Academy, you can start by filling out our online application form. We have a rigorous selection process that includes evaluating qualifications, subject expertise, teaching experience and initial interview. Successful applicants will be invited to join our Tutor network.

</div>
                  </div>
               </div>
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingThree"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseThree" aria-expanded="false" aria-controls="home_accordion_02collapseThree"><span class="col ps-3 text-dark fw-600">How to access Tutor platform?</span></button></h2>
                  <div id="home_accordion_02collapseThree" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingThree" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Once you join VaaGa Academy as a Tutor, you can login to access the Tutor platform. You can visit our website and click on the " Login" button to access your dashboard.</div>
                  </div>
               </div>
                <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingFou"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseFou" aria-expanded="false" aria-controls="home_accordion_02collapseThree"><span class="col ps-3 text-dark fw-600">How do I update my profile information?</span></button></h2>
                  <div id="home_accordion_02collapseFou" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingFou" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Within the Tutor platform, you will have the option to update and manage your profile information. You can add or edit your educational qualifications, teaching experience, subjects you can teach, availability, and other relevant details. Keeping your profile up to date ensures that students get accurate information about your expertise.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading5"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse5" aria-expanded="false" aria-controls="home_accordion_02collapse5"><span class="col ps-3 text-dark fw-600">Can I manage my class schedule through Tutor platform?</span></button></h2>
                  <div id="home_accordion_02collapse5" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading5" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, the tutor platform allows you to manage your class schedule conveniently. You can set your availability, block off specific time slots and view assigned students. The portal provides a user-friendly calendar interface that enables you to efficiently manage your teaching schedule.</div>
                  </div>
               </div>
             

            </div>
         </div>
         <div class="col-lg-6">
            <div class="accordion" id="home_accordion_02">
               
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading6"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse6" aria-expanded="false" aria-controls="home_accordion_02collapse6"><span class="col ps-3 text-dark fw-600">How can I communicate with my students through platform?</span></button></h2>
                  <div id="home_accordion_02collapse6" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading6" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">The Tutor platform provides communication tools to interact with your students. You can exchange messages, share study materials, and provide feedback on assignments or assessments. Additionally, you can use video conferencing tools within the portal to conduct one-to-one sessions with your students.</div>
                  </div>
               </div>

               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading6x"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse6x" aria-expanded="false" aria-controls="home_accordion_02collapse6x"><span class="col ps-3 text-dark fw-600">What will be the duration of class?</span></button></h2>
                  <div id="home_accordion_02collapse6x" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading6x" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Every class will be of one hour duration.</div>
                  </div>
               </div>


               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading7"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse7" aria-expanded="false" aria-controls="home_accordion_02collapse7"><span class="col ps-3 text-dark fw-600">What resources and tools are available for Tutor?</span></button></h2>
                  <div id="home_accordion_02collapse7" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading7" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Within the Tutor platform, you will have access to a range of resources and tools to enhance your teaching experience. This may include whiteboard, lesson plans, interactive learning resources, assessment tools, feedback and more. We strive to provide Tutors with the necessary resources to deliver high-quality teaching experience.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading8"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse8" aria-expanded="false" aria-controls="home_accordion_02collapse8"><span class="col ps-3 text-dark fw-600">Can I seek support or assistance through Tutor platform?</span></button></h2>
                  <div id="home_accordion_02collapse8" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading8" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, through ‘Contact Us’ page on portal you can seek assistance from the VaaGa Academy support team. If you have any questions, technical issues, or need guidance, you can reach out to the support team directly through the portal.</div>
                  </div>
               </div>
               <!-- <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading9"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse9" aria-expanded="false" aria-controls="home_accordion_02collapse9"><span class="col ps-3 text-dark fw-600">Can I seek support or assistance through the tutor portal?</span></button></h2>
                  <div id="home_accordion_02collapse9" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading9" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, the tutor portal includes a support feature where you can seek assistance from the Vaaga Academy support team. If you have any questions, technical issues, or need guidance, you can reach out to the support team directly through the portal.</div>
                  </div>
               </div> -->

            </div>
         </div>
      </div>
   </div>
</section>

phoneInput
			</main>

@stop
@section('page_js')

<script type="text/javascript">
	wow = new WOW(
    {
      boxClass:     'wowx',      // default
      animateClass: 'animated', // default
      offset:       0,          // default
      mobile:       true,       
      live:         true        // default
    }
  )
wow.init();

</script>
<script>
    const phoneInput = document.getElementById('phoneInput');

    phoneInput.addEventListener('input', function () {
        const phoneNumber = phoneInput.value.replace(/\D/g, '');

        if (phoneNumber.length > 10) {
            phoneInput.value = phoneNumber.slice(0, 10);
        }
    });
</script>
@stop