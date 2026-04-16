@extends('frontend.layout.sub-master')
@section('title')
<title>Testimonial | {{env('APP_NAME')}}</title>
<meta name="description" content="Empower your education journey with live online classes on VaaGa Academy, India's top learning platform for school students.">
<meta name="keywords" content="Vaaga Academy,Vaaga Academy Gurgaon,online education platforms in India,Best elearning platforms in India,Online learning platform for students,Top online learning platforms in india,Online live learning platform for school students,Live Online Learning Classes for school students">



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
               <h1 class="text-white h1 ">Testimonials</h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Testimonials</li>
              </ol>
            </div>
         </div>
      </div>
   </section>
 

<section class="section">
			   <div class="container">
			      
			      <div class="row g-3">
	@foreach($testimonials as $te)
    <div class="col-sm-6 col-lg-4">
       <div class="border text-center mt-5 mb-4 rounded-3 h-100 d-flex flex-column">

          @if(!empty($te->video_link))
          <div class="pt-2 text-center">
             <a href="#">
                <iframe width="360px" height="200"
                        src="https://www.youtube.com/embed/{{ $te->video_link }}"
                        title="YouTube video player" allowfullscreen></iframe>
             </a>
          </div>
          @else
          <div class="pt-2 text-center">
              <img src="{{ asset('uploads/testimonials/'.$te->image) }}" alt="{{ $te->name }}"
                   class="rounded-circle shadow-sm" style="width:120px; height:120px; object-fit:cover;"
                   onerror="this.onerror=null;this.src='https://vaagaacademy.com/uploads/testimonials/1756449983-648881126325.png';">
          </div>
          @endif

          <div class="pt-3 pb-1">
             <h5 class="h6 m-0">{{$te->name}}</h5>
             <label class="fw-600 small m-0">{{$te->occupation}}</label>
          </div>

          <div class="p-4 pt-1 flex-grow-1">
             <p>{{$te->content}}</p>
          </div>
       </div>
    </div>
@endforeach
			         
			        
			      
			      </div>
			   </div>
			</section>
</main>

@stop


@section('page_js')

@stop