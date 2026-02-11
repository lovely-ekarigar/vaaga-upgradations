
@extends('frontend.layout.sub-master')
@section('title')
<title>  {{$find_parent_category->meta_title}} | {{$board->name}} | {{env('APP_NAME')}}</title>
<meta name="description" content="{{$find_parent_category->meta_description}}">
<meta name="keywords" content="{{$find_parent_category->meta_keyword}}">
@stop
@section('page_css')
<style type="text/css">
  .card-body {
    padding: 8px 0px 6px 0px !important;
}
.mr-3 {
    margin-right: 1rem !important;
}
@media  only screen and (max-width: 600px) {
        .mtp-20{
  margin-top: 20px !important;
}
}
</style>
@stop
@section('content')

<main>
   <!-- Page Title --><!-- Home Banner -->
   <section class="bg-primary effect-section page-heading-pad mtp-20 m-0" style="background-image: url({{asset('newassets/img/bg/bg-page-header.jpeg')}}); background-size: cover;background-position: center;background-position-y: top;">
      <div class="mask bg-0000_ opacity-8"></div>
      <div class="container position-relative">
         <div class="row">
            <div class="col-lg-8">
               <h1 class="text-white h1">{{$board->name}}</h1>
               <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{$find_parent_category->name}}</li>
                <li class="breadcrumb-item active">{{$board->name}}</li>
              </ol>
            </div>
         </div>
      </div>
   </section>

   <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 my-3">
            <div class="row">
                 <div class="col-lg-12 my-3">
             <div class="card card-body" style="padding: 20px 20px 20px 20px !important;">
                <h5>{{$find_parent_category->name}}</h5>
                <p>{!!$find_parent_category->description!!}</p>
             </div>
          </div>
               @if($categories_list->count() > 0)
                <div class="col-12 pb-3">
              @foreach($categories_list as $cat)

             

                <a class="btn btn-warning mb-3 mr-3" href="{{route('academic-course',['slug' =>$cat->slug,'board' =>$boards])}}">{{$cat->name}}</a>
               
               
            
              @endforeach  
               </div>
              @else

              <div class="col-sm-12 col-lg-12">
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="card-body text-center">
                      <div class="py-4">
                        <img src="{{asset('newassets/img/no-data-found.png')}}">
                      </div>
                        <h4 class="h4 pb-4 ">No Course Found</h2>
                    </div>
                  </div>
                </div>

              @endif
              

            </div>
          </div>
          <div class="col-lg-4 my-3 ">
           
            <div class="card mt-5">
              <div class="card-header bg-transparent p-3">
                <span class="h5 m-0">Categories</span>
              </div>
             <div class="list-group list-group-flush">
                @foreach($categories as $catego)
                <a href="{{route('category',['slug'=>$catego->slug])}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
                  <div>
                    <span>{{$catego->name}}</span>
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
</main>

@stop