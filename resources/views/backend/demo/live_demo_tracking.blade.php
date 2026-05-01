@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid">

    <h3 class="mb-4">Live Demo Tracking</h3>

    <div class="row">

        @forelse($demos as $demo)

        @php
            $course = \App\Models\Course::find($demo->course_id);
            $teacher = \App\Models\Auth\User::find($demo->teacher_id);

            $participants = DB::table('demo_participants')
                ->where('demo_id', $demo->id)
                ->orderBy('joined_at','desc')
                ->get();
        @endphp

        {{-- CARD --}}
        <div class="col-md-6 col-lg-6 mb-3">

            <div class="card border-0 shadow-sm rounded-3">

                <div class="card-body p-0">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-white">

                        <div class="d-flex align-items-center">
                            <span class="badge rounded-circle me-2"
                                  style="width:10px;height:10px;
                                  background:{{ $demo->demo_status == 'started' ? '#28a745' : '#6c757d' }};">
                            </span>

                            <h6 class="mb-0 text-truncate fw-semibold" style="max-width:220px;">
                                {{ $course->title ?? 'Course Not Found' }}
                            </h6>
                        </div>

                        <span class="badge rounded-pill px-3 py-1"
                              style="background:#ede9fe; color:#5b21b6; font-size:12px;">
                            {{ ucfirst($demo->demo_status) }}
                        </span>

                    </div>

                    {{-- DATE + PARTICIPANTS --}}
                    <div class="d-flex border-bottom">

                        <div class="p-2 flex-grow-1 border-end">
                            <small class="text-muted d-block">Date</small>
                            <small class="fw-semibold">
                                {{ date("d M h:i A", strtotime($demo->demo_date_time)) }}
                            </small>
                        </div>

                        <div class="p-2 text-center" style="width:100px;">
                            <small class="text-muted d-block">Participants</small>
                            <small class="fw-semibold">
                                {{ $participants->count() }}
                            </small>
                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="table table-sm table-borderless mb-0">

                            <thead>
                                <tr class="text-muted border-bottom">
                                    <th class="fw-normal ps-3">Name</th>
                                    <th class="fw-normal text-center">Role</th>
                                    <th class="fw-normal text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- TEACHER --}}
                                <tr>
                                    <td class="ps-3 py-1">
                                        {{ $teacher->name ?? 'N/A' }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-info">Tutor</span>
                                    </td>

                                    <td class="text-center">
                                        @if($demo->demo_status === 'started')
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill text-secondary"></i>
                                        @endif
                                    </td>
                                </tr>

                                {{-- STUDENT --}}
                                <tr>
                                    <td class="ps-3 py-1">
                                        {{ $demo->name }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-dark">Student</span>
                                    </td>

                                    <td class="text-center">
                                        @if($demo->student_join_at)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                </tr>

                                {{-- PARTICIPANTS --}}
                                @foreach($participants as $p)
                                <tr>
                                    <td class="ps-3 py-1">
                                        {{ $p->name }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($p->role) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <i class="bi bi-dot text-success fs-4"></i>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- FOOTER --}}
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">

                        @if($demo->meet_link)
                           <a href="javascript:void(0)"
   class="btn btn-success btn-sm rounded-pill px-3 fw-semibold joinGoogleMeet"
   data-id="{{ $demo->id }}">
    <i class="bi bi-camera-video-fill me-1"></i>
    Join Meeting
</a>
                        @else
                            <span class="text-danger small">No Link</span>
                        @endif

                        <small class="text-muted">
                            Live Tracking
                        </small>

                    </div>

                </div>
            </div>
        </div>

        @empty

        <div class="col-12">
            <div class="alert alert-warning">
                No live demos available.
            </div>
        </div>

        @endforelse

    </div>
</div>
@endsection

@push('after-scripts')
<script>
setInterval(function () {
    if ($('.modal.show').length === 0) {
        window.location.reload();
    }
}, 20000);


$(document).on('click', '.joinGoogleMeet', function () {

    let id = $(this).data('id');
    let btn = $(this);

    btn.prop('disabled', true);

    $.ajax({
        url: "/join-meet",
        type: "POST",
        data: {
            id: id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {

            if(res.success){
                // open meeting AFTER tracking
                window.open(res.link, '_blank');
            } else {
                alert(res.message || 'Something went wrong');
            }

            btn.prop('disabled', false);
        },
        error: function () {
            alert("Server error");
            btn.prop('disabled', false);
        }
    });

});
</script>
@endpush