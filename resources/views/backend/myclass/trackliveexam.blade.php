@extends('backend.layouts.app')

@section('title')
<title>Live Exam Tracking | {{ env('APP_NAME') }}</title> 
@stop

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Live Exam Tracking</h4>
        <span class="badge bg-light text-dark">
            <i class="fas fa-sync-alt fa-sm me-1"></i> Auto-refresh
        </span>
    </div>

    @php
        $allExams = $exams ?? [];
    @endphp

    @if(!empty($allExams))
        <div class="row g-3">
            @foreach($allExams as $index => $exam)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2"></span>
                            <h6 class="mb-0 text-truncate" style="max-width: 200px" title="{{ $exam['name'] ?? 'Student ' . ($index + 1) }}">
                                {{ $exam['name'] ?? 'Student ' . ($index + 1) }}
                            </h6>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="d-flex border-bottom">
                            <div class="p-2 flex-grow-1 border-end">
                                <small class="text-muted d-block">Test Name</small>
                                <small class="fw-semibold">{{ $exam['test'] ?? '-' }}</small>
                            </div>
                            <div class="p-2 text-center" style="width: 130px">
                                <small class="text-muted d-block">Last Activity</small>
                                @php
                                    $lastUpdate = is_numeric($exam['last_update']) ? intval($exam['last_update']) : null;
                                    $lastUpdateFormatted = $lastUpdate ? date('d M h:i A', $lastUpdate) : 'N/A';
                                @endphp
                                <small class="fw-semibold">{{ $lastUpdateFormatted }}</small>
                            </div>
                            <div class="p-2 text-center" style="width: 100px">
                                <small class="text-muted d-block">Current Qn</small>
                                <small class="fw-semibold">#{{ $exam['question'] ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <i class="fas fa-user-clock text-muted mb-2"></i>
            <p class="text-muted mb-0">No exams in progress</p>
        </div>
    </div>
    @endif
</div>

<style>
    .card {
        border-radius: 8px;
    }
    .table-sm th, .table-sm td {
        padding: 0.25rem 0.5rem;
    }
    small {
        font-size: 100%;
    }
</style>
@stop

@push('after-scripts')
<script>
setInterval(function(){
    window.location.reload();
}, 20000);
</script>
@endpush
