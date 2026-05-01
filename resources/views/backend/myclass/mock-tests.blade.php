@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Mock Tests - ' . $batch->name . ' | '.app_name())

@section('content')

<div class="card">
  <div class="card-header">
    <h3 class="page-title float-left mb-0">Available Mock Tests - {{ $batch->name }}</h3>
    <div class="float-right">
      @if(auth()->user()->hasRole('administrator'))
        <a href="{{ route('admin.batch') }}" class="btn btn-secondary">
          <i class="fa fa-arrow-left"></i> Back to Batches
        </a> 
      @else
        <a href="{{ route('admin.myclass') }}" class="btn btn-secondary">
          <i class="fa fa-arrow-left"></i> Back to Classes
        </a>
      @endif
    </div>
  </div>
  
  <div class="card-body">
    <!-- <div class="alert alert-info mb-3">
      <i class="fa fa-lightbulb-o"></i> <strong>Scheduling Tip:</strong> Set a date and time for a mock test, and it will automatically appear to students at that time. No manual activation needed!
    </div> -->
    
    @if(count($mockTests) > 0)
      @php
        $today = \Carbon\Carbon::now()->startOfDay();
        $statusCounts = [
          'all' => 0,
          'scheduled' => 0,
          'pending' => 0,
          'not_scheduled' => 0,
        ];
      @endphp

      @foreach($mockTests as $mock)
        @php
          $isPending = isset($mock->is_pending) && $mock->is_pending;
          $scheduledAt = $mock->scheduled_at ? \Carbon\Carbon::parse($mock->scheduled_at)->startOfDay() : null;
          $isScheduled = !$isPending && ($mock->is_active == 1 || $scheduledAt);
          $statusKey = $isPending ? 'pending' : ($isScheduled ? 'scheduled' : 'not_scheduled');
          $statusCounts['all']++;
          $statusCounts[$statusKey]++;
        @endphp
      @endforeach

      <div class="mock-status-filters mb-3">
        <button type="button" class="btn btn-outline-secondary btn-sm mock-status-filter-btn active" data-status="all">
          All <span class="badge badge-light ml-1">{{ $statusCounts['all'] }}</span>
        </button>
        <button type="button" class="btn btn-outline-success btn-sm mock-status-filter-btn ml-1" data-status="scheduled">
          Scheduled <span class="badge badge-light ml-1">{{ $statusCounts['scheduled'] }}</span>
        </button>
        <button type="button" class="btn btn-outline-warning btn-sm mock-status-filter-btn ml-1" data-status="pending">
          Pending <span class="badge badge-light ml-1">{{ $statusCounts['pending'] }}</span>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm mock-status-filter-btn ml-1" data-status="not_scheduled">
          Not Scheduled <span class="badge badge-light ml-1">{{ $statusCounts['not_scheduled'] }}</span>
        </button>
      </div>

      <div id="mock-filter-empty" class="alert alert-info mb-3" style="display:none;">
        <i class="fa fa-info-circle"></i> No mock tests match the selected filter.
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Mock Test Name</th>
              <th style="width: 150px;">Status</th>
              <th style="width: 250px;">Scheduled Date</th>
              <th style="width: 350px;">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mockTests as $mock)
            @php
              $isPending = isset($mock->is_pending) && $mock->is_pending;
              $scheduledAt = $mock->scheduled_at ? \Carbon\Carbon::parse($mock->scheduled_at)->startOfDay() : null;
              $today = \Carbon\Carbon::now()->startOfDay();
              $isPastSchedule = $scheduledAt && $scheduledAt->lte($today);
              $isScheduled = !$isPending && ($mock->is_active == 1 || $scheduledAt);
              $statusKey = $isPending ? 'pending' : ($isScheduled ? 'scheduled' : 'not_scheduled');
            @endphp
            <tr data-mock-id="{{ $mock->id }}" data-status="{{ $statusKey }}">
              <td>
                {{ $mock->name }}
                @if($isPending)
                  <br><small class="text-muted"><i class="fa fa-info-circle"></i> {{ $mock->pending_reason ?? 'Progress requirements not met' }}</small>
                @endif
              </td>
              <td>
                @if($isPending)
                  <span class="badge badge-warning" title="{{ $mock->pending_reason ?? 'Progress requirements not met' }}">
                    <i class="fa fa-clock"></i> Pending (Progress)
                  </span>
                @elseif($mock->is_active == 1)
                  <span class="badge badge-success">Scheduled</span>
                  <br><small class="text-muted">(visible to students)</small>
                @elseif($scheduledAt && $isPastSchedule)
                  <span class="badge badge-success">Scheduled</span>
                  <br><small class="text-muted">(visible to students)</small>
                @elseif($scheduledAt && $scheduledAt->gt($today))
                  <span class="badge badge-warning">Scheduled</span>
                  <br><small class="text-muted">(will auto-show)</small>
                @else
                  <span class="badge badge-secondary">Not Scheduled</span>
                @endif
              </td>
              <td>
                <div class="schedule-datetime-wrapper">
                  @if($mock->scheduled_at)
                    <div class="scheduled-display">
                      <strong>{{ \Carbon\Carbon::parse($mock->scheduled_at)->format('M d, Y') }}</strong>
                    </div>
                  @else
                    <small class="text-muted">Not scheduled</small>
                  @endif
                </div>
              </td>
              <td>
                @if(!$isPending)
                  <a href="{{ route('admin.myclass.mockTestQuestions', $mock->id) }}?batch_id={{ $batch->id }}"
                     class="btn btn-sm btn-info"
                     target="_blank">
                    <i class="fa fa-eye"></i> Preview Questions
                  </a>
                @else
                  <button class="btn btn-sm btn-secondary" disabled title="{{ $mock->pending_reason ?? 'Progress requirements not met' }}">
                    <i class="fa fa-eye"></i> Preview Questions
                  </button>
                @endif

               
                  @if($isPending)
                    <button class="btn btn-sm btn-primary ml-1" disabled title="{{ $mock->pending_reason ?? 'Progress requirements not met' }}">
                      <i class="fa fa-users"></i> View as result
                    </button>
                  @else
                    <button class="btn btn-sm btn-primary ml-1 view-attempts-btn"
                            data-mock-id="{{ $mock->id }}"
                            data-mock-name="{{ $mock->name }}">
                      <i class="fa fa-users"></i> View as result
                    </button>
                  @endif
               
                
                @if($mock->is_active && !$isPending)
                  <button class="btn btn-sm btn-warning ml-1 deactivate-mock-btn" 
                          data-id="{{ $mock->id }}" 
                          data-batch="{{ $batch->id }}">
                    <i class="fa fa-ban"></i> Cancel Schedule
                  </button>
                @elseif($mock->scheduled_at && !$isPending)
                  <button class="btn btn-sm btn-warning ml-1 cancel-schedule-btn" 
                          data-id="{{ $mock->id }}" 
                          data-batch="{{ $batch->id }}"
                          data-date="{{ \Carbon\Carbon::parse($mock->scheduled_at)->format('M d, Y') }}">
                    <i class="fa fa-ban"></i> Cancel Schedule
                  </button>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> No mock tests assigned to this batch yet.
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="mockAttemptsModal" tabindex="-1" role="dialog" aria-labelledby="mockAttemptsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mockAttemptsModalLabel">Students Attempt Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="attempts-loading" class="text-center py-4" style="display:none;">
          <i class="fa fa-spinner fa-spin fa-2x"></i>
          <p class="mt-2 mb-0">Loading...</p>
        </div>
        <div id="attempts-summary" class="mb-3" style="display:none;">
          <span class="badge badge-info mr-2" id="summary-total">Total: 0</span>
          <span class="badge badge-success mr-2" id="summary-attempted">Attempted: 0</span>
          <span class="badge badge-secondary" id="summary-not-attempted">Not Attempted: 0</span>
        </div>
        <div class="table-responsive" id="attempts-table-wrapper" style="display:none;">
          <table class="table table-bordered table-striped mb-0">
            <thead>
              <tr>
                <th style="width: 40px;">#</th>
                <th>Student</th>
                <th style="width: 160px;">Status</th>
                <th style="width: 160px;">Attempted On</th>
                <th style="width: 140px;">Action</th>
              </tr>
            </thead>
            <tbody id="attempts-table-body"></tbody>
          </table>
        </div>
        <div id="attempts-empty" class="alert alert-warning mb-0" style="display:none;">
          <i class="fa fa-info-circle"></i> No students assigned to this batch.
        </div>
        <div id="attempts-error" class="alert alert-danger mb-0" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@stop

