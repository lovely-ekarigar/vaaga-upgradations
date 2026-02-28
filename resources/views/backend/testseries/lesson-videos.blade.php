@extends('frontend.layout.sub-master')

@section('content')

<div class="container mt-12 mb-10">
    <h4>Lesson Videos: {{ $lesson->title }}</h4>

    <div class="row">
        @forelse($videos as $video)
            @php
                $parts = explode('/', $video->url);
                $lastPart = end($parts);
                $videoId = explode('?', $lastPart)[0];
            @endphp

            <div class="col-md-4 mb-4 mt-5">
                <div class="card shadow-sm">
                    <div class="card-body p-2">

                        @if($videoId)
                            <div class="vplay text-center"
                                 data-href="https://www.youtube.com/embed/{{ $videoId }}">
                                 
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                     class="img-fluid"
                                     style="cursor:pointer;"
                                     alt="Lesson Video Thumbnail">
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No videos for this lesson.</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body">
            <div class="vplay-data"></div>
         </div>
      </div>
   </div>
</div>

@endsection

@section('page_js')

<script>
$(document).on("click",".vplay",function(){
    var href = $(this).data("href");

    $("#exampleModalCenter .vplay-data")
        .html('<iframe src="'+href+'?autoplay=1&rel=0" style="width:100%;height:450px;" frameborder="0" allowfullscreen></iframe>');

    $("#exampleModalCenter").modal('show');
});

$('#exampleModalCenter').on('hidden.bs.modal', function () {
    $(".vplay-data").html('');
});
</script>

@endsection 
