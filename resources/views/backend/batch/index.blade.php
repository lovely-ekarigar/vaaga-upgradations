<?php
use App\Models\Course;
use App\Models\Batch;
use App\Models\Earning;
use App\Models\Recording;
 
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">Batch list</h3>  
      
@if(auth()->user()->hasRole('administrator') || auth()->user()->hasRole('backend-support-staff'))
    <div class="float-right">
        <a href="{{ route('admin.batch.create') }}"
           class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
    </div>
@endif
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
                  <th>Completion</th>
                  <th>Batch Duration</th>
                  <th>Batch class time</th>
                  <th>Batch Days</th>
                  <th>Expenses</th>
                  <th>Join</th>
                  <th>&nbsp; @lang('strings.backend.general.actions')</th>
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>

               @foreach($list as $l)
               @if($l->name)
               <?php $count++; ?>
               <tr data-entry-id="1" role="row" class="odd">
                  <td class="text-center">
                     <input type="checkbox" class="single" name="id[]" value="1">
                  </td>
                  <td>
                     <?=$count?>
                  </td>
                  <td>{{$l->name}}</td>
                  <td>
                     <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($l->course->id);
                     ?>

                  </td>
                  <td>
                     <?php
          $lo = new Batch;
          $ar = $lo->bacthCompletion($l->id);
           ?>

                     @if($l->is_completed == '0')

                     {{$ar['completed']}}/{{$ar['total']}}
                     @if($ar['total']!=0)
                     ({{floor($ar['completed']*100/$ar['total'])}}%)
                     @endif

                     @else

                     <span class="badge badge-success">Completed</span>

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
                     <?php
                     $ern = new Earning();
                     $rp = $ern->totalEarningsBatch(0,$l->id);
                      ?>
                     ₹{{number_format($rp)}}
                  </td>
                  <td>
                     <?php
                 $meetid=Recording::where("parent",$l->parent_api_class_id)->where("created_at",">=",date("Y-m-d 00:00:00"))->orderBy("id","desc")->first();
            if($meetid){
                $l["can_join"]=true;
                $l["api_id"]=$meetid->api_class_id;

            }else{
                $l["can_join"]=false;
                $l["api_id"]=""; 
            }
            
                 
                 if($l["can_join"]){ ?>
                     <p><a href="{{route('myclass.slaunch',['id'=>$l['id'],'meetid'=>$l['api_id']])}}" target="_blank"
                           class="btn btn-primary btn-sm mb-2 cnotstart" data-class="<?=$l['api_id']?>">Goto class</a>
                        <?php }else{
                                  ?>
                     <p>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm cnotstart mb-2" data-class="">Class
                           has not started</a>
                        <?php
                                } ?>
                  </td>
                  <td>



                     <a href="<?php echo route('admin.batch.batchassign', ['id' => $l->id]); ?>"
                        class="btn btn-xs btn-primary mb-1"><i class="icon-plus"></i></a>
                     <a href="<?php echo route('admin.batch.edit', ['id' => $l->id]); ?>"
                        class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                     <a href="<?php echo route('admin.batch.delete', ['id' => $l->id]); ?>"
                        onclick="return confirm('Do you really want to delete this batch')"
                        class="btn btn-xs btn-danger mb-1"><i class="fa fa-trash"></i></a>
                     <!-- <a href="javascript:void(0)" data-batch="{{$l->id}}"    class="btn btn-xs btn-warning mb-1 notify"><i class="icon-bell"></i></a> -->

                     <a class="btn btn-info btn-sm mb-1" href="/user/batch/batch-progress-list/{{$l->id}}">Progress</a>
                     <a class="btn btn-primary btn-sm mb-1"
                        href="/user/batch/batch-recordings/{{$l->id}}">Recordings</a>
                     <a class="btn btn-secondary btn-sm mb-1" href="/user/batch/batch-feedback/{{$l->id}}">Feedbacks</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/assignment/{{$l->id}}">Assignments</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/upload/{{$l->id}}">Course Material</a>
                     <a class="btn btn-dark btn-sm mb-1" href="{{ route('study-material', ['id' => $l->id]) }}">Study Material</a>
                     <a class="btn btn-primary btn-sm mb-1" href="/user/myclass/exam/{{$l->id}}">Subjective Exams</a>
                     <a class="btn btn-success btn-sm mb-1" href="/user/myclass/fees/{{$l->id}}">Fees</a>
                     <a class="btn btn-info btn-sm mb-1" href="/user/myclass/attend/{{$l->id}}">Attendance</a>
                     <a href="javascript:void(0);" class="btn btn-warning btn-sm mb-1 mock-tests-btn"
                        data-id="{{ $l->id }}" data-name="{{ $l->name }}">
                        <i class="fa fa-file-text"></i> Assign Mock Tests
                     </a>
                     <a href="{{ route('admin.myclass.mockTestsPage', $l->id) }}" class="btn btn-outline-success btn-sm mb-1">
                        <i class="fa fa-file-text"></i> Available Mock Tests
                     </a>
                     <a href="{{ route('admin.batch.mockResults', $l->id) }}" class="btn btn-success btn-sm mb-1">
                        <i class="fa fa-chart-bar"></i> Mock Test Results
                     </a>


                     <a href="javascript:void(0);" class="btn btn-xs btn-warning mb-1 suspend-btn"
                        data-id="{{ $l->id }}">
                        <i class="fa fa-ban"></i> Suspend
                     </a>

                     @if($l->is_completed == '0')

                     <a class="btn btn-primary btn-sm mb-1"
                        onclick="return confirm('Do you want to mark this batch as completed? Once marked completed can not be changed.')"
                        href="/user/batch/batch-is-completed/{{$l->id}}">Mark Completed</a>


                     @endif




                  </td>
               </tr>
               @endif
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>

