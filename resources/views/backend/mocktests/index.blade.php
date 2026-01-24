@extends('backend.layouts.app')

@section('title')
    <title>Mock Tests | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            @include('admin.includes.message')

            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Mock Tests</h5>
                    @can('mocktest_create')
                        <a href="{{ route('admin.mocktests.create') }}" class="btn btn-primary btn-sm">Create Mock Test</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Published</th>
                                <th>Created</th>
                                <th style="width: 200px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($mockTests as $mt)
                                <tr>
                                    <td>{{ $mt->id }}</td>
                                    <td>{{ $mt->title }}</td>
                                    <td>
                                        {{ ($mt->courses ?? collect())->pluck('title')->filter()->implode(', ') ?: (optional($mt->course)->title ?? $mt->course_id) }}
                                    </td>
                                    <td>
                                        @if((int)($mt->published ?? 0) === 1)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($mt->created_at)->format('d M Y') }}</td>
                                    <td>
                                        @can('mocktest_view')
                                            <a class="btn btn-sm btn-info" href="{{ route('admin.mocktests.show', $mt->id) }}">View</a>
                                        @endcan
                                        @can('mocktest_edit')
                                            <a class="btn btn-sm btn-warning" href="{{ route('admin.mocktests.edit', $mt->id) }}">Edit</a>
                                        @endcan
                                        @can('mocktest_delete')
                                            <form action="{{ route('admin.mocktests.destroy', $mt->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this mock test?')">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No mock tests found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="small text-muted">
                        Tip: Use “Assign Batch” inside a mock test (View) once you create it.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

