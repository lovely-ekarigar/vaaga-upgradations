dd($testSeries);
@extends('frontend.layout.sub-master')

@section('title')
<title>Study Material | {{ $testSeries->name ?? 'Test Series' }}</title>
@stop

@section('content')
<div class="container mt-12 mb-10">
    <h4 class="mb-3">Study Material: {{ $testSeries->name ?? 'Test Series' }}</h4>

    @if($lessons->count() > 0)
        <ul class="list-group">
            @foreach($lessons as $lesson)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    
                    {{ $lesson->title }}
                    <a href="{{ route('lesson.materials', $lesson->id) }}" class="btn btn-sm btn-primary">
                        View Files
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">
            No lessons available for this test series.
        </div>
    @endif
</div>

@stop
