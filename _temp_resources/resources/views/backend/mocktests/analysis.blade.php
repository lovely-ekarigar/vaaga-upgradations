@extends('backend.layouts.app')

@section('title')
    <title>Mock Test Analysis | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Analysis: {{ $mockTest->title }}</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.mocktests.results', $schedule->id) }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Student:</strong> {{ $user->name ?? $user->email ?? 'Student' }}<br>
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Question</th>
                                <th>Selected Option</th>
                                <th>Correct?</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($responses as $resp)
                                <tr>
                                    <td>{{ $resp->question_id }}</td>
                                    <td>{{ $resp->response_option_id ?? '-' }}</td>
                                    <td>
                                        @if((int)($resp->is_correct ?? 0) === 1)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No responses found.</td>
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

