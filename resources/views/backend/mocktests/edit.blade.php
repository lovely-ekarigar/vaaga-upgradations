@extends('backend.layouts.app')

@section('title')
    <title>Edit Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Edit Mock Test</h5>
                    <a href="{{ route('admin.mocktests.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.mocktests.update', $mockTest->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Assign to Classes <span class="text-danger">*</span></label>
                            @php
                                $selected = old('course_ids', $selectedCourseIds ?? []);
                                $selected = array_map('strval', (array)$selected);
                            @endphp
                            <select class="form-control" name="course_ids[]" multiple required size="6">
                                @foreach(($courses ?? []) as $id => $title)
                                    <option value="{{ $id }}" {{ in_array((string)$id, $selected, true) ? 'selected' : '' }}>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (Windows) / Cmd (Mac) to select multiple.</small>
                        </div>

                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input class="form-control" name="title" value="{{ old('title', $mockTest->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="4" required>{{ old('description', $mockTest->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Published</label>
                            <select class="form-control" name="published">
                                <option value="0" {{ (int)($mockTest->published ?? 0) === 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ (int)($mockTest->published ?? 0) === 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Selected Questions</h6>
                    <div class="small text-muted">Questions assigned from the Question Bank.</div>
                </div>
                <div class="card-body">
                    @php
                        $questions = $mockTest->questions ?? collect();

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
                                }
                                if (strlen(implode(' ', $parts)) > 220) break;
                            }
                            return trim(implode(' ', $parts));
                        };
                    @endphp

                    @if($questions->isEmpty())
                        <div class="text-muted">No questions selected yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 90px;">ID</th>
                                    <th>Question</th>
                                    <th style="width: 140px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($questions as $q)
                                    @php
                                        $raw = $q->question_json ?: $q->question;
                                        $preview = $previewFromEditorJs($raw);
                                    @endphp
                                    <tr>
                                        <td>{{ $q->id }}</td>
                                        <td>
                                            <div class="mb-1">
                                                {{ \Illuminate\Support\Str::limit($preview ?: ('Question #' . $q->id), 220) }}
                                            </div>
                                            <div class="small">
                                                <a href="{{ route('admin.questions.edit', ['question' => $q->id]) }}">Open in Question Bank</a>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.mocktests.detach_question', ['mockTestId' => $mockTest->id, 'questionId' => $q->id]) }}"
                                                  onsubmit="return confirm('Remove this question from the mock test?');">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

