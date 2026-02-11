@extends('backend.layouts.app')

@section('title')
    <title>Batch Results | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Batch Results</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('tutor.mocktests.scheduled') }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="mb-2 text-muted">
                        Mock Test: {{ $mockTest->title ?? 'Mock Test' }} | Date: {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Student</th>
                                <th>Score</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $u)
                                @php $r = $u->mock_test_result ?? null; @endphp
                                <tr>
                                    <td>{{ $u->name ?? $u->email ?? 'Student' }}</td>
                                    <td>{{ $r->score ?? '-' }}</td>
                                    <td>{{ $r->percentage ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No students found.</td>
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

