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

                <h5>Lesson PDFs</h5>

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
    <div class="d-flex justify-content-between align-items-center border p-3 mb-2 rounded">
        
        <div>
            <strong>{{ $media->name }}</strong>
        </div>

        <div>
            <a href="{{ route('lesson.pdf.view', $media->id) }}" 
               class="btn btn-sm btn-primary">
                View
            </a>
        </div>

    </div>
@endforeach


            @else
                <p>No PDFs available for this lesson.</p>
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
