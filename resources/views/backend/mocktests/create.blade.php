@extends('backend.layouts.app')

@section('title')
    <title>Create Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Create Mock Test</h5>
                    <a href="{{ route('admin.mocktests.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.mocktests.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Course <span class="text-danger">*</span></label>
                            <select class="form-control" name="course_id" required>
                                @foreach(($courses ?? []) as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input class="form-control" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Published</label>
                            <select class="form-control" name="published">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

