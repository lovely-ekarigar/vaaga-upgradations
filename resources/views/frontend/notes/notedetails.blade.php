<?php
use App\Models\NoteCategory;
use App\Models\Note;

  $notesx = Note::where("category_id",$note->category->id)->where("id","!=",$note->id)->orderBy("id","desc")->limit(5)->get();
//   dd($notesx);
?>
@extends('frontend.layout.sub-master')
@section('title')
<title> {{$note->meta_title}} | {{env('APP_NAME')}}</title>
<meta name="description" content="{{$note->meta_description}}">
<meta name="keywords" content="{{$note->meta_keyword}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@stop
@section('page_css')
<style>
   .avatar-sm {
   height: 2.6875rem;
   width: auto; 
   }
   iframe >footer{
   display:none !important;
   }
</style>
@stop
@section('content')
  <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1 ">
                 {{$note->name}}
               </h1>
              <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item ">
                    <a href="#">Study Material
                    </a>
                    
                    </li>
                <li class="breadcrumb-item ">
                    <a href="#">
                        @if($note->category)
                     {{$note->category->name}}
                    @endif
                    </a>
                    
                    </li>
               
              </ol>
            </div>
         </div>
      </div>
   </section>
   
<section id="blog-item" class="py-6" style="background-color:#f9f9f9 !important">
   <div class="container" style="padding-top: 60px;">
      <div class="row">
         <div class="col-md-9">
            <div class="card shadow-lg">
               <div class="card-body">
                  <figure class="figure lightbox-gallery"> 
                     <img alt="" src="/{{$note->image}}" class="img-fluid shadow rounded">
                  </figure>
                  <h2>{{$note->name}}</h2>
                  <div class="row d-flex w-100 justify-content-between">
                      
                  </div>
                  @include('includes.editorjs-parser', ['content' => $note->description])
                  <br>
              
                  <br>
     
        
                  <div class="text-center">
                       <a href="{{route('frontend.note.download',['id'=>$note->id])}}" class="btn btn-primary flex-shrink-0" > <i class="bi bi-download"></i> Download </a>
                  </div>
                 
             
                  
               </div>
            </div>
            <br><br>
         </div>
         
           
                  <div class="col-md-3">
                    
                    @if($course->count() > 0 )
              

             
                  <div class="card hover-scale overflow-hidden hover-top ">
                    <div class="position-relative hover-scale-in text-center py-3" style="padding-top: 0px !important;">
                      <a href="{{route('courses.show',['slug'=>$course->slug])}}">
                        <img class="card-img-top" src="@if($course->course_image) {{asset('storage/uploads/'.$course->course_image)}} @else /newassets/img/logo.png @endif" onerror='this.src="/newassets/img/logo.png"' style="width: 100%;height: 110px;" title="{{$course->title}}" alt="{{$course->title}}">
                      </a>
                     
                    </div>
        
                    <div class="card-body card-bodyx text-center pt-2">
                      <h3 class="h3">
                        <a class="text-reset stretched-link" href="{{route('courses.show',['slug'=>$course->slug])}}">{{$course->title}}</a>
                      </h3>
                       <a class="btn btn-sm btn-warning" href="{{route('courses.show',['slug'=>$course->slug])}}">Browse Course</a>
                    </div> 
                  
                  </div> 
            
             
             
              @else

             
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="card-body text-center">
                      <div class="py-4">
                        <img src="{{asset('newassets/img/no-data-found.png')}}">
                      </div>
                        <h4 class="h4 pb-4 ">No Course Found</h2>
                    </div>
                  </div>
               

              @endif
              

                            
                            
                             <div class="card mt-2">
                                <div class="card-header bg-transparent p-3">
                                    <span class="h5 m-0">Notes</span>
                                </div>
                                <div class="list-group list-group-flush">
                                  @foreach($notesx as $note)
                                    <a href="{{route('frontend.note.noteDeatails',['slug'=> $note->slug])}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
                                        <div>
                                            <span>{{$note->name}}</span>
                                        </div>
                                        <div>
                                            <i class="bi bi-chevron-right"></i>
                                        </div>
                                    </a>
                                    @endforeach
                                    
                                   
                                </div>
                            </div>
                            
                            
                            
                    </div>
                    
      </div>
   </div>
</section>
@endsection
@section('page_js')
@stop