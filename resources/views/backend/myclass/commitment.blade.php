<?php use Carbon\Carbon; ?>
@extends('frontend.layout.sub-master')
@section('title')
<title>Course Progress {{$batch->name}} | {{env('APP_NAME')}}</title>
@stop
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css" />
<style> 
    .progress-container {
        margin-bottom: 30px;
    }
    .progress-title {
        font-weight: 600;
        margin-bottom: 10px;
    }
    .progress {
        height: 20px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    .progress-bar {
        background-color: #4e73df;
    }
    .stats-card {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .stats-card.primary {
        background-color: #f8f9fa;
        border-left: 4px solid #4e73df;
    }
    .stats-card.success {
        background-color: #f8f9fa;
        border-left: 4px solid #1cc88a;
    }
    .stats-card.warning {
        background-color: #f8f9fa;
        border-left: 4px solid #f6c23e;
    }
    .stats-value {
        font-size: 24px;
        font-weight: 700;
    }
    .stats-label {
        font-size: 14px;
        color: #5a5c69;
    }
    .class-history {
        margin-top: 30px;
    }
    .badge-delivered {
        background-color: #1cc88a;
    }
     .badge-assigned {
        background-color: #50b5ff;
    }
    .badge-pending {
        background-color: #f6c23e;
    }
    .badge-primary {
        background-color: #3f51b5;
    }
    .btn-objection {
        padding: 3px 8px;
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
    .multiple-classes {
        margin-top: 5px;
        font-size: 0.9em;
    }
    .recording-link {
        margin-right: 5px;
        margin-bottom: 5px;
    }
</style>

@include("frontend.include.user-menu")
</div> 
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            <h3 class="mb-4">@lang('strings.backend.dashboard.welcome') {{ ucwords($logged_in_user->name) }}!</h3>
            <h4 class="text-primary mb-4">Course Progress for {{$batch->name}}</h4>
            
            @php
                // Show all recordings except those with invalid 1970 dates
                $filteredList = $list->filter(function ($class) {
                    // Only filter out 1970 dates (like OLD code did)
                    if (!empty($class->recording_date) && strpos($class->recording_date, '1970') !== false) {
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

                $validClassCount = $groupedClasses->count();
            @endphp


            <div class="row">
                <div class="col-md-6">
                    <div class="stats-card primary">
                        <div class="stats-value">{{ $batch->total_class+$batch->total_test }}</div>
                        <div class="stats-label">Total Session</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card success">
                        <div class="stats-value">{{ $validClassCount + count($test_list) + (isset($mock_list) ? count($mock_list) : 0) }}</div>
                        <div class="stats-label">Session Delivered</div>
                    </div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-title">Session Completion Progress</div>
                <div class="progress">
                    @php
                        $totalSessions = $batch->total_class + $batch->total_test;
                        $deliveredSessions = $validClassCount + count($test_list) + (isset($mock_list) ? count($mock_list) : 0);
                        $sessionProgress = $totalSessions > 0 ? ($deliveredSessions / $totalSessions) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-success" role="progressbar" 
                        style="width: {{ $sessionProgress }}%" 
                        aria-valuenow="{{ $sessionProgress }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                        {{ round($sessionProgress) }}%
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Delivered: {{ $deliveredSessions }}</span>
                    <span>Remaining: {{ $totalSessions - $deliveredSessions }}</span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="stats-card primary">
                        <div class="stats-value">{{ $batch->total_class }}</div>
                        <div class="stats-label">Total Live Classes Committed</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card success">
                        <div class="stats-value">{{ $validClassCount }}</div>
                        <div class="stats-label">Live Classes Delivered</div>
                    </div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-title">Live Class Completion Progress</div>
                <div class="progress">
                    @php
                        $liveClassProgress = $batch->total_class > 0 ? ($validClassCount / $batch->total_class) * 100 : 0;
                    @endphp
                    <div class="progress-bar" role="progressbar" 
                        style="width: {{ $liveClassProgress }}%" 
                        aria-valuenow="{{ $liveClassProgress }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                        {{ round($liveClassProgress) }}%
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Delivered: {{ $validClassCount }}</span>
                    <span>Remaining: {{ $batch->total_class - $validClassCount }}</span>
                </div>
            </div>

            @if($batch->total_test > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="stats-card primary">
                            <div class="stats-value">{{ $batch->total_test }}</div>
                            <div class="stats-label">Total Tests Committed</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card warning">
                            <div class="stats-value">{{count($test_list) + (isset($mock_list) ? count($mock_list) : 0)}}</div>
                            <div class="stats-label">Tests Conducted</div>
                        </div>
                    </div>
                </div>

                <div class="progress-container">
                    <div class="progress-title">Test Completion Progress</div>
                    <div class="progress">
                        @php
                            $totalTestsConducted = count($test_list) + (isset($mock_list) ? count($mock_list) : 0);
                            $testProgress = $batch->total_test > 0 ? ($totalTestsConducted / $batch->total_test) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-warning" role="progressbar" 
                            style="width: {{ $testProgress }}%" 
                            aria-valuenow="{{ $testProgress }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ round($testProgress) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Conducted: {{$totalTestsConducted}}</span>
                        @if($batch->total_test - $totalTestsConducted < 0)
                        <span>Over Commitment: {{ -$batch->total_test + $totalTestsConducted }}</span>
                        @else
                        <span>Remaining: {{ $batch->total_test - $totalTestsConducted }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <div class="class-history">
                <h5 class="mb-3">Live Class Delivery History</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="classHistoryTable">
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
                            @php $index=0 @endphp
                            @foreach($groupedClasses as $date => $classes)
                            @php 
                                $index++;
                                $mainClass = $classes->first();
                                $totalDuration = $classes->sum('length');
                                $hasObjection = $classes->contains(function($class) {
                                    return $class->objection;
                                });
                            @endphp
                          <tr>
    <td rowspan="{{ $classes->count() }}">{{ $index }}</td>
    <td rowspan="{{ $classes->count() }}" style="width:120px;">
        {{ date('d M Y', strtotime($date)) }}
        @if($classes->count() > 1)
            <div class="multiple-classes">({{ $classes->count() }} sessions)</div>
        @endif
    </td>
    
    @foreach($classes as $class)
        @php
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
        
        <td><span class="badge badge-delivered text-white">Delivered</span></td>
        <td class="duration-valid">{{ $duration }}</td>
        <td>
            @if(!empty($class->internal_id))
                <a href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{$class->internal_id}}" 
                   target="_blank" 
                   class="btn btn-sm btn-primary">
                    Watch Recording
                </a>
            @else
                <span class="text-muted">Recording not available</span>
            @endif
            
            @if($class->objection)
                <div class="objection-info mt-2">
                    <span class="badge 
                        @if($class->objection->status == 'accepted') bg-success
                        @elseif($class->objection->status == 'rejected') bg-danger
                        @else bg-warning @endif">
                        Objection {{ ucfirst($class->objection->status) }}
                    </span>
                    <p class="mb-0"><strong>Your Objection:</strong> {{$class->objection->reason}}</p>
                    @if($class->objection->admin_reason)
                     <p class="mb-0"><strong>Admin Remarks:</strong> {{$class->objection->admin_reason}}</p>
                    @endif
                </div>
            @else
                <button class="btn btn-sm btn-danger btn-objection mt-2"
                        data-id="{{ $class->id }}">
                    Raise Objection
                </button>
            @endif
        </td>
        
        @if(!$loop->last)
            </tr>
        @endif
    @endforeach
</tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($batch->total_test > 0 || (isset($mock_list) && count($mock_list) > 0))
            <div class="class-history mt-5">
                <h5 class="mb-3">Test Delivery History</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="testHistoryTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                {{-- <th>Test Name</th> --}}
                                <th>Date</th>
                                <th>Status</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allTests = collect();
                                
                                // Add regular tests
                                foreach($test_list as $test) {
                                    $allTests->push([
                                        'type' => 'regular',
                                        'name' => $test->test ? $test->test->name : 'Test',
                                        'date' => $test->test_date_time,
                                        'duration' => $test->test ? $test->test->duration : 0,
                                        'mytest' => $test->mytest,
                                        'data' => $test
                                    ]);
                                }
                                
                                // Add mock tests
                                if(isset($mock_list)) {
                                    foreach($mock_list as $mock) {
                                        $allTests->push([
                                            'type' => 'mock',
                                            'name' => $mock->name,
                                            'date' => $mock->scheduled_at,
                                            'duration' => $mock->duration,
                                            'mytest' => $mock->myExam,
                                            'data' => $mock
                                        ]);
                                    }
                                }
                                
                                // Sort by date - newest first
                         $allTests = $allTests->sortByDesc(function ($item) {
                        if (empty($item['date'])) {
                            return 0;
                           }
                         try {
                            return \Carbon\Carbon::parse($item['date'])->timestamp;
                              } catch (\Exception $e) {
                             return 0;
                             }
                            })->values();


                            @endphp
                            
                            @foreach($allTests as $index => $test)
                            <tr>
                                <td>{{$index+1}}</td>
                                {{-- <td>
                                    {{ $test['name'] }}
                                    @if($test['type'] == 'mock')
                                        <span class="badge badge-primary text-white ml-2">Mock</span>
                                    @endif
                                </td> --}}
                                <td>{{ $test['date'] ? Carbon::parse($test['date'])->format('M d Y h:i A') : '' }}</td>
                                <td>
                                    @if($test['mytest'])
                                        <span class="badge badge-delivered text-white">Conducted</span>
                                    @else
                                        <span class="badge badge-assigned text-white">Assigned</span>
                                    @endif
                                </td>
                                <td class="duration-valid">{{ $test['duration'] }} mins</td>
                                <td>
                                    @if($test['mytest'])
                                        @if($test['type'] == 'mock')
                                            <a href="{{ route('myMockSeries.result', $test['mytest']->id) }}" 
                                               class="btn btn-sm btn-info action-btn">
                                                <i class="bi bi-file-text"></i> View Result
                                            </a>
                                        @else
                                            <a href="https://exam.vaagaacademy.com/result/{{base64_encode($test['mytest']->id)}}" 
                                               class="btn btn-sm btn-info action-btn" target="_blank">
                                                <i class="bi bi-file-text"></i> Result
                                            </a>
                                        @endif
                                    @else
                                        Not Attempted yet
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
</section>
</main>

<!-- Objection Modal -->
<div class="modal fade" id="objectionModal" tabindex="-1" role="dialog" aria-labelledby="objectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="objectionForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="objectionModalLabel">Raise Objection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><strong>Date:</strong> <span id="modalDate"></span></p>
          <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
          <div class="form-group">
            <label for="objectionReason">Objection Reason</label>
            <textarea class="form-control" id="objectionReason" name="reason" rows="4" required></textarea>
          </div>
          <input type="hidden" id="modalRowId" name="recording_id" />
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Submit Objection</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

@stop

@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    // Open modal and populate objection info
    $('.btn-objection').click(function () {
        const row = $(this).closest('tr');
        const date = row.find('td:eq(1)').text().split('(')[0].trim();
        const duration = row.find('td:eq(3)').text();
        const recordingIds = $(this).data('id');

        $('#modalDate').text(date);
        $('#modalDuration').text(duration);
        $('#modalRowId').val(recordingIds);
        $('#objectionReason').val('');
        $('#objectionModal').modal('show');
    });

    // Submit objection form with AJAX
    $('#objectionForm').submit(function (e) {
        e.preventDefault();

        const recordingIds = $('#modalRowId').val();
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
            url: '{{route("myclass.commitment.objection")}}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                recording_id: recordingIds,
                reason: reason
            },
            success: function (response) {
                $('#objectionModal').modal('hide');

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
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Close'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Ensure modal can close via 'X' or button
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function () {
            $('#objectionModal').modal('hide');
        });
    });
});
</script>
@stop