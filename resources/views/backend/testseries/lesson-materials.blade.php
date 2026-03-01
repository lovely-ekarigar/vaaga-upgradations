@extends('frontend.layout.sub-master')

@section('title')
<title>Lesson Materials | {{ $lesson->title }}</title>
@stop

@section('content')
<div class="container mt-12 mb-10">
    <h4 class="mb-3">Lesson: {{ $lesson->title }}</h4>

    @if($media->count() > 0)
        @foreach($media as $file)
            @php
                $fileExt = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                $isPdf = $fileExt === 'pdf';
                $isDocx = in_array($fileExt, ['docx', 'doc']);
            @endphp
            <div class="d-flex justify-content-between align-items-center border p-3 mb-2 rounded">
                <div>
                    <strong>{{ $file->file_name }}</strong>
                </div>

                <div>
                    @if($isPdf)
                        {{-- PDF - Use built-in viewer --}}
                        <a href="{{ route('lesson.pdf.view', $file->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View PDF
                        </a>
                    @elseif($isDocx)
                        {{-- Word Document - Use Google Docs Viewer --}}
                        <a href="https://docs.google.com/gview?embedded=1&url={{ urlencode(asset('storage/uploads/' . $file->file_name)) }}" 
                           class="btn btn-sm btn-success"
                           target="_blank">
                            <i class="bi bi-eye"></i> View Document
                        </a>
                    @else
                        {{-- Other files - Direct download --}}
                        <a href="{{ asset('storage/uploads/' . $file->file_name) }}" 
                           class="btn btn-sm btn-secondary"
                           download>
                            <i class="bi bi-download"></i> Download
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            No materials uploaded for this lesson.
        </div>
    @endif
</div>
@stop
