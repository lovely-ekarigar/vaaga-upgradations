@extends('frontend.layout.sub-master')
@section('title')
<title>Analysis report for {{$test->title}} | {{env('APP_NAME')}}</title>
@stop
@section('content')
<style type="text/css">
     
    .badge-primary {
    color: #fff;
    background-color: #007bff;
}
.badge-secondary {
    color: #fff;
    background-color: #6c757d;
}
.badge-success {
    color: #fff;
    background-color: #28a745;
}
.badge-danger {
    color: #fff;
    background-color: #dc3545;
}
.badge-warning {
    color: #212529;
    background-color: #ffc107;
}
.badge-info {
    color: #fff;
    background-color: #17a2b8;
}

.badge-light {
    color: #212529;
    background-color: #f8f9fa;
}
.badge-dark {
    color: #fff;
    background-color: #343a40;
}
button.close {
    background: transparent;
    border: 0px;
}
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
label.options.correctanswer1 {
    /* background: red; */
    color: #4CAF50;
    font-weight: 700;
}
.grrncolor{
  color: #4CAF50;
    font-weight: 700;  
}
@media(max-width:576px){
    .question{
        width: 100%;
        word-spacing: 2px;
    } 
}
</style>

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2"></h3>
                @include('includes.partials.messages')
              <h1 class="text-body fw-500 mb-3" style="font-size:24px;">Analysis report for {{$test->title}}</h1>
              

                    @foreach($responses as $k=>$question_data)

                <div class="question ml-sm-5 pl-sm-5 pt-2">
                  <div class="py-2 h5" style="font-size:18px;"><b>{{$k+1}}. {{$question_data->question->question}} {{$question_data->correct}}</b>

                    <br>
                   
                  </div>
                  <div class="  pt-3" id="options">
                    @foreach($question_data->options as $qo )
                     <label class="options correctanswer{{$qo->correct}}">{{$qo->option_text}}
                          <input type="radio" name="option[{{$question_data->question_id}}]" readonly @if($qo->id==$question_data->response_option_id) checked @endif value="{{$qo->id}}">
                          <span class="checkmark"></span>
                      </label>
                      @if($qo->explanation)
                      {!!$qo->explanation!!}
                      @endif
                     @endforeach
                  </div>
                </div>
                @endforeach


                
                 
                   


            </div>
          <p>
                    *<span class="grrncolor">Green</span> Color indicates correct answer for the question.
                </p>
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop