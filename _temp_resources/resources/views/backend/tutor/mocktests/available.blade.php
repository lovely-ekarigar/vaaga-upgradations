@extends('backend.layouts.app')

@section('title')
    <title>Tutor Mock Tests | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0">Available Mock Tests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Questions</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($mockTests as $mt)
                                <tr>
                                    <td>{{ $mt->id }}</td>
                                    <td>{{ $mt->title }}</td>
                                    <td>{{ optional($mt->course)->title ?? $mt->course_id }}</td>
                                    <td>{{ optional($mt->questions)->count() ?? '-' }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('tutor.mocktests.preview', $mt->id) }}">Preview</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('tutor.mocktests.schedule_form', $mt->id) }}">Schedule</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No published mock tests found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a class="btn btn-sm btn-secondary" href="{{ route('tutor.mocktests.scheduled') }}">View Scheduled Tests</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

