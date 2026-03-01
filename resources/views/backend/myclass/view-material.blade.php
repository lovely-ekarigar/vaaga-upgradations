@extends('frontend.layout.sub-master')

@section('title')
<title>Study Material | {{ env('APP_NAME') }}</title>
@stop

@section('content')

@include("frontend.include.user-menu")
</div>

<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">

        <h3 class="mb-4">{{ $lesson->title }}</h3>

        <div class="study-material-content mb-4">
            {!! $lesson->study_material !!}
        </div>

        {{-- If lesson not started --}}
        @if(!$lessonStatus)
            <div class="alert alert-warning">
                Study material will be available once you start this lesson.
            </div>
        @else

            {{-- If PDFs exist --}}
            @if($lessonMedia->count() > 0)

                <h5>Lesson Documents</h5>

                <!-- @foreach($lessonMedia as $media)
                    <div class="mb-4">
                        <iframe 
                            src="{{ $media->url }}"

                            width="100%" 
                            height="600px">
                        </iframe>
                    </div>
                @endforeach -->


                @foreach($lessonMedia as $media)
    @php
        // Skip media without valid ID or file_name
        if (empty($media->id) || empty($media->file_name)) {
            continue;
        }
        
        $fileExt = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
        $isPdf = $fileExt === 'pdf';
        $isDocx = in_array($fileExt, ['docx', 'doc']);
        
        // Get the file URL - prioritize url field, fallback to storage path
        $fileUrl = $media->url;
        if (empty($fileUrl)) {
            $fileUrl = asset('storage/uploads/' . $media->file_name);
        }
        
        // Check if file actually exists on disk
        $fileExists = file_exists(public_path('storage/uploads/' . $media->file_name));
    @endphp
    <div class="d-flex justify-content-between align-items-center border p-3 mb-2 rounded">
        <div>
            <strong>{{ $media->name }}</strong>
            <small class="d-block text-muted">{{ $media->file_name }}</small>
        </div>

        <div>
            @if(!$fileExists)
                {{-- File not found on server --}}
                <span class="btn btn-sm btn-warning disabled" title="File not found on server">
                    <i class="bi bi-exclamation-triangle"></i> File Missing
                </span>
            @elseif($isPdf)
                {{-- PDF - Use built-in viewer --}}
                <a href="{{ route('lesson.pdf.view', $media->id) }}" 
                   class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i> View PDF
                </a>
            @elseif($isDocx)
                {{-- Word Document - Use Google Docs Viewer (needs public URL) --}}
                <a href="https://docs.google.com/gview?embedded=1&url={{ urlencode($fileUrl) }}" 
                   class="btn btn-sm btn-success"
                   target="_blank"
                   rel="noopener noreferrer">
                    <i class="bi bi-eye"></i> View Document
                </a>
            @else
                {{-- Other files - Direct download --}}
                <a href="{{ $fileUrl }}" 
                   class="btn btn-sm btn-secondary"
                   download>
                    <i class="bi bi-download"></i> Download
                </a>
            @endif
        </div>
    </div>
@endforeach


            @else
                <p>No Documents available for this lesson.</p>
            @endif

        @endif

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                ← Back to Lessons
            </a>
        </div>

    </div>
</div>
</section> </main>

@stop
