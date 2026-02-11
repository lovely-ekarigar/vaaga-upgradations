@extends('frontend.layout.sub-master')
@section('title')
<title>Course Progress | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <div class="row">
                <div class="col-md-8">
                  <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
                </div>
                <div class="col-md-4">
                  <a href="{{route('feedback',['id'=>$batch_list->id])}}" class="btn btn-outline-info">Feedback</a>
                </div>
              </div>
              
              <div class="container pt-5">
               <div class="row ">
                  <div class="col-lg-12">
                     <ul class="list-unstyled">
                       @foreach($list as $lesson)
                        <li class="pb-3">
                          <span class="d-block text-mode fw-600">{{$lesson->title}}</span> 

                          @foreach($lesson->lesson_lists as $ll)
                          @foreach($lession_complete_list as $lession_complete)
                            @if($ll->id == $lession_complete->lession_id)

                              @if($lession_complete->status == 'completed')
                                <p class="px-5">{{$ll->title}} <span class="badge text-bg-success mx-5">Completed</span></p>
                              @elseif($lession_complete->status == 'ongoing')
                                <p class="px-5">{{$ll->title}} <span class="badge text-bg-info mx-5">Ongoing</span></p>
                              @else
                                <p class="px-5">{{$ll->title}} <span class="badge text-bg-secondary mx-5">Not Started</span></p>
                              @endif
                            
                            @endif
                            
                            
                          @endforeach

                          @endforeach

                        </li>
                         @endforeach
                     </ul>
                  </div>
               </div>
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