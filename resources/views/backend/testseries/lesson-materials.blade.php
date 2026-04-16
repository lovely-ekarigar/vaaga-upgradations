@extends('frontend.layout.sub-master')

@section('title')
<title>Lesson Materials | {{ $lesson->title }}</title>
@stop

@section('content')
<div class="container mt-12 mb-10">
    <h4 class="mb-3">Lesson: {{ $lesson->title }}</h4>

    @if($media->count() > 0)
        @foreach($media as $file)
            <div class="d-flex justify-content-between align-items-center border p-3 mb-2 rounded">
                <div>
                    <strong>{{ $file->file_name }}</strong>
                </div>

                <div>
                    <a href="{{ route('lesson.pdf.view', $file->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
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
