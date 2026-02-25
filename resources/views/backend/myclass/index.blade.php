<?php
use App\Models\Batch;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Class list'.' | '.app_name())

@section('content')

 
<div class="card">
  <div class="card-header">
    <h3 class="page-title float-left mb-0">My Classes</h3>

  </div>
  <div class="card-body">
    <div class="table-responsive">

      <table id="myTable" class="table table-bordered table-striped dt-select ">
        <thead>
          <tr>

            <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all" />
            </th>

            <th>@lang('labels.general.sr_no')</th>

            <th>Batch Name</th>
            <th>Course Name</th>
            <th>Batch Fees</th>
            <th>Completion</th>
            <th>Batch Duration</th>
            <th>Batch class time</th>
            <th>Batch Repeat</th>

            <th>&nbsp; @lang('strings.backend.general.actions')</th>

          </tr>
        </thead>

        <tbody>
          <?php $count=0; ?>
          @foreach($list as $l)

          <?php $count++; ?>
          <tr data-entry-id="1" role="row" class="odd">
            <td class="text-center">
              <input type="checkbox" class="single" name="id[]" value="1">
            </td>

            <td>
              <?=$count?>
            </td>
            <td>{{$l->name}}</td>

            <td>{{$l->course->title}}</td>
            <td>{{$l->fees}}</td>
            <td>
              <?php
          $lo = new Batch;
          $ar = $lo->bacthCompletion($l->id,Auth::user()->id);
           ?>

              {{$ar['completed']}}/{{$ar['total']}}
              @if($ar['total']!=0)
              ({{floor($ar['completed']*100/$ar['total'])}}%)
              @endif
            </td>
            <td>{{$l->start_date}} - {{$l->end_date}}</td>
            <td>{{$l->start_time}} - {{$l->end_time}}</td>
            <td>
              <?php
         $days=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
         $wdays=json_decode($l->occur,true);
         foreach($wdays as $wd){
          echo $days[$wd].", ";

         }

         ?>
            </td>
            <td>
              @if($l->active=='1')
              <a href="<?php echo route('admin.myclass.details', ['id' => $l->id]); ?>"
                class="btn btn-xs btn-primary mb-1"><i class="icon-eye"></i></a>

              <a href="<?php echo route('admin.myclass.upload', ['id' => $l->id]); ?>"
                class="btn btn-xs btn-info mb-1"><i class="icon-cloud-upload"></i></a>

              <a href="<?php echo route('admin.myclass.attendance', ['id' => $l->id]); ?>"
                class="btn btn-xs btn-info mb-1"><i class="fa fa-users" aria-hidden="true"></i>

                <a href="{{route('admin.myclass.assignment',['id'=>$l->id])}}"
                  class="btn btn-outline-info mb-1">Assignment</a>
                <a href="{{route('admin.myclass.exam',['id'=>$l->id])}}" class="btn btn-outline-info mb-1">Subjective
                  Exam</a>

                <a href="/user/batch-progress-list-teacher/{{$l->id}}" class="btn btn-outline-info mb-1">Progress</a>
                {{-- <a href="/user/batch/batch-progress-list/{{$l->id}}" class="btn btn-outline-info mb-1">Progress</a>
                --}}

                <a href="{{ route('admin.myclass.mockTestsPage', $l->id) }}" class="btn btn-outline-success mb-1">
                  <i class="fa fa-file-text"></i> Available Mock Tests
                </a> 

                <a href="{{ route('admin.myclass.mockResults', $l->id) }}" class="btn btn-outline-info mb-1">
                  <i class="fa fa-chart-bar"></i> Mock Test Results
                </a>

                <a href="javascript:void(0);" class="btn btn-xs btn-warning mb-1 suspend-btn" data-id="{{ $l->id }}">
                  <i class="fa fa-ban"></i> Suspend
                </a>

              </a>

              @else

              <p>This batch no longer belongs to you.</p>
              @endif


            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Class Suspension Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" role="dialog" aria-labelledby="suspendModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('admin.myclass.suspend') }}">
      @csrf
      <input type="hidden" name="batch_id" id="suspend_batch_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="suspendModalLabel">Suspend Class</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="suspend_date">Select Date</label>
            <input type="date" name="suspend_date" id="suspend_date" class="form-control" min="{{date('Y-m-d')}}"
              required>
          </div>
          <p class="text-muted">This will mark the selected date as suspended for the class.</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Suspension</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Mock Tests Modal -->
