@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.tests.title').' | '.app_name())

@section('content')
<style type="text/css">
    .demo_box {
    background: #ffeb3b24;
    padding: 0px 8px;
    border-radius: 8px;
}
.question{
    width: 100%;
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

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Analysis report for {{$test->title}} </h3>
            <div class="float-right">
                <a href="{{route('admin.tests.result',['id'=>$test->id])}}" class="btn btn-sm btn-primary">Back to results</a>
            </div>

        </div>
          <div class="card-body">
            <p>
                Student Name: <strong>{{$user->name}}</strong>
            </p>
<p>
                    *<span class="grrncolor">Green</span> Color indicates correct answer for the question.
                </p>

              @foreach($responses as $k=>$question_data)

                <div class="question  pt-2">
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
    </div>


     <!-- Start Model -->

     <!-- End Model -->
@stop

@push('after-scripts')
    <script>

      

    </script>

@endpush