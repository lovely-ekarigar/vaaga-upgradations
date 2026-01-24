@extends('backend.layouts.app')

@section('title')
    <title>Mock Test Question Reports | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Question Reports</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.mocktests.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Reported By</th>
                                <th>Status</th>
                                <th>Reported At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reports as $r)
                                <tr>
                                    <td>{{ $r->id }}</td>
                                    <td>{{ $r->question_id }}</td>
                                    <td>{{ optional($r->reporter)->name ?? $r->reported_by }}</td>
                                    <td>{{ $r->status }}</td>
                                    <td>{{ optional($r->created_at)->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No reports found.</td>
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

