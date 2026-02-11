@extends('backend.layouts.app')

@section('title')
    <title>Mock Test Details | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Mock Test: {{ $mockTest->title }}</h5>
                    <div>
                        <a class="btn btn-sm btn-secondary" href="{{ route('admin.mocktests.index') }}">Back</a>
                        @can('mocktest_edit')
                            <a class="btn btn-sm btn-warning" href="{{ route('admin.mocktests.edit', $mockTest->id) }}">Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <strong>Assigned Classes:</strong>
                        {{ ($mockTest->courses ?? collect())->pluck('title')->filter()->implode(', ') ?: (optional($mockTest->course)->title ?? $mockTest->course_id) }}
                    </div>
                    <div class="mt-2"><strong>Description:</strong><br>{{ $mockTest->description }}</div>
                    <div class="mt-2"><strong>Published:</strong> {{ (int)($mockTest->published ?? 0) === 1 ? 'Yes' : 'No' }}</div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a class="btn btn-sm btn-info" href="{{ route('admin.mocktests.schedules', $mockTest->id) }}">View Schedules</a>
                    <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.mocktests.question_reports') }}">Question Reports</a>
                </div>
            </div>
        </div>
    </div>
@endsection