<!-- Assign Mock Tests Modal -->
<div class="modal fade" id="mockTestsModal" tabindex="-1" role="dialog" aria-labelledby="mockTestsModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <form id="mockTestsForm">
         @csrf
         <input type="hidden" name="batch_id" id="mock_batch_id">

         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="mockTestsModalLabel">Assign Mock Tests - <span id="modal_batch_name"></span></h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>

            <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
               <p class="text-muted">Course: <strong id="modal_course_name"></strong></p>
               <div id="mock-tests-loading" class="text-center">
                  <i class="fa fa-spinner fa-spin fa-2x"></i>
                  <p>Loading mock tests...</p>
               </div>
               <div id="mock-tests-content" style="display: none;">
                  <div class="alert alert-info mb-3">
                     <i class="fa fa-info-circle"></i> <strong>Note:</strong> You can assign any mock test to this batch. The chapter progress filter will be applied on the tutor's dashboard - tutors will only see mock tests where all required chapters have been completed or ongoing in batch progress.
                  </div>
                  <p class="text-info"><i class="fa fa-check-square"></i> Select mock tests to assign to this batch:</p>
                  <div id="mock-tests-list" class="form-group">
                     <!-- Mock tests will be loaded here dynamically -->
                  </div>
                  <div id="no-mock-tests" class="alert alert-warning" style="display: none;">
                     <i class="fa fa-exclamation-triangle"></i> No mock tests available for this course.
                  </div>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
               <button type="submit" class="btn btn-primary" id="save-mock-tests-btn">Save Mock Tests</button>
            </div>
         </div>
      </form>
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



