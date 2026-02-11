@extends('frontend.layout.sub-master')
@section('title')
<title>Lesson| {{env('APP_NAME')}}</title>
@stop
@section('content')
@include("frontend.include.user-menu")
</div>

<style type="text/css">
    a.dropdown-itemx {
    display: inline;
}
a.dropdown-itemx i {
    font-size: 20px;
}
</style>
<?php 

$icons = array(

"image/png"=>"ti-image",
"lesson_pdf"=>"bi-file-pdf",
"upload"=>"ti-video-camera",
"lesson_audio"=>"bi-optical-audio-fill",
"youtube"=>"bi-youtube",

);

?>
<div class="col-lg-8 col-xl-9">
   <div class="profile-content-area my-6 card card-body">
      <div class="border-bottom mb-6 pb-6">
         <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
         <h6 class="text-body fw-500 mb-3">Course Content for {{$course->title}} </h6>
         @if(count($clist)  > 0)
         <div class="accordion shadow" id="accordionExample_03">
            @php $count = 0; @endphp
            @foreach($clist as $ct)
            @php $count++ @endphp
            <div class="accordion-item">
               <p class="m-0 accordion-header" id="heading_03_{{$count}}">
                  <button class="py-3 accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_03_{{$count}}" aria-expanded="true" aria-controls="collapse_03_1">{{$ct->title}}</button>
               </p>
               <div id="collapse_03_{{$count}}" class="accordion-collapse collapse " aria-labelledby="heading_03_{{$count}}" data-bs-parent="#accordionExample_03">
                  <div class="accordion-body">
                     <ul style="list-style: none;">
                        @foreach($ct->lessons as $cl)
                        <li class="mb-3">
                           <a href="#" class="d-flex align-items-center justify-content-between">
                           <span>
                           <img src="/frontend/assets/img/icon/play.svg" alt="" class="me-2">
                           {{$cl->title}}
                           </span>
                            @if(count($cl->media)>0)
                              @foreach($cl->media as $cm)
                                                                       
                                                                                    <a class="dropdown-itemx" href="/user/download/{{$course->id}}/{{$cm->id}}" download data-id="{{$cm->id}}" data-file="{{$cm->file_name}}">
                                                                                      <i class="{{$icons[$cm->type]}} me-2"></i>
                                                                                    </a>
                                                                                  
                                                                                    @endforeach


                            @endif
                         <!--   <span>
                           <?php $dur=explode(":", $cl->duration);
                             
                              
                               ?>
                           </span> -->
                           </a>
                        </li>
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         @endif
      </div>
   </div>
</div>
</div>
</div>
</section>
<!-- End Section -->
</main>
@stop