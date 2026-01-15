@extends('frontend.layout.sub-master')
@section('title')
<title>Register | {{env('APP_NAME')}} </title>
@stop
@section('page_css')
<style type="text/css">
	@media (min-width: 1200px)

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
	.slider-min-size {
    min-height: 50vh !important
}
.slider-min-size-h2{
	font-size: 20px !important;
}
.slider-min-size p{
	font-size: 16px !important;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
	.slider-min-size {
    min-height: 50vh !important
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
	.slider-min-size {
    min-height: 75vh !important
}
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
	.slider-min-size {
    min-height: 100vh !important
}
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
	.slider-min-size {
    min-height: 100vh !important
}
}
</style>
@stop
@section('content')

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-cover bg-no-repeat" style="background-image: url(/public/newassets/img/bg/bg-88.jpg);background-position: center center;">
   	 <!-- <div class="mask bg-0000_ opacity-8"></div> -->
		   <div class="container">
		      <div class="row align-items-center justify-content-center py-5 pt-lg-10 slider-min-size align-items-center">
		         <div class="col-lg-6 col-md-6 pe-xl-11 py-4">
		            <h2 class="display-2 fw-700 text-white slider-min-size-h2 text-center text-md-start">Welcome to <br>{{env('PROJECT_NAME')}}</h2>
		            <p class="lead fw-600 text-white text-opacity-85 text-center text-md-start">Join Live and Interactive Online Classes with the best Tutors</p>
		            <div class="pt-2 text-center text-md-start">
						<!-- <a class="btn btn-white me-2" href="#cus-enroll-student">Enroll Now</a>  -->
					<a class="btn btn-outline-white " href="/contact">Contact Us</a></div>
		         </div>
		         <div class="col-md-6 col-lg-6 ">
                <div class="card">

                  <div class="card-body">
                    <div class="pb-4 text-center">
                      <h3 class="mb-2">Create your account</h3>
                     
                    </div>
                    <form action="" method="post">
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

                    		<div class="row">
									<div class="col-md-6 mb-2">
										<div class="form-group">
											<label class="form-control-label">First Name<span class="text-danger">*</span></label>
											<input type="text" class="form-control " name="first_name" value="{{old('first_name')}}" placeholder="First Name" >
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="form-group">
											<label class="form-control-label">Last Name<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="Last Name" >
										</div>
									</div>
									
									<div class="col-md-6 mb-2">
										<label for="dec" class="control-label">Gender <span class="text-danger">*</span></label>
											<div class="col-md-10">
												<label class="radio-inline mr-3 mb-0">
													<input type="radio" name="gender" value="male" > Male
													</label>
													<label class="radio-inline mr-3 mb-0">
													<input type="radio" name="gender" value="female" > Female
												</label>
                        		            </div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="form-group">
											<label class="form-control-label">Phone<span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder=" Phone " oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');" name="phone" maxlength="10" pattern="\d{10}" value=""/>
											
										</div>
									</div>
									<div class="col-md-12 mb-2">
										<div class="form-group">
											<label class="form-control-label">Email<span class="text-danger">*</span></label>
											<input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email" >
										</div>
									</div>
									
									<div class="col-md-6 mb-2">
										<div class="form-group">
											<label class="form-control-label">Password<span class="text-danger">*</span></label>
											<div class="pass-group" id="passwordInput">
											<input type="password" class="form-control pass-input @error('password') @enderror" name="password" placeholder="Password" >
											<!--<span class="toggle-password feather-eye"></span>-->
											<span class="pass-checked"><i class="feather-check"></i></span>
											</div>
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="form-group">
											<label class="form-control-label">Confirm Password<span class="text-danger">*</span></label>
											<div class="pass-group" id="passwordInput">
											<input type="password" class="form-control pass-input @error('password') @enderror" name="password_confirmation" placeholder="Confirm password" required>
											<!--<span class="toggle-password feather-eye"></span>-->
											<span class="pass-checked"><i class="feather-check"></i></span>
											</div>
										</div>
									</div>
									<div class="row mb-3 mt-3 p-0 m-0">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="" required id="flexCheckDefault">
											  <label class="form-check-label" for="flexCheckDefault">I agree to the <a href="/student-terms-and-conditions" target="_blank">terms and conditions</a>
											  </label>
											</div>
										</div>
										
									</div>
								</div>

								<div class="mb15">
									<div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
									</div> 
                      
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Create account</button>
                      </div>
                     
                      
                      <div class="mt-3 text-center">
                        <small>Already have an acocunt?</small>
                        <a href="/userlogin?redirect={{$red}}" class="small fw-700">Login</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
		      </div>
		   </div>
		</section>

		<section class="section">
		  <div class="container">
		    <div class="row justify-content-between align-items-center">
		      <div class="col-lg-5">
		        <h6 class="bg-primary bg-opacity-10 px-3 py-1 rounded-pill text-primary d-inline-flex fs-sm">What we do</h6>
		        <!-- <h3 class="h1 mb-3">We need to stop <mark>interrupting</mark> what people are interested </h3> -->
		        <p class="m-0 lead">VaaGa Academy is here to help students with the personalised and effective academic plan for the session to achieve their goal by constantly monitoring students’ progress and guiding them through in the right direction. Personal attention on the student to solve all the subject related queries and boost their confidence.</p>
		        <div class="pt-3 pb-4">
		       
		           <ul class="list-type-03 mb-4 list-unstyled">
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Personalised and interactive Live Private and Group classes </li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Experienced and Well qualified tutors</li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>	Tutor replacement guarantee</li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i> Best education at comfort and safety of your home</li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>	Regular test to track the progress</li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>	Regular progress reporting to parents</li>
	               <li class="d-flex py-1"><i class="bi bi-check-circle-fill text-secondary me-2"></i>Focus on conceptual learning</li>
            </ul>
		        </div>
		       
		      </div>
		      <div class="col-lg-6">
		        <img src="/public/newassets/img/home/ai-2.svg" title="" alt="">
		      </div>
		    </div>
		  </div>
		</section>

		<section class="section">
			   <div class="container">
			      <div class="row section-heading justify-content-center">
			         <div class="col-lg-8 col-xl-6 text-center">
			            <h3 class="h1 bg-primary-after after-50px pb-3 mb-3">Why To Choose {{env('PROJECT_NAME')}}?</h3>
			         </div>
			      </div>
			      <div class="row g-3">
			         <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600"> Personalised Live Private online class </h6>
			               <p>Engaging, Interactive and customized learning experience</p>
			            </div>
			         </div>
			          <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Group Class </h6>
			               <p>Engaging and interactive group classes, encouraging group discussion </p>
			            </div>
			         </div>
			        <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Interactive Learning Tools</h6>
			               <p>Various tools and resources to enhance your learning experience</p>
			            </div>
			         </div> 
			         <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Regular progress update</h6>
			               <p>Regular progress report and constructive feedback to achieve academic success</p>
			            </div>
			         </div>
			         
			         <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Anytime and Anywhere</h6>
			               <p>Freedom to learn from anywhere with guidance from expert Tutors from all over the country</p>
			            </div>
			         </div>
			         <div class="col-sm-6 col-lg-3">
			            <div class="card card-body hover-top text-center h-100">
			               <div class="icon-lg bg-primary bg-opacity-10 mb-3 text-primary mx-auto">
			               	<i class="bi-people-fill"></i>
			               </div>
			               <h6 class="mb-2 fw-600">Flexible Payment cycles</h6>
			               <p>Convenient monthly subscription model available for fees payment</p>
			            </div>
			         </div>
			       
			      </div>
			   </div>
			</section>

        <!-- Section -->
        {{-- <section class="section bg-gray-100" id="cus-enroll-student">
          <div class="container">
          	<div class="row section-heading justify-content-center">
			         <div class="col-lg-8 col-xl-6 text-center">
			            <h3 class="h1 bg-primary-after after-50px text-warning">Register with us</h3>
			         </div>
			      </div>
            <div class="row align-items-center justify-content-center">
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                  <div class="card-body">
                    <div class="pb-4 text-center">
                      <h3 class="mb-2">Create your account</h3>
                     
                    </div>
                    <form action="" method="post">
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

                    		<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">First Name<span class="text-danger">*</span></label>
											<input type="text" class="form-control " name="first_name" value="{{old('first_name')}}" placeholder="First Name" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Middle Name</label>
											<input type="text" class="form-control" name="middle_name" value="{{old('middle_name')}}" placeholder="Middle Name" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Last Name<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="Last Name" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Date Of Birth<span class="text-danger">*</span></label>
											<input type="date" class="form-control" name="dob" value="{{old('dob')}}"  >
										</div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-6">
										<label for="dec" class="control-label">Gender <span class="text-danger">*</span></label>
											<div class="col-md-10">
												<label class="radio-inline mr-3 mb-0">
													<input type="radio" name="gender" value="male" > Male
													</label>
													<label class="radio-inline mr-3 mb-0">
													<input type="radio" name="gender" value="female" > Female
												</label>
                        		            </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Email<span class="text-danger">*</span></label>
											<input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Phone<span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder=" Phone " oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');" name="phone" maxlength="10" pattern="\d{10}" value=""/>

										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Address<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="address" value="{{old('address')}}" placeholder="Address">
										</div>
								</div>
								<div class="row p-0 m-0">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Country<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="country" value="{{old('country')}}" placeholder="country">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">State<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="state" value="{{old('state')}}" placeholder="State">
										</div>
									</div>
								</div>

								<div class="row p-0 m-0">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">City<span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="city" value="{{old('city')}}" placeholder="City">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Pin code<span class="text-danger">*</span></label>
											<input type="number" class="form-control" name="pincode" value="{{old('pincode')}}" placeholder="Pincode">
										</div>
									</div>
									
								</div>

								<div class="row mb-3 p-0 m-0">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Password<span class="text-danger">*</span></label>
											<div class="pass-group" id="passwordInput">
											<input type="password" class="form-control pass-input @error('password') @enderror" name="password" placeholder="Password" >
											<!--<span class="toggle-password feather-eye"></span>-->
											<span class="pass-checked"><i class="feather-check"></i></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label">Confirm Password<span class="text-danger">*</span></label>
											<div class="pass-group" id="passwordInput">
											<input type="password" class="form-control pass-input @error('password') @enderror" name="password_confirmation" placeholder="Confirm password" required>
											<!--<span class="toggle-password feather-eye"></span>-->
											<span class="pass-checked"><i class="feather-check"></i></span>
											</div>
										</div>
									</div>
									<div class="row mb-3 mt-3 p-0 m-0">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="" required id="flexCheckDefault">
											  <label class="form-check-label" for="flexCheckDefault">I agree to the <a href="/student-terms-and-conditions" target="_blank">terms and conditions</a>
											  </label>
											</div>
										</div>
										
									</div>
								</div>

								<div class="mb15">
									<div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
									</div> 
                      
                      <div class="py-2">
                        <button class="btn btn-primary w-100" type="submit">Create account</button>
                      </div>
                     
                      
                      <div class="mt-3 text-center">
                        <small>Already have an acocunt?</small>
                        <a href="/userlogin?redirect={{$red}}" class="small fw-700">Login</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section> --}}
        <!-- End section -->

       <section class="section bg-gray-100" id="faqs">
   <div class="container">
      <div class="row gy-4 align-items-center">
      	<div class="col-lg-12 text-center">
      		 <h3 class="h1 mb-1 text-white">Frequently Asked Questions</h3>
         	<p class="m-0 lead text-white">Certainly! Here are some frequently asked questions (FAQs) about the Student Platform @VaaGa Academy:</p>
      	</div>
         <div class="col-lg-6">
         	<!-- <img src="/public/newassets/img/home/ai-2.svg" title="" alt=""> -->
         	 <div class="accordion" id="home_accordion_02">
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingOne"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseOne" aria-expanded="true" aria-controls="home_accordion_02collapseOne"><span class="col ps-3 text-dark fw-600">How online classes @VaaGa Academy will help us?</span></button></h2>
                  <div id="home_accordion_02collapseOne" class="accordion-collapse collapse show" aria-labelledby="home_accordion_02headingOne" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Our personalised approach helps the student to have a better understanding about the subject and having online class will reduce the travel time which can be used in a constructive way. One-on-One interaction will always give better result than group tuitions.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingTwo"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseTwo" aria-expanded="false" aria-controls="home_accordion_02collapseTwo"><span class="col ps-3 text-dark fw-600">Can I take a demo class before registration?</span></button></h2>
                  <div id="home_accordion_02collapseTwo" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingTwo" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">No, you have to complete the registration process before taking the demo class. The free demo class will help you to have a better understanding about the way in which the future classes will be conducted.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-3 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingThree"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseThree" aria-expanded="false" aria-controls="home_accordion_02collapseThree"><span class="col ps-3 text-dark fw-600">Will I have to pay in advance?</span></button></h2>
                  <div id="home_accordion_02collapseThree" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingThree" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">The tuition fees have to be paid in advance. You can opt for monthly subscription or full course plan.</div>
                  </div>
               </div>

                <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading5"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse5" aria-expanded="false" aria-controls="home_accordion_02collapse5"><span class="col ps-3 text-dark fw-600">What if a Tutor discontinue class in between?</span></button></h2>
                  <div id="home_accordion_02collapse5" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading5" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">In this case we provide 100% Tutor replacement guarantee. You will be assigned a new expert Tutor immediately for uninterrupted learning.   </div>
                  </div>
               </div>


                <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02headingFou"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapseFou" aria-expanded="false" aria-controls="home_accordion_02collapseThree"><span class="col ps-3 text-dark fw-600">Can I change the Tutor if I am not satisfied with the teaching method?</span></button></h2>
                  <div id="home_accordion_02collapseFou" class="accordion-collapse collapse" aria-labelledby="home_accordion_02headingFou" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, we provide 100% Tutor replacement guarantee. You can send a request mail for changing the Tutor and we will assign a new expert Tutor.   </div>
                  </div>
               </div>
              
             

            </div>
         </div>
         <div class="col-lg-6">
            <div class="accordion" id="home_accordion_02">
               
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading6"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse6" aria-expanded="false" aria-controls="home_accordion_02collapse6"><span class="col ps-3 text-dark fw-600">Will I get study material, assignment and class notes for later reference?</span></button></h2>
                  <div id="home_accordion_02collapse6" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading6" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, Tutor will upload the notes and assignments during the class which will be available on the student dashboard.    </div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading7"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse7" aria-expanded="false" aria-controls="home_accordion_02collapse7"><span class="col ps-3 text-dark fw-600">Will I be able to see the class recordings?</span></button></h2>
                  <div id="home_accordion_02collapse7" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading7" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, all the video recordings will be available to the student after the class. You can watch the recording again in case you have of any doubt or for revision.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading8"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse8" aria-expanded="false" aria-controls="home_accordion_02collapse8"><span class="col ps-3 text-dark fw-600">Will I get assessments/ exams?</span></button></h2>
                  <div id="home_accordion_02collapse8" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading8" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, regular assessments will be conducted after completion of every topic.  </div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading9"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse9" aria-expanded="false" aria-controls="home_accordion_02collapse9"><span class="col ps-3 text-dark fw-600">What are the benefits of online tuitions?</span></button></h2>
                  <div id="home_accordion_02collapse9" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading9" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Personalised approach and individual attention on the student will help in good understanding of subject concepts, boost confidence and give good results.</div>
                  </div>
               </div>
               <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading10"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse10" aria-expanded="false" aria-controls="home_accordion_02collapse10"><span class="col ps-3 text-dark fw-600">How online tuition will help in scoring good marks?</span></button></h2>
                  <div id="home_accordion_02collapse10" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading10" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Presentation and class video recordings will be available for later reference and can be viewed again by the student in case on any doubt. Regular tests will be conducted to track the performance of the student.    </div>
                  </div>
               </div>


                <div class="accordion-item rounded-4 border border-gray-200 mb-3">
                  <h2 class="accordion-header" id="home_accordion_02heading10x"><button class="accordion-button bg-transparent d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#home_accordion_02collapse10x" aria-expanded="false" aria-controls="home_accordion_02collapse10x"><span class="col ps-3 text-dark fw-600">Can I seek support or assistance through Student platform?</span></button></h2>
                  <div id="home_accordion_02collapse10x" class="accordion-collapse collapse" aria-labelledby="home_accordion_02heading10x" data-bs-parent="#home_accordion_02">
                     <div class="accordion-body text-body border-top">Yes, through ‘Contact Us’ page on portal you can seek assistance from the VaaGa Academy support team. If you have any questions, technical issues, or need guidance, you can reach out to the support team directly through the portal.   </div>
                  </div>
               </div>



            </div>
         </div>
      </div>
   </div>
</section>

      </main>

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
