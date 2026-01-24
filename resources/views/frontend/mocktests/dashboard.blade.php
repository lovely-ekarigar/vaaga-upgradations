@extends('frontend.layout.sub-master')

@section('title')
    <title>Mock Tests | {{ env('APP_NAME') }}</title>
@endsection

@section('page_css')
    <style>
        /* This page was showing a purple background due to theme styles. Keep mocktests clean/white only here. */
        .bg-gray-100 { background: #ffffff !important; }
    </style>
@endsection

@section('content')
    <section class="section bg-gray-100">
        <div class="container py-5">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="mb-0">Mock Tests</h3>
                    <p class="text-muted mb-0">Upcoming, available and completed tests.</p>
                </div>
            </div>

            @if(session('flash_danger'))
                <div class="alert alert-danger">{{ session('flash_danger') }}</div>
            @endif
            @if(session('flash_warning'))
                <div class="alert alert-warning">{{ session('flash_warning') }}</div>
            @endif
            @if(session('flash_success'))
                <div class="alert alert-success">{{ session('flash_success') }}</div>
            @endif

            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white">
                            <strong>Available Today</strong>
                        </div>
                        <div class="card-body">
                            @forelse($availableTests as $schedule)
                                <div class="border rounded p-3 mb-3">
                                    <div class="fw-semibold">
                                        {{ $schedule->mockTest->title ?? 'Mock Test' }}
                                    </div>
                                    <div class="small text-muted">
                                        Date: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                                    </div>
                                    <div class="mt-2">
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('student.mocktests.attempt', ['scheduleId' => $schedule->id]) }}">
                                            Attempt
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">No tests available today.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white">
                            <strong>Upcoming</strong>
                        </div>
                        <div class="card-body">
                            @forelse($upcomingTests as $schedule)
                                <div class="border rounded p-3 mb-3">
                                    <div class="fw-semibold">
                                        {{ $schedule->mockTest->title ?? 'Mock Test' }}
                                    </div>
                                    <div class="small text-muted">
                                        Date: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">No upcoming tests.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white">
                            <strong>Completed</strong>
                        </div>
                        <div class="card-body">
                            @forelse($completedTests as $schedule)
                                @php
                                    $result = optional($schedule->results)->first();
                                @endphp
                                <div class="border rounded p-3 mb-3">
                                    <div class="fw-semibold">
                                        {{ $schedule->mockTest->title ?? 'Mock Test' }}
                                    </div>
                                    <div class="small text-muted">
                                        Date: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                                    </div>
                                    @if($result)
                                        <div class="mt-2">
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('student.mocktests.result', ['resultId' => $result->id]) }}">
                                                View Result
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-muted">No completed tests.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($publishedTests) && count($publishedTests) > 0)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white">
                        <strong>Published Mock Tests (Not Scheduled Yet)</strong>
                        <div class="text-muted small">
                            {{ $publishedTestsNote ?? "These are created & published, but not yet assigned/scheduled for your batch." }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($publishedTests as $mt)
                                    <tr>
                                        <td>{{ $mt->title }}</td>
                                        <td>
                                            {{ ($mt->courses ?? collect())->pluck('title')->filter()->implode(', ') ?: (optional($mt->course)->title ?? $mt->course_id) }}
                                        </td>
                                        <td>
                                            @if(!empty($publishedScheduledIds) && in_array($mt->id, $publishedScheduledIds))
                                                <span class="badge bg-info">Scheduled (Not assigned to you)</span>
                                            @else
                                                <span class="badge bg-secondary">Awaiting Schedule</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

