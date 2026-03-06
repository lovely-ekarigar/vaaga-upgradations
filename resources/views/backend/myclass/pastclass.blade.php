@extends('frontend.layout.sub-master')
@section('title')
<title>Class History for {{$batch->name}} | {{env('APP_NAME')}}</title>
@stop
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css"  />
<style>
    .badge-delivered {
        background-color: #1cc88a;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    .duration-valid {
        color: #1cc88a;
        font-weight: 600;
    }
    .duration-invalid {
        color: #e74a3b;
        font-weight: 600;
    }
    .btn-objection {
        padding: 3px 8px;
        font-size: 12px;
    }
    .objection-info {
        margin-top: 5px;
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        font-size: 12px;
    }
    .multiple-classes {
        margin-top: 5px;
        font-size: 0.85em;
        color: #6c757d;
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
            <h6 class="text-body fw-500 mb-3">Class History for {{$batch->name}}</h6>
            
            @php
                use Carbon\Carbon;
                // Show all recordings except those with invalid 1970 dates
                // This matches the OLD code behavior
                $filteredList = $list->filter(function ($rec) {
                    // Only filter out 1970 dates (like OLD code did)
                    if (!empty($rec->recording_date) && strpos($rec->recording_date, '1970') !== false) {
                        return false;
                    }
                    return true;
                });
                
                // Group by date - use recording_date if available, otherwise use created_at
                $groupedClasses = $filteredList->groupBy(function($item) {
                    if (!empty($item->recording_date) && strpos($item->recording_date, '1970') === false) {
                        return Carbon::parse($item->recording_date)->format('Y-m-d');
                    }
                    // Fallback to created_at date if recording_date is null
                    return Carbon::parse($item->created_at)->format('Y-m-d');
                })->sortKeysDesc();
            @endphp
            
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap" id="classHistoryTable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 0 @endphp
                        @forelse($groupedClasses as $date => $classes)
                            @php 
                                $index++;
                            @endphp
                            <tr>
                                <td rowspan="{{ $classes->count() }}">{{ $index }}</td>
                                <td rowspan="{{ $classes->count() }}" style="width: 140px;">
                                    {{ date('d M Y', strtotime($date)) }}
                                    @if($classes->count() > 1)
                                        <div class="multiple-classes">({{ $classes->count() }} sessions)</div>
                                    @endif
                                </td>
                                
                                @foreach($classes as $class)
                                    @php
                                        // Determine display date
                                        $displayDate = !empty($class->recording_date) && strpos($class->recording_date, '1970') === false
                                            ? $class->recording_date 
                                            : $class->created_at;
                                        
                                        // Calculate duration from start_time and end_time (stored in milliseconds)
                                        $duration = 'N/A';
                                        if (isset($class->start_time) && isset($class->end_time) && 
                                            is_numeric($class->start_time) && is_numeric($class->end_time) &&
                                            $class->end_time > $class->start_time) {
                                            $lengthMins = ($class->end_time - $class->start_time) / (1000 * 60);
                                            if ($lengthMins > 0) {
                                                $duration = round($lengthMins) . ' mins';
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!$loop->first)
                                        <tr>
                                    @endif
                                    
                                    <td><span class="badge-delivered">Delivered</span></td>
                                    <td class="duration-valid">{{ $duration }}</td>
                                    <td>
                                        @if(!empty($class->internal_id))
                                            <a href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{$class->internal_id}}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-primary mb-1">
                                                <i class="fas fa-play-circle"></i> Watch Recording
                                            </a>
                                        @else
                                            <span class="text-muted">Recording not available</span>
                                        @endif
                                        
                                        @if($class->objection)
                                            <div class="objection-info">
                                                <span class="badge 
                                                    @if($class->objection->status == 'accepted') bg-success
                                                    @elseif($class->objection->status == 'rejected') bg-danger
                                                    @else bg-warning @endif">
                                                    Objection {{ ucfirst($class->objection->status) }}
                                                </span>
                                                <p class="mb-0 mt-1"><strong>Your Objection:</strong> {{$class->objection->reason}}</p>
                                                @if($class->objection->admin_reason)
                                                    <p class="mb-0"><strong>Admin Remarks:</strong> {{$class->objection->admin_reason}}</p>
                                                @endif
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-danger btn-objection mt-1"
                                                    data-id="{{ $class->id }}"
                                                    data-date="{{ date('d M Y', strtotime($displayDate)) }}"
                                                    data-duration="{{ $duration }}">
                                                <i class="fas fa-exclamation-circle"></i> Raise Objection
                                            </button>
                                        @endif
                                    </td>
                                    
                                    @if(!$loop->last)
                                        </tr>
                                    @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                        No class recordings found for this batch.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
<!-- End Section -->
</main>

<!-- Objection Modal -->
<div class="modal fade" id="objectionModal" tabindex="-1" role="dialog" aria-labelledby="objectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="objectionForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="objectionModalLabel">Raise Objection</h5>
          <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><strong>Date:</strong> <span id="modalDate"></span></p>
          <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
          <div class="form-group mb-3">
            <label for="objectionReason" class="form-label">Objection Reason</label>
            <textarea class="form-control" id="objectionReason" name="reason" rows="4" required placeholder="Please describe your objection..."></textarea>
          </div>
          <input type="hidden" id="modalRecordingId" name="recording_id" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Submit Objection</button>
        </div>
      </form>
    </div>
  </div>
</div>

@stop

@section('page_js')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Wait for DOM and scripts to be fully loaded
window.addEventListener('load', function() {
    // Initialize DataTable
    if ($.fn.DataTable) {
        $("#classHistoryTable").DataTable({
            "pageLength": 25,
            "ordering": false,
            "searching": true,
            "info": true
        });
    }
    
    // Open modal and populate objection info
    $(document).on('click', '.btn-objection', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        const recordingId = $(this).data('id');
        const date = $(this).data('date');
        const duration = $(this).data('duration');

        $('#modalDate').text(date || 'N/A');
        $('#modalDuration').text(duration || 'N/A');
        $('#modalRecordingId').val(recordingId);
        $('#objectionReason').val('');
        
        // Show modal using jQuery (works with both BS4 and BS5)
        try {
            $('#objectionModal').modal('show');
        } catch (err) {
            console.error('Modal error:', err);
            // Fallback: manually show modal
            $('#objectionModal').addClass('show').css('display', 'block').css('opacity', '1');
            $('body').addClass('modal-open');
            $('.modal-backdrop').remove();
            $('<div class="modal-backdrop fade show"></div>').appendTo('body');
        }
    });

    // Close modal handlers
    $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .modal .btn-close, .modal .close', function() {
        try {
            $('#objectionModal').modal('hide');
        } catch (err) {
            // Fallback: manually hide modal
            $('#objectionModal').removeClass('show').css('display', 'none').css('opacity', '');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    });

    // Submit objection form with AJAX
    $('#objectionForm').submit(function (e) {
        e.preventDefault();

        const recordingId = $('#modalRecordingId').val();
        const reason = $('#objectionReason').val().trim();

        if (!reason) {
            Swal.fire({
                icon: 'warning',
                title: 'Reason Required',
                text: 'Please enter a reason for raising an objection.',
                confirmButtonColor: '#d33'
            });
            return;
        }

        $.ajax({
            url: '{{ route("myclass.commitment.objection") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                recording_id: recordingId,
                reason: reason
            },
            success: function (response) {
                // Hide modal
                try {
                    $('#objectionModal').modal('hide');
                } catch (err) {
                    $('#objectionModal').removeClass('show').css('display', 'none');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Objection Submitted',
                    text: 'Our team will review it shortly.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                let errorMsg = 'Something went wrong. Please try again later.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: errorMsg,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Close'
                });
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
@stop