<div class="modal fade" id="mockTestModal" tabindex="-1" role="dialog" aria-labelledby="mockTestModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mockTestModalLabel">Available Mock Tests - <span id="batch_name"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div id="mock_test_loader" class="text-center" style="display: none;">
          <i class="fa fa-spinner fa-spin fa-3x"></i>
          <p>Loading mock tests...</p>
        </div>
        <div id="mock_test_content">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Mock Test Name</th>
                <th style="width: 150px;">Status</th>
                <th style="width: 180px;">Scheduled Date</th>
                <th style="width: 280px;">Action</th>
              </tr>
            </thead>
            <tbody id="mock_test_list">
            </tbody>
          </table>
        </div>
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
  $(document).ready(function () {
    $('#myTable').DataTable();

    // Open modal with correct batch_id
    $(document).on('click', '.suspend-btn', function () {
        var batchId = $(this).data('id');
        $('#suspend_batch_id').val(batchId);
        $('#suspendModal').modal('show');
    });

    // Open mock test modal
    $(document).on('click', '.mock-test-btn', function () {
        var batchId = $(this).data('id');
        var batchName = $(this).data('name');
        console.log('Mock test button clicked. Batch ID:', batchId, 'Batch Name:', batchName);
        $('#batch_name').text(batchName);
        $('#mockTestModal').modal('show');
        loadMockTests(batchId);
    });

    // Function to load mock tests
    function loadMockTests(batchId) {
        console.log('Loading mock tests for batch ID:', batchId);
        var ajaxUrl = '{{ route("admin.myclass.getMockTests") }}';
        console.log('AJAX URL:', ajaxUrl);
        
        $('#mock_test_loader').show();
        $('#mock_test_content').hide();
        
        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: { batch_id: batchId },
            success: function(response) {
                console.log('Response received:', response);
                $('#mock_test_loader').hide();
                $('#mock_test_content').show();
                
                var html = '';
                if(response.success && response.data.length > 0) {
                    $.each(response.data, function(index, mock) {
                        var isActive = mock.is_active == 1;
                        var hasSchedule = mock.scheduled_at && mock.scheduled_at !== null;
                        var isPending = mock.is_pending == 1 || mock.is_pending === true;
                        var pendingReason = mock.pending_reason || 'Progress requirements not met';
                        
                        // Date-only comparison (ignoring time)
                        var scheduledDate = hasSchedule ? new Date(mock.scheduled_at) : null;
                        var today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (scheduledDate) {
                            scheduledDate.setHours(0, 0, 0, 0);
                        }
                        var isPastSchedule = hasSchedule && scheduledDate <= today;
                        
                        // Status badge with pending priority
                        var statusBadge = isPending 
                            ? '<span class="badge badge-warning" title="' + pendingReason + '"><i class="fa fa-clock"></i> Pending (Progress)</span>'
                            : (isActive 
                                ? '<span class="badge badge-success">Active</span>' 
                                : (hasSchedule && !isPastSchedule 
                                    ? '<span class="badge badge-warning">Scheduled</span>'
                                    : '<span class="badge badge-secondary">Inactive</span>'));
                        
                        var scheduledDateText = hasSchedule 
                            ? '<small>' + new Date(mock.scheduled_at).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'}) + '</small>'
                            : '<small class="text-muted">Not scheduled</small>';
                        
                        var previewButton = '<a href="/user/myclass/mock-test-questions/' + mock.id + '?batch_id=' + batchId + '" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Preview</a>';
                        
                        // Disable schedule and submit buttons if pending
                        var scheduleButton = !isActive && !isPending
                            ? '<button class="btn btn-sm btn-primary ml-1 schedule-mock-btn" data-id="' + mock.id + '" data-batch="' + batchId + '" data-name="' + mock.name + '"><i class="fa fa-calendar"></i> Schedule</button>'
                            : (!isActive && isPending 
                                ? '<button class="btn btn-sm btn-secondary ml-1" disabled title="' + pendingReason + '"><i class="fa fa-calendar"></i> Schedule</button>'
                                : '');
                        
                        var actionButton = !isActive && !isPending
                            ? '<button class="btn btn-sm btn-success ml-1 submit-mock-btn" data-id="' + mock.id + '" data-batch="' + batchId + '"><i class="fa fa-check"></i> Submit Now</button>'
                            : (!isActive && isPending
                                ? '<button class="btn btn-sm btn-secondary ml-1" disabled title="' + pendingReason + '"><i class="fa fa-check"></i> Submit Now</button>'
                                : (isActive 
                                    ? '<button class="btn btn-sm btn-danger ml-1 inactive-mock-btn" data-id="' + mock.id + '" data-batch="' + batchId + '"><i class="fa fa-times"></i> Inactive</button>'
                                    : ''));
                        
                        html += '<tr>' +
                                '<td>' + mock.name + (isPending ? ' <small class="text-muted">(' + pendingReason + ')</small>' : '') + '</td>' +
                                '<td>' + statusBadge + '</td>' +
                                '<td>' + scheduledDateText + '</td>' +
                                '<td>' + previewButton + scheduleButton + actionButton + '</td>' +
                                '</tr>';
                    });
                } else {
                    console.log('No mock tests found or empty response');
                    html = '<tr><td colspan="4" class="text-center">No mock tests assigned to this batch.</td></tr>';
                }
                
                $('#mock_test_list').html(html);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);
                $('#mock_test_loader').hide();
                $('#mock_test_content').show();
                $('#mock_test_list').html('<tr><td colspan="3" class="text-center text-danger">Error loading mock tests. Please check console for details.</td></tr>');
            }
        });
    }

    // Submit mock test (activate)
    $(document).on('click', '.submit-mock-btn', function() {
        var mockId = $(this).data('id');
        var batchId = $(this).data('batch');
        var button = $(this);
        
        if(!confirm('Are you sure you want to submit and activate this mock test?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
        
        $.ajax({
            url: '{{ route("admin.myclass.toggleMockStatus") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mock_id: mockId,
                status: 1,
                batch_id: batchId
            },
            success: function(response) {
                if(response.success) {
                    loadMockTests(batchId);
                } else {
                    alert('Error: ' + response.message);
                    button.prop('disabled', false).html('<i class="fa fa-check"></i> Submit');
                }
            },
            error: function(xhr, status, error) {
                console.error('Submit Error:', xhr.responseText);
                alert('Error submitting mock test. Please try again.');
                button.prop('disabled', false).html('<i class="fa fa-check"></i> Submit');
            }
        });
    });

    // Inactive mock test (deactivate)
    $(document).on('click', '.inactive-mock-btn', function() {
        var mockId = $(this).data('id');
        var batchId = $(this).data('batch');
        var button = $(this);
        
        if(!confirm('Are you sure you want to make this mock test inactive?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        
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
                    loadMockTests(batchId);
                } else {
                    alert('Error: ' + response.message);
                    button.prop('disabled', false).html('<i class="fa fa-times"></i> Inactive');
                }
            },
            error: function(xhr, status, error) {
                console.error('Inactive Error:', xhr.responseText);
                alert('Error making mock test inactive. Please try again.');
                button.prop('disabled', false).html('<i class="fa fa-times"></i> Inactive');
            }
        });
    });

    // Schedule mock test
    $(document).on('click', '.schedule-mock-btn', function() {
        var mockId = $(this).data('id');
        var batchId = $(this).data('batch');
        var mockName = $(this).data('name');
        
        var scheduleDate = prompt('Enter date to schedule "' + mockName + '" (Format: YYYY-MM-DD):\\n\\nExample: 2026-02-15\\n\\nNote: Mock will be visible to students when their local date reaches this date.');
        
        if(!scheduleDate) {
            return;
        }
        
        // Validate date format
        var datePattern = /^\d{4}-\d{2}-\d{2}$/;
        if(!datePattern.test(scheduleDate)) {
            alert('Invalid date format. Please use YYYY-MM-DD format.');
            return;
        }
        
        // Check if date is today or in the future
        var scheduledDate = new Date(scheduleDate + 'T00:00:00');
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if(scheduledDate < today) {
            alert('Scheduled date must be today or in the future.');
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.myclass.scheduleMock") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mock_id: mockId,
                batch_id: batchId,
                scheduled_at: scheduleDate + ' 00:00:00'
            },
            success: function(response) {
                if(response.success) {
                    alert('Mock test scheduled successfully for ' + scheduleDate);
                    loadMockTests(batchId);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Schedule Error:', xhr.responseText);
                alert('Error scheduling mock test. Please try again.');
            }
        });
    });
});
</script>

<style>
/* Toggle Switch CSS */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
}

input:checked + .slider {
  background-color: #28a745;
}

input:focus + .slider {
  box-shadow: 0 0 1px #28a745;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}

input:disabled + .slider {
  cursor: not-allowed;
  opacity: 0.6;
}
</style>

@endpush