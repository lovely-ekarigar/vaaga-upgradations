@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Course Tracking'.' | '.app_name())

@section('content')


<style type="text/css">
    .accordion {
    background: linear-gradient(to bottom right, #FFF, #f7f7f7);
    background: #f0f3f5;
    margin: 0 auto;
    border-radius: 3px;
    box-shadow: 0 10px 15px -20px rgba(0, 0, 0, 0.3);
}
.heading {
    color: #000;
    font-size:14px;
    border-bottom: 1px solid #e7e7e7;
    letter-spacing: 0.8px;
    padding: 15px;
    cursor: pointer;  
}
.heading:nth-last-child(2){
    border-bottom:0; 
}
.heading:hover {
    background: #e4e7ea;
    border-radius: 0;
}
.heading:first-child:hover {
    border-radius: 3px 3px 0 0;
}
.heading:nth-last-child(2):hover{
    border-radius:0 0 3px 3px;
}
.heading::before {
    content: '';
    vertical-align: middle;
    display: inline-block;
    border-top: 7px solid #000;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    float: right;
    transform: rotate(0);
    transition: all 0.5s;
    margin-top: 5px;
}
.active.heading::before {
    transform: rotate(-180deg);
}
.not-active.heading::before {
    transform: rotate(0deg);
}
.contents {
    display: none;
    background: #fff;
    padding: 15px;
    color: #7f8fa4;
    font-size: 13px;
    line-height: 1.5;
}

</style>

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Course Tracking</h3>
          
        </div>
        <div class="card-body">
            <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h1 class="h1">Math</h1>
                    </div>
                    <div>
                        <form action="" method="POST">
                            @csrf
                        
                        <div class="accordion mt-5 mb-5">
                             @foreach($list as $lesson)
                            <div class="heading">{{$lesson->title}}</div>
                            <div class="contents">
                                @foreach($lesson->lesson_lists as $ll)
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="{{$ll->title}}" id="flexCheckDefault">
                                  <label class="form-check-label" for="flexCheckDefault">
                                    {{$ll->title}}
                                  </label>
                                  <input type="hidden" value="{{$ll->id}}" name="lesson_id">
                                </div>
                                @endforeach
                            </div>

                         @endforeach
                        </div>
                        
                        <div class="" style="text-align: end;">
                            <button class="btn btn-info" type="submit">Submit</button>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
                
            </div>
        </div>
    </div>
@stop

@push('after-scripts')
<script type="text/javascript">
        $(document).ready(function(){
       $(".accordion").on("click", ".heading", function() {

       $(this).toggleClass("active").next().slideToggle();

       $(".contents").not($(this).next()).slideUp(300);
                    
       $(this).siblings().removeClass("active");
       });
      });
           
</script>

@endpush