@push('after-scripts')
<script>
$(document).ready(function() {
    function applyMockFilter(status) {
        const selectedStatus = status || 'all';
        let visibleCount = 0;

        $('.mock-status-filter-btn').removeClass('active');
        $('.mock-status-filter-btn[data-status="' + selectedStatus + '"]').addClass('active');

        $('tr[data-status]').each(function() {
            const rowStatus = $(this).data('status');
            const matches = selectedStatus === 'all' || rowStatus === selectedStatus;
            $(this).toggle(matches);
            if (matches) {
                visibleCount++;
            }
        });

        $('#mock-filter-empty').toggle(visibleCount === 0);
    }

    applyMockFilter('all');

    $(document).on('click', '.mock-status-filter-btn', function() {
        applyMockFilter($(this).data('status'));
    });

    // View attempts for a mock test
    $(document).on('click', '.view-attempts-btn', function() {
        const mockId = $(this).data('mock-id');
        const mockName = $(this).data('mock-name');
        const batchId = {{ $batch->id }};

        $('#mockAttemptsModalLabel').text('Attempt Status - ' + mockName);
        $('#attempts-summary').hide();
        $('#attempts-table-wrapper').hide();
        $('#attempts-table-body').empty();
        $('#attempts-empty').hide();
        $('#attempts-error').hide().text('');
        $('#attempts-loading').show();

        $('#mockAttemptsModal').modal('show');

        $.ajax({
            url: '/user/myclass/' + batchId + '/mock-attempt-status/' + mockId,
            type: 'GET',
            success: function(response) {
                $('#attempts-loading').hide();

                if (!response.success) {
                    $('#attempts-error').text(response.message || 'Failed to load data').show();
                    return;
                }

                $('#summary-total').text('Total: ' + response.total_students);
                $('#summary-attempted').text('Attempted: ' + response.attempted_count);
                $('#summary-not-attempted').text('Not Attempted: ' + response.not_attempted_count);
                $('#attempts-summary').show();

                if (!response.students || response.students.length === 0) {
                    $('#attempts-empty').show();
                    return;
                }

                const tbody = $('#attempts-table-body');
                response.students.forEach(function(student, index) {
                    const statusBadge = student.attempted
                        ? '<span class="badge badge-success">Attempted</span>'
                        : '<span class="badge badge-secondary">Not Attempted</span>';

                    const attemptedAt = student.attempted_at || '-';

                    let actionHtml = '<span class="text-muted">—</span>';
                    if (student.attempted) {
                        const resultUrl = '{{ route('admin.myclass.mockResults', ['batch_id' => $batch->id]) }}'
                            + '?student_id=' + student.id + '&mock_id=' + mockId;
                        actionHtml = '<a href="' + resultUrl + '" target="_blank" class="btn btn-sm btn-success">'
                            + '<i class="fa fa-eye"></i> View Result</a>';
                    }

                    tbody.append(
                        '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td><strong>' + $('<div/>').text(student.name).html() + '</strong>' +
                                '<br><small class="text-muted">' + $('<div/>').text(student.email).html() + '</small></td>' +
                            '<td>' + statusBadge + '</td>' +
                            '<td>' + attemptedAt + '</td>' +
                            '<td>' + actionHtml + '</td>' +
                        '</tr>'
                    );
                });
                $('#attempts-table-wrapper').show();
            },
            error: function(xhr) {
                $('#attempts-loading').hide();
                const msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Error loading attempt status. Please try again.';
                $('#attempts-error').text(msg).show();
            }
        });
    });

    // Deactivate mock test
    $(document).on('click', '.deactivate-mock-btn', function() {
        const mockId = $(this).data('id');
        const batchId = $(this).data('batch');
        const button = $(this);
        
        if(!confirm('Are you sure you want to deactivate this mock test?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deactivating...');
        
        $.ajax({
            url: '{{ route("admin.myclass.toggleMockStatus") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mock_id: mockId,
                status: 0,
                batch_id: batchId
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    button.prop('disabled', false).html('<i class="fa fa-times"></i> Deactivate');
                }
            },
            error: function(xhr) {
                console.error('Deactivate Error:', xhr.responseText);
                alert('Error deactivating mock test. Please try again.');
                button.prop('disabled', false).html('<i class="fa fa-times"></i> Deactivate');
            }
        });
    });
    
    // Cancel scheduled mock test
    $(document).on('click', '.cancel-schedule-btn', function() {
        const mockId = $(this).data('id');
        const batchId = $(this).data('batch');
        const scheduleDate = $(this).data('date');
        const button = $(this);
        
        if(!confirm('Are you sure you want to cancel the scheduled mock test for ' + scheduleDate + '?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Canceling...');
        
        $.ajax({
            url: '{{ route("admin.myclass.scheduleMock") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mock_id: mockId,
                batch_id: batchId,
                scheduled_at: null
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    button.prop('disabled', false).html('<i class="fa fa-ban"></i> Cancel Schedule');
                }
            },
            error: function(xhr) {
                console.error('Cancel Schedule Error:', xhr.responseText);
                alert('Error canceling schedule. Please try again.');
                button.prop('disabled', false).html('<i class="fa fa-ban"></i> Cancel Schedule');
            }
        });
    });
});
</script>

<style>
.schedule-datetime-wrapper {
    min-height: 40px;
}

.schedule-input-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.schedule-date-input {
    max-width: 180px;
}

.scheduled-display {
    display: flex;
    align-items: center;
}

.clear-schedule-btn {
    padding: 0 5px;
    font-size: 14px;
}

.badge {
    font-size: 0.9em;
    padding: 5px 10px;
}
</style>
@endpush
