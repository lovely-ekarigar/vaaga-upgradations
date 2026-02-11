@extends('backend.layouts.app')

@section('title')
    <title>Mock Test Results | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Results: {{ $mockTest->title }}</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.mocktests.schedules', $mockTest->id) }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="mb-2 text-muted">
                        Schedule: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }} ({{ $schedule->status }})
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Student</th>
                                <th>Total</th>
                                <th>Correct</th>
                                <th>Incorrect</th>
                                <th>Unattempted</th>
                                <th>Score</th>
                                <th>%</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $u)
                                @php $r = $u->mock_test_result ?? null; @endphp
                                <tr>
                                    <td>{{ $u->name ?? ($u->email ?? 'Student') }}</td>
                                    <td>{{ $r->total_questions ?? '-' }}</td>
                                    <td>{{ $r->total_correct ?? '-' }}</td>
                                    <td>{{ $r->total_incorrect ?? '-' }}</td>
                                    <td>{{ $r->total_unattempted ?? '-' }}</td>
                                    <td>{{ $r->score ?? '-' }}</td>
                                    <td>{{ $r->percentage ?? '-' }}</td>
                                    <td>
                                        @if($r)
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('admin.mocktests.analysis', ['scheduleId' => $schedule->id, 'studentId' => $u->id]) }}">
                                                Analysis
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No students found for this schedule.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

