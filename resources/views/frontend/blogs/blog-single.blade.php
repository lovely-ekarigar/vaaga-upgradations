@extends('frontend.layout.sub-master')
@section('title')
<title>{{$blog->meta_title ? $blog->meta_title :$blog->title}} | {{env('APP_NAME')}}</title>

<meta name="description" content="{{$blog->meta_description}}">
<meta name="keywords" content="{{$blog->meta_keywords}}">

<meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{$blog->title}} | {{env('APP_NAME')}}" />
    <meta property="og:description" content="{{$blog->meta_description}}" />
    <meta property="og:url" content="{{URL::to('/blog')}}/{{$blog->slug}}" />
    <meta property="og:site_name" content="VaaGa Academy | Online Learning Platforms For School Students" />
    <meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('0 days',strtotime($blog->created_at)))}}" />
    <meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('0 days',strtotime($blog->updated_at)))}}" />
    <meta property="og:image" content="{{asset('storage/uploads/'.$blog->image)}}" />
   
   <meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="{{$blog->title}}" />
<meta name="twitter:description" content="{{$blog->meta_description}}" />
<meta name="twitter:image" content="{{asset('storage/uploads/'.$blog->image)}}" /> 
<link rel="canonical" href="{{URL::to('/blog')}}/{{$blog->slug}}">
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


  
<section id="blog-item" class="py-6" style="background-color:#f9f9f9 !important">
        <div class="container" style="padding-top: 60px;">
             <div class="row">
                <div class="col-md-9">
                    <div class="card shadow-lg">
                                   <div class="card-body">
                                       
                                 <figure class="figure lightbox-gallery">
                                         <img alt="{{$blog->title}}" src="{{ asset('storage/uploads/' . $blog->image) }}" class="img-fluid shadow rounded">
                                  
                                </figure>

                            <h2>{{$blog->title}}</h2>
                                <div class="row d-flex w-100 justify-content-between">
                             
                                  @if($blog->user_id)
                                <div class="col-md-4"><img class="" style="height: 50px;border-radius: 50%;width: 50px;" src="{{ asset('storage/' . $blog->user->avatar_location) }}" title="" alt="">
                                    {{$blog->user->first_name}} {{$blog->user->middle_name}} {{$blog->user->last_name}} </div>
                                @endif
                                
                                 <!--<div class="col-md-2 pt-2"><i class="fa fa-calendar"></i> {{$blog->created_at->format('d M Y')}}</div>-->
                               
                                <!--<span class="d-inline"><i class="fa fa-tag"></i> {{ucwords($blog->category->name)}}</span>-->
                               <div class="justify-content-md-end d-flex pt-2">
                                    
                                     <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" role="button" href="https://www.facebook.com/profile.php?id=100095502854507"><i class="bi-facebook"></i> </a>
                                     <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://twitter.com/VaaGaAcademy" role="button"><i class="bi-twitter"></i> </a>
                                      <a class="icon-sm  rounded-circle ms-2" href="https://www.instagram.com/vaagaacademy/" aria-label="VaaGaAcademy Instagram" target="_blank"><i style="    font-size: 20px;color:#d74d5d" class="bi-instagram"></i> </a>
                                     <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://api.whatsapp.com/send?text={{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}" role="button"><i class="bi bi-whatsapp"></i> </a>
                                     <!--<a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://www.linkedin.com/company/96911976/admin/feed/posts/" role="button"><i class="bi-linkedin"></i></a>-->
                                      <a class="icon-sm  rounded-circle ms-2" href="https://www.linkedin.com/company/vaaga-academy/" aria-label="VaaGaAcademy Linkedin" target="_blank"><i style="    font-size: 20px;color:#0a66c2" class="bi-linkedin"></i></a>
                                </div>
                                </div>
                          
                            <br>
                            @include('includes.editorjs-parser', ['content' => $blog->content])
                            
                            
                                       </div>
                                       </div>
                     <!--                   <article class="card card-body">-->
                       
                     <!--   <div class="">-->
                     <!--      <div class="row align-items-center">-->
                     <!--         <div class="col-md-6">-->
                     <!--            <div class="media align-items-center">-->
                     <!--                 @if($blog->user_id)-->
                     <!--               <img class="avatar rounded-circle" src="{{ asset('storage/' . $blog->user->avatar_location) }}" title="{{$blog->user->first_name}} {{$blog->user->middle_name}} {{$blog->user->last_name}}" alt="{{$blog->user->first_name}} {{$blog->user->middle_name}} {{$blog->user->last_name}}">-->
                     <!--               <div class="media-body ps-3">-->
                     <!--                  <h6 class="mb-1">{{$blog->user->first_name}} {{$blog->user->middle_name}} {{$blog->user->last_name}}</h6>-->
                     <!--               </div>-->
                     <!--               @endif-->
                     <!--            </div>-->
                     <!--         </div>-->
                     <!--         <div class="col-md-6">-->
                     <!--            <div class="nav justify-content-end">-->
                     <!--                <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" role="button" href="https://www.facebook.com/sharer/sharer.php?u={{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}"><i class="bi-facebook"></i> </a>-->
                     <!--                <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://twitter.com/intent/tweet?text={{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}" role="button"><i class="bi-twitter"></i> </a>-->
                     <!--                <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://api.whatsapp.com/send?text={{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}" role="button"><i class="bi bi-whatsapp"></i> </a>-->
                     <!--                <a class="icon icon-sm rounded-circle text-white bg-primary ms-2" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}" role="button"><i class="bi-linkedin"></i></a></div>-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--</article>-->
                                       <br><br>
                                        <div id="disqus_thread"></div>
                    </div>
                      <div class="col-md-3">
                          
                          <div class="card mb-4">
                           <div class="card-header">
                              <h5 class="mb-0">Search</h5>
                           </div>
                           <div class="card-body text-center">
                              <form action="https://vaagaacademy.com/blog">
                                 <div class="d-flex flex-row">
                                     <input type="text" value="{{request('k')}}" name="k" class="form-control me-md-2" placeholder="Search"> 
                                     <button class="btn btn-primary flex-shrink-0" type="submit">
                                         <i class="bi bi-search"></i>
                                     </button>
                                 </div>
                              </form>
                           </div>
                        </div>
                    
                    
                    
                  <div class="card">
                                <div class="card-header bg-transparent p-3">
                                    <span class="h5 m-0">Categories</span>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($categories as $cat)
                                    <a href="{{route('blogs.category',['category'=>$cat->slug])}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
                                        <div>
                                            <span>{{$cat->name}}</span>
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

    
<script>
    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
   
    var disqus_config = function () {
     this.page.url = "{{ url()->current() }}"; // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = {{$blog->id}}; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
  
    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://dsvcvfb.disqus.com/embed.js'; 
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

@stop