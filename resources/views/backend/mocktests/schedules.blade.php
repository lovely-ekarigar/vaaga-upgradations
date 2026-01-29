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
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="mb-3">Assign / Schedule this Mock Test</h6>
                            <form method="POST" action="{{ route('admin.mocktests.assign_batch') }}" class="row g-2 align-items-end">
                                @csrf
                                <input type="hidden" name="mock_test_id" value="{{ $mockTest->id }}">

                                <div class="col-md-5">
                                    <label class="form-label">Batch</label>
                                    <select name="batch_id" class="form-control" required>
                                        <option value="">Please select</option>
                                        @foreach(($batch_list ?? collect()) as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="scheduled_date" class="form-control" value="{{ now()->toDateString() }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Timezone</label>
                                    <input type="text" name="timezone" class="form-control" value="{{ auth()->user()->timezone ?? 'Asia/Kolkata' }}">
                                </div>

                                <div class="col-md-1">
                                    <button class="btn btn-primary w-100" type="submit">Save</button>
                                </div>
                            </form>
                            <div class="small text-muted mt-2">
                                Note: Students must be in the selected batch to see this test as “Available”.
                            </div>
                        </div>
                    </div>

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
                                        @if($s->results()->count() === 0)
                                            <a class="btn btn-sm btn-outline-primary ml-1" href="{{ route('admin.mocktests.schedules.reschedule_form', $s->id) }}">Reschedule</a>
                                        @endif
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

