@extends('frontend.layout.sub-master')

@section('title')
    <title>Attempt Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <section class="section bg-gray-100">
        <div class="container py-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="mb-0">{{ $mockTest->title ?? 'Mock Test' }}</h3>
                    <div class="text-muted small">
                        Schedule: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                    </div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">Time remaining (today)</div>
                    <div class="fw-semibold">{{ gmdate('H:i:s', (int)($timeRemaining ?? 0)) }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.mocktests.submit') }}">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @php
                            $previewFromEditorJs = function ($raw) {
                                $s = is_string($raw) ? trim($raw) : '';
                                if ($s === '') return '';
                                $decoded = json_decode($s, true);
                                if (!is_array($decoded) || empty($decoded['blocks']) || !is_array($decoded['blocks'])) {
                                    return strip_tags($s);
                                }
                                $parts = [];
                                foreach ($decoded['blocks'] as $b) {
                                    if (!is_array($b)) continue;
                                    $type = $b['type'] ?? null;
                                    $data = $b['data'] ?? [];
                                    if ($type === 'paragraph' || $type === 'header') {
                                        $t = trim(strip_tags((string)($data['text'] ?? '')));
                                        if ($t !== '') $parts[] = $t;
                                    } elseif ($type === 'list' && !empty($data['items']) && is_array($data['items'])) {
                                        foreach ($data['items'] as $it) {
                                            $t = trim(strip_tags((string)$it));
                                            if ($t !== '') $parts[] = $t;
                                        }
                                    } elseif ($type === 'image') {
                                        $parts[] = '[Image]';
                                    }
                                    if (strlen(implode(' ', $parts)) > 1200) break;
                                }
                                return trim(implode(' ', $parts));
                            };
                        @endphp

                        @forelse($questions as $qIndex => $question)
                            @php
                                $rawQ = $question->question_json ?: $question->question;
                                $qText = $previewFromEditorJs($rawQ);
                            @endphp
                            <div class="mb-4">
                                <div class="fw-semibold mb-2">
                                    Q{{ $qIndex + 1 }}. {!! nl2br(e($qText ?: '')) !!}
                                </div>

                                <div class="ms-2">
                                    @forelse(($question->options ?? []) as $opt)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="responses[{{ $question->id }}]"
                                                   id="q{{ $question->id }}_o{{ $opt->id }}"
                                                   value="{{ $opt->id }}">
                                            <label class="form-check-label" for="q{{ $question->id }}_o{{ $opt->id }}">
                                                {!! nl2br(e($opt->option_text ?? '')) !!}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-muted">No options configured for this question.</div>
                                    @endforelse
                                </div>
                            </div>
                            <hr>
                        @empty
                            <div class="text-muted">No questions found for this test.</div>
                        @endforelse

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit Test</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

