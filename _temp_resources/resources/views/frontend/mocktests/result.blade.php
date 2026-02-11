@extends('frontend.layout.sub-master')

@section('title')
    <title>Mock Test Result | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <section class="section bg-gray-100">
        <div class="container py-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="mb-0">Result: {{ $mockTest->title ?? 'Mock Test' }}</h3>
                    <div class="text-muted small">
                        Completed: {{ optional($result->completed_at)->format('d M Y, h:i A') ?? '-' }}
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">Score: {{ $result->score ?? 0 }}</div>
                    <div class="text-muted small">({{ $result->percentage ?? 0 }}%)</div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <div class="text-muted small">Total</div>
                            <div class="fw-semibold">{{ $result->total_questions ?? 0 }}</div>
                        </div>
                        <div class="col">
                            <div class="text-muted small">Correct</div>
                            <div class="fw-semibold text-success">{{ $result->total_correct ?? 0 }}</div>
                        </div>
                        <div class="col">
                            <div class="text-muted small">Incorrect</div>
                            <div class="fw-semibold text-danger">{{ $result->total_incorrect ?? 0 }}</div>
                        </div>
                        <div class="col">
                            <div class="text-muted small">Unattempted</div>
                            <div class="fw-semibold">{{ $result->total_unattempted ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Answer Review</strong>
                </div>
                <div class="card-body">
                    @forelse($responses as $idx => $resp)
                        @php
                            $q = $resp->question;
                            $selected = $q && $resp->response_option_id
                                ? $q->options->firstWhere('id', $resp->response_option_id)
                                : null;
                        @endphp

                        <div class="mb-4">
                            <div class="fw-semibold mb-1">
                                Q{{ $idx + 1 }}. {!! nl2br(e($q->question ?? '')) !!}
                            </div>
                            <div class="small">
                                <span class="text-muted">Your answer:</span>
                                <span class="{{ $resp->is_correct ? 'text-success' : 'text-danger' }}">
                                    {{ $selected->option_text ?? 'Not attempted' }}
                                </span>
                            </div>
                        </div>
                        <hr>
                    @empty
                        <div class="text-muted">No responses found.</div>
                    @endforelse

                    <div class="d-flex justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('student.mocktests.dashboard') }}">Back to Mock Tests</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

