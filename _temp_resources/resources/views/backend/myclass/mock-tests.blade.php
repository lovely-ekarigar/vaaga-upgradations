@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Mock Tests - ' . $batch->name . ' | '.app_name())

@section('content')

<div class="card">
  <div class="card-header">
    <h3 class="page-title float-left mb-0">Available Mock Tests - {{ $batch->name }}</h3>
    <div class="float-right">
      <a href="{{ route('admin.myclass') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Classes
      </a>
    </div>
  </div>
  
  <div class="card-body">
    <!-- <div class="alert alert-info mb-3">
      <i class="fa fa-lightbulb-o"></i> <strong>Scheduling Tip:</strong> Set a date and time for a mock test, and it will automatically appear to students at that time. No manual activation needed!
    </div> -->
    
    @if(count($mockTests) > 0)
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
            <tr data-mock-id="{{ $mock->id }}">
              <td>{{ $mock->name }}</td>
              <td>
                @php
                  $scheduledAt = $mock->scheduled_at ? \Carbon\Carbon::parse($mock->scheduled_at)->startOfDay() : null;
                  $today = \Carbon\Carbon::now()->startOfDay();
                  $isPastSchedule = $scheduledAt && $scheduledAt->lte($today);
                @endphp
                @if($mock->is_active == 1)
                  <span class="badge badge-success">Manually Active</span>
                @elseif($scheduledAt && $isPastSchedule)
                  <span class="badge badge-success">Auto-Active</span>
                  <br><small class="text-muted">(visible to students)</small>
                @elseif($scheduledAt && $scheduledAt->gt($today))
                  <span class="badge badge-warning">Scheduled</span>
                  <br><small class="text-muted">(will auto-show)</small>
                @else
                  <span class="badge badge-secondary">Inactive</span>
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
                <a href="{{ route('admin.myclass.mockTestQuestions', $mock->id) }}?batch_id={{ $batch->id }}" 
                   class="btn btn-sm btn-info" 
                   target="_blank">
                  <i class="fa fa-eye"></i> Preview Questions
                </a>
                
                @if($mock->is_active)
                  <button class="btn btn-sm btn-danger ml-1 deactivate-mock-btn" 
                          data-id="{{ $mock->id }}" 
                          data-batch="{{ $batch->id }}">
                    <i class="fa fa-times"></i> Deactivate
                  </button>
                @elseif($mock->scheduled_at)
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

@stop

@push('after-scripts')
<script>
$(document).ready(function() {
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
