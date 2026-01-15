@extends('frontend.layout.sub-master')
@section('title')
<title>Available Tests for {{$course->title}} | {{env('APP_NAME')}}</title>
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
</style>

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2"></h3>
                @include('includes.partials.messages')
              <h6 class="text-body fw-500 mb-3">Available Quiz for {{$course->title}}</h6>
                <div class="row">
                    @if(count($test_list)>0)
                    @foreach($test_list as $test)
                    <div class="col-md-12 card card-body mb-3">
                        
                        <h4 style="font-size: 20px;
    color: #0000008a;">{{$test->test->name}}</h4>
                        <div style="    display: flex
;
    justify-content: start;
    gap: 36px;">
                            
                            <div>
                                 Duration: {{ $test->test->duration }} Mins.
                            </div>
                            <div>
                                Questions: {{ $test->test->total_questions }}
                            </div>
                           </div>
                        
                      
                       
                        <p style="text-align: right;">
                        <a href="https://exam.vaagaacademy.com/autologin/{{$batch->id}}/{{Auth::user()->id}}" target="_blank" style="width:100px;" class="btn btn-sm btn-primary">Start Quiz</a>
                    </p>


                  

                    </div>

                    @endforeach
                    @else

                     <div class="col-md-12 card card-body mb-3">
                        <center><h4>No available Quiz</h4></center>
                     </div>
                    @endif
                   


                </div>
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop