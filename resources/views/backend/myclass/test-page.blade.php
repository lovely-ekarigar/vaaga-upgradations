
@extends('frontend.layout.sub-master')
@section('title')
<title>Student Test| {{env('APP_NAME')}}</title>
@stop
@section('content')
<style> 
    .demo_box {
    background: #ffeb3b24;
    padding: 0px 8px;
    border-radius: 8px;
}
.question{
    width: 75%;
}
.options{
    position: relative;
    padding-left: 40px;
}
#options label{
    display: block;
    margin-bottom: 15px;
    font-size: 14px;
    cursor: pointer;
}
.options input{
    opacity: 0;
}
.checkmark {
    position: absolute;
    top: -1px;
    left: 0;
    height: 25px;
    width: 25px;
    border: 1px solid #ddd;
    border-radius: 50%;
}
.options input:checked ~ .checkmark:after {
    display: block;
}
.options .checkmark:after{
    content: "";
  width: 10px;
    height: 10px;
    display: block;
  background: white;
    position: absolute;
    top: 50%;
  left: 50%;
    border-radius: 50%;
    transform: translate(-50%,-50%) scale(0);
    transition: 300ms ease-in-out 0s;
}
.options input[type="radio"]:checked ~ .checkmark{
    background: #21bf73;
    transition: 300ms ease-in-out 0s;
}
.options input[type="radio"]:checked ~ .checkmark:after{
    transform: translate(-50%,-50%) scale(1);
}

@media(max-width:576px){
    .question{
        width: 100%;
        word-spacing: 2px;
    } 
}
</style>

         <main>
  <!-- <div class="min-h-350px bg-no-repeat bg-cover" style="background-image: url({{asset('newassets/img/bg/bg-222.png')}});"></div> -->
  <!-- <div class="mask bg-0000_ opacity-8"></div> -->
  <!-- Section -->

  <section class="section bg-gray-100 border-bottom">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-lg-9 col-xl-8 text-center pb-10 wow fadeInUp pt-8" data-wow-duration="0.5s" style="visibility: visible; animation-duration: 0.5s; animation-name: fadeInUp;">
                        <h3 class="h1 mb-3 text-warning"> {{$test_list->title}}</h3>
                        <!-- <div class="nav justify-content-center"><span class="mb-2 pe-3">Lessons: <span class="text-dark">0</span></span> <span class="mb-2 pe-3">Student enrolled: <span class="text-dark">1</span></span> <span class="mb-2 pe-3">Timing: <span class="text-dark">
                                                    </span>
                        </span> <span class="mb-2 text-primary">course</span></div> -->
                     </div>
                  </div>
               </div>
            </section>

  <section class="section pt-0" >
    <div class="container mt-n12">
      <div class="row align-items-start">
      
        

        <div class="col-lg-12 col-xl-12">
          <div class="profile-content-area my-6 card card-body">
              <form method="post" action="/user/submit-test">
                @csrf
                <input type="hidden" name="test_id" value="{{$test_list->id}}">
            <div class="border-bottom mb-3 pb-6">
         
@foreach($ret_list as $k=>$question_data)

                <div class="question ml-sm-5 pl-sm-5 pt-2">
                  <div class="py-2 h5"><b>{{$k+1}}. {{$question_data->question_list->question}} ?</b></div>
                  <div class="  pt-3" id="options">
                    @foreach($question_data->question_list->options as $qo )
                     <label class="options">{{$qo->option_text}}
                          <input type="radio" name="option[{{$question_data->question_id}}]" value="{{$qo->id}}">
                          <span class="checkmark"></span>
                      </label>
                     @endforeach
                  </div>
                </div>
                @endforeach   
               
            </div>

            <div class="container">
                <div class=" text-end">
                    <button class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
           
         
              
              
        
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop