@extends('backend.layouts.app')

@section('title')
    <title>Schedule Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Schedule: {{ $mockTest->title }}</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('tutor.mocktests.available') }}">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tutor.mocktests.schedule') }}">
                        @csrf
                        <input type="hidden" name="mock_test_id" value="{{ $mockTest->id }}">

                        <div class="form-group">
                            <label>Batch <span class="text-danger">*</span></label>
                            <select class="form-control" name="batch_id" required>
                                @foreach($batches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name ?? ('Batch #' . $b->id) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Scheduled Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="scheduled_date" required>
                        </div>

                        <div class="form-group">
                            <label>Timezone</label>
                            <input class="form-control" name="timezone" value="Asia/Kolkata">
                        </div>

                        <button class="btn btn-primary">Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

