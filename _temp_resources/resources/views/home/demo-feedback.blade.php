@extends('frontend.layout.sub-master')
@section('title')
<title>Demo Feedback | {{env('APP_NAME')}}</title>
@endsection
@section('content')
<style type="text/css">
   .scale-rating{
   margin: 12px 0 0px;
   display: inline-block;
   width: 100%;
   }
   .scale-rating>.label {
   position:relative;
   -webkit-appearance: none;
   outline:0 !important;
   border: 1px solid #cbd5e0;
       border-radius: 6px;
   height:33px;
   margin: 0 5px 0 0;
   width: calc(10% - 7px);
   float: left;
   cursor:pointer;
   }
   .scale-rating .label {
   position:relative;
   -webkit-appearance: none;
   outline:0 !important;
   height:33px;
   margin: 0 5px 0 0;
   width: calc(10% - 7px);
   float: left;
   cursor:pointer;
   }
   .scale-rating input[type=radio] {
   position:absolute;
   -webkit-appearance: none;
   opacity:0;
   outline:0 !important;
   /*border-right: 1px solid grey;*/
   height:33px;
   margin: 0 5px 0 0;
   width: 100%;
   float: left;
   cursor:pointer;
   z-index:3;
   }
   .scale-rating .label:hover{
   background:#4A2A51;
   color:#fff;
   }
   .scale-rating input[type=radio]:last-child{
   border-right:0;
   }
   .scale-rating .label input[type=radio]:checked ~ .label{
   -webkit-appearance: none;
   margin: 0;
   background:#4A2A51;
   }
   .scale-rating .label:before
   {
   content:attr(value);
   top: 2px;
   width: 100%;
   position: absolute;
   left: 0;
   right: 0;
   text-align: center;
   vertical-align: middle;
   z-index:2;
   color: #ffbe3d;
   }
</style>
<!-- Section -->
<section class="bg-cover bg-no-repeat bg-center effect-section" >
    <div class="mask bg-0000_ opacity-8"></div>
   <div class="container">
   <div class="row align-items-center justify-content-center min-vh-100">
      <div class="col-md-6 col-lg-5 ">
         <div class="card my-11">
            <div class="card-body p-5 ">
               <div class="text-center">
                  <h5>FeedBack</h5>
               </div>
               <form class="" method="post" action="/user/demo-feedback/{{$demo_id}}">
                  @csrf
                  <div class="mb-3">
                     <label class="form-label rd-input-label" for="contact-name">Your Name</label> 
                     <input id="contact-name" type="text" readonly value="{{auth()->user()->first_name}} {{auth()->user()->last_name}}" name="name" class="form-control" required>
                  </div>
                  @php
                  $sl = 1;
                  @endphp
                  @foreach($question_list as $questions)
                  <div class="mb-5">

                        <label>{{$sl++}}. {{$questions->question}}</label>
                          <!-- <input type="hidden" name="question_id[]" value="{{$questions->id}}"> -->
                        <span class="scale-rating">
                            @for($x = 1; $x <= 10; $x++)
                          <label class="label" value="{{$x}}">
                              <input type="radio" value="{{$x}}" name="rating[{{$questions->id}}]" required>
                              <label class="label" style="width:100%;"></label>
                          </label>
                          @endfor
                         
                        </span>
                        <div style="color:grey">
                            <span style="float:left; font-size: 14px;">
                             Not at all likely
                            </span>
                            <span style="float:right;font-size: 14px;">
                              Extermely likely
                            </span>
                            
                          </div>
 
                  </div>
                 @endforeach
                  <div class="mb-5">
                     <label class="form-label rd-input-label" for="contact-name">Message</label> 
                     <textarea class="form-control" id="contact-message" name="message" rows="3" required></textarea>
                  </div>
                  <div>
                     <button class="btn btn-primary w-100" type="submit" >Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- End section -->
@stop
@section('page_js')
@if(Session::has("flash_message"))
<script>
   $(document).ready(function(){
     Swal.fire({
    position: 'top-center',
    icon: 'success',
    title: '{!! Session::get("flash_message") !!}',
    showConfirmButton: false,
    timer: 5500
     });

     window.location.href = "/user/dashboard";
   
   });
</script>
@endif
@stop