<div class="modal fade" id="lessionModal" tabindex="-1" role="dialog" aria-labelledby="lessionTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="lessionTitle">Notify Batch Students</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-12">
                  <div class="form-group">
                     <label>Enter Title</label>
                     <input type="text" id="ntitle" name="title" class="form-control" />
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-group">
                     <label>Enter Message</label>
                     <input type="text" id="nmsg" name="msg" class="form-control" />
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary notify-now">Notify</button>
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
    
    // Store previously assigned mock test IDs
    var previouslyAssignedMockIds = [];
    
    // Open Mock Tests Modal
    $(document).on('click', '.mock-tests-btn', function () {
        var batchId = $(this).data('id');
        var batchName = $(this).data('name');
      var submitButton = $('#save-mock-tests-btn');
        
        // Reset modal state
        $('#mock_batch_id').val(batchId);
        $('#modal_batch_name').text(batchName);
        $('#modal_course_name').text('');
        $('#mock-tests-list').html('');
        $('#mock-tests-loading').show();
        $('#mock-tests-content').hide();
        $('#no-mock-tests').hide();
      submitButton.prop('disabled', false).html('Save Mock Tests');
        $('#mockTestsModal').modal('show');
        
        // Fetch available mock tests
        $.ajax({
            url: '/user/batch/' + batchId + '/available-mock-tests',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Debug info
                    console.log('Mock Tests Count:', response.mockTests.length);
                    console.log('Mock Tests:', response.mockTests);
                    console.log('Previously Assigned:', response.assignedMockIds);
                    
                    // Store previously assigned IDs
                    previouslyAssignedMockIds = response.assignedMockIds || [];
                    
                    $('#modal_course_name').text(response.courseName);
                    $('#mock-tests-loading').hide();
                    
                    if (response.mockTests.length > 0) {
                        var mockTestsHtml = '';
                        var currentSeriesId = null;
                        
                        response.mockTests.forEach(function(mockTest) {
                            var isAssigned = response.assignedMockIds.includes(mockTest.id);
                            var isChecked = isAssigned ? 'checked' : '';
                            var assignedClass = isAssigned ? 'currently-assigned' : '';
                            
                            // Show series header if it's a new series
                            if (currentSeriesId !== mockTest.series_id) {
                                if (currentSeriesId !== null) {
                                    mockTestsHtml += '</div></div>'; // Close previous card
                                }
                                currentSeriesId = mockTest.series_id;
                                mockTestsHtml += '<div class="card mb-3">';
                                mockTestsHtml += '<div class="card-header bg-primary text-white">';
                                mockTestsHtml += '<strong>' + mockTest.series_name + '</strong>';
                                if (mockTest.series_detail) {
                                    mockTestsHtml += '<br><small>' + mockTest.series_detail + '</small>';
                                }
                                mockTestsHtml += '</div>';
                                mockTestsHtml += '<div class="card-body">';
                            }
                            
                            // Individual mock test checkbox (all enabled now)
                            mockTestsHtml += '<div class="custom-control custom-checkbox mb-2 ' + assignedClass + '">';
                            mockTestsHtml += '<input type="checkbox" class="custom-control-input mock-checkbox" id="mock_test_' + mockTest.id + '" name="mock_test_ids[]" value="' + mockTest.id + '" ' + isChecked + ' data-originally-assigned="' + isAssigned + '">';
                            mockTestsHtml += '<label class="custom-control-label" for="mock_test_' + mockTest.id + '">';
                            mockTestsHtml += '<strong>' + mockTest.name + '</strong>';
                           //  if (isAssigned) {
                           //      mockTestsHtml += ' <span class="badge badge-info">Currently Assigned</span>';
                           //  }
                            if (mockTest.description) {
                                mockTestsHtml += '<br><small class="text-muted">' + mockTest.description + '</small>';
                            }
                            mockTestsHtml += '<br><small>';
                            if (mockTest.total_questions) {
                                mockTestsHtml += '<span class="badge badge-info mr-1">Questions: ' + mockTest.total_questions + '</span>';
                            }
                            if (mockTest.duration) {
                                mockTestsHtml += '<span class="badge badge-success">Duration: ' + mockTest.duration + ' min</span>';
                            }
                            mockTestsHtml += '</small>';
                            mockTestsHtml += '</label>';
                            mockTestsHtml += '</div>';
                        });
                        
                        // Close last card
                        if (currentSeriesId !== null) {
                            mockTestsHtml += '</div></div>';
                        }
                        
                        $('#mock-tests-list').html(mockTestsHtml);
                        $('#mock-tests-content').show();
                        $('#no-mock-tests').hide();
                        updateSaveButtonState();
                    } else {
                        $('#mock-tests-content').show();
                        $('#mock-tests-list').html('');
                        $('#no-mock-tests').show();
                        updateSaveButtonState();
                    }
                }
            },
            error: function(xhr) {
                $('#mock-tests-loading').hide();
                alert('Error loading mock tests: ' + (xhr.responseJSON?.message || 'Unknown error'));
                $('#mockTestsModal').modal('hide');
            }
        });
    });

   function updateSaveButtonState() {
      var hasChanges = false;
      $('input[name="mock_test_ids[]"]').each(function() {
         var wasOriginallyAssigned = $(this).data('originally-assigned');
         var isCurrentlyChecked = $(this).is(':checked');

         if ((isCurrentlyChecked && !wasOriginallyAssigned) || (!isCurrentlyChecked && wasOriginallyAssigned)) {
            hasChanges = true;
            return false;
         }
      });

      $('#save-mock-tests-btn').prop('disabled', !hasChanges);
   }

   $(document).on('change', 'input[name="mock_test_ids[]"]', function() {
      updateSaveButtonState();
   });
    
    // Save Mock Tests
    $('#mockTestsForm').on('submit', function(e) {
        e.preventDefault();
        var batchId = $('#mock_batch_id').val();
        var mocksToAdd = [];
        var mocksToRemove = [];
        var currentlyChecked = [];
        
        // Get all currently checked mock test IDs
        $('input[name="mock_test_ids[]"]:checked').each(function() {
            currentlyChecked.push($(this).val());
        });
        
        // Analyze all mock test checkboxes to determine adds and removes
        $('input[name="mock_test_ids[]"]').each(function() {
            var mockId = $(this).val();
            var wasOriginallyAssigned = $(this).data('originally-assigned');
            var isCurrentlyChecked = $(this).is(':checked');
            
            // If it's checked now but wasn't assigned before = ADD
            if (isCurrentlyChecked && !wasOriginallyAssigned) {
                mocksToAdd.push(mockId);
            }
            
            // If it was assigned before but is now unchecked = REMOVE
            if (!isCurrentlyChecked && wasOriginallyAssigned) {
                mocksToRemove.push(mockId);
            }
        });
        
        // Check if any changes were made
        if (mocksToAdd.length === 0 && mocksToRemove.length === 0) {
            alert('No changes detected. Please check or uncheck mock tests to make changes.');
            return;
        }
        
        // Confirm if removing any mock tests
        if (mocksToRemove.length > 0) {
            var confirmMsg = 'You are about to:\n';
            if (mocksToRemove.length > 0) {
                confirmMsg += '- Remove ' + mocksToRemove.length + ' mock test(s)\n';
            }
            if (mocksToAdd.length > 0) {
                confirmMsg += '- Add ' + mocksToAdd.length + ' new mock test(s)\n';
            }
            confirmMsg += '\nNote: Schedules and activation status will be preserved for remaining tests.\n\nContinue?';
            
            if (!confirm(confirmMsg)) {
                return;
            }
        }
        
        var submitButton = $('#save-mock-tests-btn');
        submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '/user/batch/' + batchId + '/save-mock-tests',
            type: 'POST',
            data: {
                mock_test_ids: currentlyChecked,
                mocks_to_add: mocksToAdd,
                mocks_to_remove: mocksToRemove,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var message = response.message;
                    if (mocksToAdd.length > 0 || mocksToRemove.length > 0) {
                        message += '\n\nChanges made:';
                        if (mocksToAdd.length > 0) {
                            message += '\n✓ Added: ' + mocksToAdd.length + ' mock test(s)';
                        }
                        if (mocksToRemove.length > 0) {
                            message += '\n✓ Removed: ' + mocksToRemove.length + ' mock test(s)';
                        }
                    }
                  //   alert(message);
                    $('#mockTestsModal').modal('hide');
                    // Optionally reload the page to reflect changes
                    // location.reload();
                }
                submitButton.prop('disabled', false).html('Save Mock Tests');
            },
            error: function(xhr) {
                alert('Error saving mock tests: ' + (xhr.responseJSON?.message || 'Unknown error'));
                submitButton.prop('disabled', false).html('Save Mock Tests');
            }
        });
    });
});
</script>
<script>
   $('#myTable').DataTable();
   var bid="";
   
   $(".notify").on("click",function(){
       bid = $(this).data("batch");
        $("#lessionModal").modal("show");
   })
   
   $(document).on("click",".notify-now",function(){
       var title = $("#ntitle").val();
        var msg = $("#nmsg").val();
        
        
        $(".notify-now").html("Please wait...");
        $.ajax({
            url:'/user/batches/onesignal',
            type:'POST',
            data:{bid:bid,msg:msg,title:title,_token:'<?=csrf_token()?>'},
            success:function(res){
                if(res.success){
                   $("#ntitle").val("");
                   $("#nmsg").val("");
                   $("#lessionModal").modal("hide");
                   alert("Notification sent succesfully");
                   
                }else{
                     $(".notify-now").html("Notify");
                   alert(res.message)   
                }
            },
            error:function(){
                $(".notify-now").html("Notify");
                alert("Unable to send notification")
            }
        })
        
        
   })
   
   
       
</script>

<style>
/* Style for currently assigned mock tests */
.currently-assigned {
    background-color: #e8f5e9;
    padding: 5px;
    border-radius: 4px;
    border-left: 3px solid #4caf50;
}

.currently-assigned label {
    font-weight: 500;
}
</style>

@endpush