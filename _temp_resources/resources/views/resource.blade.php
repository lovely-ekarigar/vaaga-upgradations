@extends('layout')

@section('title')
{{$res->name}}
@stop
@section('seo_des','des')
@section('seo_key','Livetutorials')
@section('content')



<section class="breadcrumb-area pt-50px pb-50px bg-white pattern-bg">
    <div class="container">
        <div class="col-lg-8 mr-auto">
            <div class="breadcrumb-content">
                <ul class="generic-list-item generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="/">Home</a></li>
                    <li><a href="#">{{$res->name}}</a></li>
                   
                </ul>
                <div class="section-heading">
                    <h2 class="section__title">{{$res->name}}</h2>
                </div><!-- end section-heading -->
               
                
            
               
            </div><!-- end breadcrumb-content -->
        </div><!-- end col-lg-8 -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<section class="course-details-area pb-20px">
    <div class="container">
        <div class="row">
           <div class="col-lg-8 pb-5">
               <div class="course-details-content-wrap pt-90px">
                  
                   <div class="course-overview-card">
                       <div class="curriculum-header d-flex align-items-center justify-content-between pb-4">
                         
                           <div class="curriculum-duration fs-15">
                               <span class="curriculum-total__text mr-2"><strong class="text-black font-weight-semi-bold">Total:</strong> <?=count($reslist)?> Files</span>
                              
                           </div>
                       </div>
                       <div class="curriculum-content">
                           <div id="accordion" class="generic-accordion">

                            @foreach($reslist as $r)
                               <div class="card">
                                   <div class="card-header" id="headingOne{{$r->id}}">
                                       <button class="btn btn-link d-flex align-items-center justify-content-between collapsed" data-toggle="collapse" data-target="#collapseOne{{$r->id}}" aria-expanded="false" aria-controls="collapseOne">
                                           <i class="la la-plus"></i>
                                           <i class="la la-minus"></i>
                                           {{$r->name}}
                                          
                                       </button>
                                   </div><!-- end card-header -->
                                   <div id="collapseOne{{$r->id}}" class="collapse" aria-labelledby="headingOne{{$r->id}}" data-parent="#accordion" style="">
                                       <div class="card-body">
                                          {!! $r->content !!}
                                         
                                <a href="/storage/uploads/{{$r->file}}" download class="btn btn-outline-success ">Download <i class="la la-arrow-right icon ml-1"></i></a>
                            
                                       </div><!-- end card-body -->
                                   </div><!-- end collapse -->
                               </div><!-- end card -->

                               @endforeach

                           </div><!-- end generic-accordion -->
                       </div><!-- end curriculum-content -->
                   </div><!-- end course-overview-card -->
                   
                  
                 
               </div><!-- end course-details-content-wrap -->
           </div><!-- end col-lg-8 -->
            <div class="col-lg-4">
                <div class="sidebar sidebar-negative">
                    <div class="card card-item">
                        <div class="card-body">
                          
                            
                        </div>
                    </div><!-- end card -->
                    
                   
                    
                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section>
@stop




