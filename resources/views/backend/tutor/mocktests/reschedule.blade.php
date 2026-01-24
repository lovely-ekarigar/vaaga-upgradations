@extends('backend.layouts.app')

@section('title')
    <title>Reschedule Mock Test | {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Reschedule Mock Test</h5>
                    <a class="btn btn-sm btn-secondary" href="{{ route('tutor.mocktests.scheduled') }}">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tutor.mocktests.reschedule') }}">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                        <div class="form-group">
                            <label>New Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="scheduled_date" required>
                        </div>

                        <div class="form-group">
                            <label>Reason</label>
                            <textarea class="form-control" name="reschedule_reason" rows="3"></textarea>
                        </div>

                        <button class="btn btn-primary">Reschedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

