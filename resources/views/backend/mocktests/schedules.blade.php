@extends('backend.layouts.app')

@section('title')
    <title>Mock Test Schedules | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Schedules: {{ $mockTest->title }}</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.mocktests.show', $mockTest->id) }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($schedules as $s)
                                <tr>
                                    <td>{{ $s->id }}</td>
                                    <td>{{ optional($s->batch)->name ?? $s->batch_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($s->scheduled_date)->format('d M Y') }}</td>
                                    <td>{{ $s->status }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('admin.mocktests.results', $s->id) }}">Results</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No schedules found.</td>
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

