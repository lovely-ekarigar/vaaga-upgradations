@extends('frontend.layout.sub-master')
@section('title')
<title>Study | {{env('APP_NAME')}}</title>
@stop

@push('after-styles')
<style type="text/css">
    .media-item {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin: 0.125rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
    }
    .media-item:hover {
        background: #e9ecef;
        color: inherit;
    }
    .media-item i {
        font-size: 1.1rem;
        margin-right: 0.375rem;
    }
    .media-item.video { color: #0d6efd; }
    .media-item.pdf { color: #dc3545; }
    .media-item.audio { color: #198754; }
    .media-item.document { color: #6c757d; }
    
    .lesson-media-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0c63e4;
    }
    
    .lesson-row {
        padding: 0.75rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s;
    }
    .lesson-row:hover {
        background-color: #f8f9fa;
    }
    
    .play-button-overlay {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        background: #e9ecef;
        border-radius: 50%;
    }
    .play-button-overlay i {
        font-size: 1rem;
        color: #495057;
    }
</style>
@endpush

@section('content')
@include("frontend.include.user-menu")
</div>

<div class="col-lg-8 col-xl-9">
   <div class="profile-content-area my-6 card card-body">
      <div class="border-bottom mb-6 pb-6">
         <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
         <h6 class="text-body fw-500 mb-3">Course Content: {{$course->title}}</h6>
         
         @if(count($clist) > 0)
         <div class="accordion shadow" id="accordionCourseContent">
            @php $count = 0; @endphp
            @foreach($clist as $ct)
            @php $count++ @endphp
            <div class="accordion-item">
               <p class="m-0 accordion-header" id="heading_{{$count}}">
                  <button class="py-3 accordion-button fw-bold {{ $count > 1 ? 'collapsed' : '' }}" 
                          type="button" 
                          data-bs-toggle="collapse" 
                          data-bs-target="#collapse_{{$count}}" 
                          aria-expanded="{{ $count == 1 ? 'true' : 'false' }}" 
                          aria-controls="collapse_{{$count}}">
                      <i class="bi bi-folder-fill me-2 text-warning"></i>
                      {{$ct->title}}
                  </button>
               </p>
               <div id="collapse_{{$count}}" 
                    class="accordion-collapse collapse {{ $count == 1 ? 'show' : '' }}" 
                    aria-labelledby="heading_{{$count}}" 
                    data-bs-parent="#accordionCourseContent">
                  <div class="accordion-body">
                     <ul class="list-unstyled mb-0">
                        @foreach($ct->lessons as $cl)
                        <li class="lesson-row mb-3">
                           <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                              <div class="d-flex align-items-center">
                                 <div class="play-button-overlay me-3">
                                    <i class="bi bi-play-fill"></i>
                                 </div>
                                 <div>
                                    <h6 class="mb-1 fw-bold">{{$cl->title}}</h6>
                                    @if($cl->duration)
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{$cl->duration}}
                                        </small>
                                    @endif
                                 </div>
                              </div>
                              
                              {{-- Lesson Media Files --}}
                              @if($cl->media && count($cl->media) > 0)
                              <div class="lesson-media-grid">
                                  @foreach($cl->media as $cm)
                                      @php
                                          $isVideo = in_array($cm->type, ['upload', 'youtube', 'vimeo', 'embed']);
                                          $isAudio = $cm->type == 'lesson_audio';
                                          $isPDF = $cm->type == 'lesson_pdf';
                                          $isExternal = in_array($cm->type, ['youtube', 'vimeo', 'embed']);
                                      @endphp
                                      
                                      @if($isVideo)
                                          {{-- Video - Open in Modal --}}
                                          <a href="javascript:void(0)" 
                                             class="media-item video"
                                             onclick="openVideoModal('{{ $cm->id }}', '{{ $cm->name }}', '{{ $cm->file_url ?? $cm->url }}', '{{ $cm->type }}', '{{ $cm->embed_url ?? '' }}')"
                                             title="Play Video: {{ $cm->name }}">
                                              <i class="bi bi-play-circle-fill"></i>
                                              <span>Video</span>
                                          </a>
                                      @elseif($isAudio)
                                          {{-- Audio - Open in Modal --}}
                                          <a href="javascript:void(0)" 
                                             class="media-item audio"
                                             onclick="openAudioModal('{{ $cm->id }}', '{{ $cm->name }}', '{{ $cm->file_url }}', '{{ $cm->mime_type ?? 'audio/mpeg' }}')"
                                             title="Play Audio: {{ $cm->name }}">
                                              <i class="bi bi-music-note-beamed"></i>
                                              <span>Audio</span>
                                          </a>
                                      @elseif($isPDF)
                                          {{-- PDF - Open in Modal or Download --}}
                                          <a href="javascript:void(0)" 
                                             class="media-item pdf"
                                             onclick="openPdfModal('{{ $cm->id }}', '{{ $cm->name }}', '{{ $cm->file_url }}')"
                                             title="View PDF: {{ $cm->name }}">
                                              <i class="bi bi-file-pdf-fill"></i>
                                              <span>PDF</span>
                                          </a>
                                      @else
                                          {{-- Downloadable File --}}
                                          <a href="{{ route('user.content.download', ['cid' => $course->id, 'mid' => $cm->id]) }}" 
                                             class="media-item document"
                                             download
                                             title="Download: {{ $cm->name }}">
                                              <i class="bi bi-file-earmark-arrow-down"></i>
                                              <span>Download</span>
                                          </a>
                                      @endif
                                  @endforeach
                              </div>
                              @endif
                           </div>
                           
                           @if($cl->short_text)
                           <p class="mt-2 mb-0 text-muted small">{{$cl->short_text}}</p>
                           @endif
                        </li>
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         @else
         <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="mt-3 text-muted">No lessons available for this course yet.</p>
         </div>
         @endif
      </div>
   </div>
</div>
</div>
</div>
</section>

{{-- Video Modal --}}
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalTitle">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9 bg-dark" id="videoModalContent">
                    {{-- Video content injected here --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Audio Modal --}}
<div class="modal fade" id="audioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="audioModalTitle">Audio Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center p-4" id="audioModalContent">
                    {{-- Audio content injected here --}}
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="audioDownloadLink" class="btn btn-primary" download>
                    <i class="bi bi-download me-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- PDF Modal --}}
<div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalTitle">PDF Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="height: 70vh;" id="pdfModalContent">
                    {{-- PDF content injected here --}}
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="pdfDownloadLink" class="btn btn-primary" download>
                    <i class="bi bi-download me-1"></i>Download PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- End Section -->
</main>

@push('after-scripts')
<script>
    // Video Modal
    function openVideoModal(id, name, url, type, embedUrl) {
        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        const titleEl = document.getElementById('videoModalTitle');
        const contentEl = document.getElementById('videoModalContent');
        
        titleEl.textContent = name || 'Video';
        
        let content = '';
        
        if (type === 'youtube' || type === 'vimeo') {
            // External video
            if (embedUrl) {
                content = `<iframe src="${embedUrl}" 
                                   title="${name}" 
                                   frameborder="0" 
                                   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                   allowfullscreen></iframe>`;
            } else {
                // Extract video ID and create embed URL
                let embed = '';
                if (type === 'youtube') {
                    const videoId = url.split('/').pop().split('?')[0];
                    embed = `https://www.youtube.com/embed/${videoId}`;
                } else {
                    const videoId = url.split('/').pop();
                    embed = `https://player.vimeo.com/video/${videoId}`;
                }
                content = `<iframe src="${embed}" 
                                   title="${name}" 
                                   frameborder="0" 
                                   allow="autoplay; fullscreen" 
                                   allowfullscreen></iframe>`;
            }
        } else {
            // Local video file
            content = `<video controls autoplay class="w-100 h-100">
                        <source src="${url}" type="video/mp4">
                        Your browser does not support the video tag.
                       </video>`;
        }
        
        contentEl.innerHTML = content;
        
        // Stop video when modal closes
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
            contentEl.innerHTML = '';
        }, { once: true });
        
        modal.show();
    }
    
    // Audio Modal
    function openAudioModal(id, name, url, mimeType) {
        const modal = new bootstrap.Modal(document.getElementById('audioModal'));
        const titleEl = document.getElementById('audioModalTitle');
        const contentEl = document.getElementById('audioModalContent');
        const downloadLink = document.getElementById('audioDownloadLink');
        
        titleEl.textContent = name || 'Audio';
        
        contentEl.innerHTML = `
            <div class="mb-3">
                <i class="bi bi-music-note-beamed display-1 text-success"></i>
            </div>
            <audio controls autoplay class="w-100">
                <source src="${url}" type="${mimeType || 'audio/mpeg'}">
                Your browser does not support the audio element.
            </audio>
        `;
        
        downloadLink.href = url;
        
        // Stop audio when modal closes
        document.getElementById('audioModal').addEventListener('hidden.bs.modal', function() {
            contentEl.innerHTML = '';
        }, { once: true });
        
        modal.show();
    }
    
    // PDF Modal
    function openPdfModal(id, name, url) {
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        const titleEl = document.getElementById('pdfModalTitle');
        const contentEl = document.getElementById('pdfModalContent');
        const downloadLink = document.getElementById('pdfDownloadLink');
        
        titleEl.textContent = name || 'PDF Document';
        
        contentEl.innerHTML = `<iframe src="${url}" width="100%" height="100%"></iframe>`;
        
        downloadLink.href = url;
        
        modal.show();
    }
</script>
@endpush

@stop
