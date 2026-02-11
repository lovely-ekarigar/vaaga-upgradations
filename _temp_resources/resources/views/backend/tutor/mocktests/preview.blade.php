@extends('backend.layouts.app')

@section('title')
    <title>Preview Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Preview: {{ $mockTest->title }}</h5>
                    <div class="d-flex gap-2">
                        @if(\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status') && isset($mockTest->status) && $mockTest->status !== \App\Models\MockTest::STATUS_REVIEWED && $mockTest->status !== \App\Models\MockTest::STATUS_PUBLISHED)
                            <form method="POST" action="{{ route('tutor.mocktests.approve', $mockTest->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                        @elseif(\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status') && isset($mockTest->status))
                            <span class="badge badge-success">{{ ucfirst($mockTest->status) }}</span>
                        @endif
                        <a class="btn btn-sm btn-primary" href="{{ route('tutor.mocktests.schedule_form', $mockTest->id) }}">Schedule</a>
                        <a class="btn btn-sm btn-secondary" href="{{ route('tutor.mocktests.available') }}">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($questions as $i => $q)
                        <div class="mb-3">
                            <div class="fw-semibold">Q{{ $i + 1 }}. {!! nl2br(e($q->question ?? '')) !!}</div>
                            <div class="ms-3 mt-2">
                                @foreach(($q->options ?? []) as $opt)
                                    <div class="small">
                                        - {{ $opt->option ?? '' }}
                                        @if(!empty($opt->correct) && (int)$opt->correct === 1)
                                            <span class="badge badge-success">Correct</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                    @empty
                        <div class="text-muted">No questions found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

