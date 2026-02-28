@extends('backend.layouts.app')

@section('title')
Mock Series Management | {{ env('APP_NAME') }}
@stop

@section('content')
<style>
    .dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item.active {
    background-color: #007bff;
    color: white;
}

.badge .fas.fa-times {
    cursor: pointer;
    opacity: 0.8;
}

.badge .fas.fa-times:hover {
    opacity: 1;
}
</style>
<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')

        <!-- Header with Add Button -->
        

        <!-- Mock Series List -->
        <div class="card shadow-sm border-0">
           <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Mock Test Management</h5>

                    
                    <div class="d-flex gap-2">
                       <!-- Course Filter Dropdown -->
<div class="d-flex gap-2 align-items-center">
    <div style="min-width: 250px;">
        <select class="form-select form-control" id="courseFilterSelect">
            <option value="">All Courses</option>
            @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                {{ $course->title }}
            </option>
            @endforeach
        </select>
    </div>
    
   
</div>


                        
                        <!-- Add New Mock Series Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMockSeriesModal">
                            <i class="fas fa-plus"></i> Add New Mock Tests
                        </button>
                    </div>
                </div>
                
                <!-- Filter Active Badge -->
                @if(request('course_id'))
                <div class="mt-2">
                    <span class="badge bg-primary">
                        Showing results for: {{ $courses->where('id', request('course_id'))->first()->title ?? '' }}
                        <a href="{{ request()->fullUrlWithQuery(['course_id' => '']) }}" class="text-white ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                   <table id="mockSeriesTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="25%">Mock Test Name</th>
            <th width="15%">Course</th>
            <!-- <th width="10%">Price</th> -->
            <!-- <th width="10%">Offer Price</th> -->
            <th width="10%">Total Mocks</th>
            <!-- <th width="10%">Validity</th> -->
            <th width="10%">Status</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mockSeries as $key => $series)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $series->name }} - ({{ucwords($series->difficulty)}})</td>
            <td>{{ $series->course->title ?? 'N/A' }}</td>
            <!-- <td>₹{{ number_format($series->price, 2) }}</td> -->
            <!-- <td>
                @if($series->offer_price > 0)
                    ₹{{ number_format($series->offer_price, 2) }}
                @else
                    -
                @endif
            </td> -->
            <td>{{ $series->total_test }}</td>
            <!-- <td>{{ $series->validity ?? '-' }}</td> -->
            <td>
                @if($series->status == '1')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </td>
          <td>
    <div class="btn-group">
       
        <button type="button" class="btn btn-sm btn-outline-primary edit-btn" 
                data-id="{{ $series->id }}"
                data-name="{{ $series->name }}"
                 data-difficulty="{{ $series->difficulty }}"
                data-course-id="{{ $series->course_id }}"
                data-total-test="{{ $series->total_test }}"
                data-status="{{ $series->status }}"
                data-detail="{{ $series->detail }}">
            <i class="fas fa-edit"></i>
        </button>
       
         <a href="{{route('mockseries.testlist',['id'=>$series->id])}}" class="btn btn-sm btn-outline-success add-tests-btn" 
                data-id="{{ $series->id }}"
                data-name="{{ $series->name }}">
            Tests
        </a>

        
        
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                data-id="{{ $series->id }}" 
                data-name="{{ $series->name }}">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>



     <div class="card shadow-sm border-0">
           <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Courses</h5>
                    <div class="d-flex gap-2">
                       <!-- Course Filter Dropdown -->



                      
                    </div>
                </div>
                
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                   <table id="mockSeriesTablex" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th >#</th>
            <th >Course</th>
            <th >Sort Order</th>
        </tr>
    </thead>
    <tbody>
        @foreach($courses as $key => $course)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $course->title }}</td>
           <td><input type="number" value="{{$course->mock_sort_order}}" data-id="{{$course->id}}" class="form-control sort_order" style="width:120px;" /></td>
           
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Add Mock Series Modal -->
<div class="modal fade" id="addMockSeriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addMockSeriesForm" action="{{ route('mock-series.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Mock Series</h5>
                    <button type="button" class="btn-close btn-add-cl" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mock Series Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required maxlength="550">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-control form-select" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Total Mocks <span class="text-danger">*</span></label>
                            <input type="number" name="total_test" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label fw-bold">Difficulty</label>
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select">
                            <option value="">-- Select Difficulty --</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Details</label>
                            <textarea name="detail" class="form-control" rows="4" placeholder="Enter mock series details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-add-cl" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Mock Series</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mock Series Modal -->
<div class="modal fade" id="editMockSeriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMockSeriesForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mock Series</h5>
                    <button type="button" class="btn-close btn-edit-cl" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mock Series Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required maxlength="550">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-control form-select" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Total Mocks <span class="text-danger">*</span></label>
                            <input type="number" name="total_test" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        
                          <div class="col-md-4">
                             <label class="form-label fw-bold">Difficulty</label>
                        <select name="difficulty" class="form-control form-select">
                            <option value="">-- Select Difficulty --</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Details</label>
                            <textarea name="detail" class="form-control" rows="4" placeholder="Enter mock series details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-edit-cl" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Mock Series</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMockSeriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this mock series? This action cannot be undone.</p>
                <p class="fw-bold" id="deleteMockSeriesName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteMockSeriesForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('page_js')
<script>
$(document).ready(function() {
    // Initialize DataTable
$("#mockSeriesTable").DataTable();
$("#mockSeriesTablex").DataTable();
$('.btn-primary').on('click', function() {
        $('#addMockSeriesModal').modal('show');
    });


$(document).on("click",".btn-edit-cl",function(){
    
    $("#editMockSeriesModal").modal('hide')
})


$(document).on("click",".btn-add-cl",function(){
    
    $("#addMockSeriesModal").modal('hide')
})
    // Edit button click handler
  $(document).on('click', '.edit-btn', function() {
        var $button = $(this);
        
        // Populate the edit form with data attributes
        $('#editMockSeriesForm input[name="name"]').val($button.data('name'));
        $('#editMockSeriesForm select[name="course_id"]').val($button.data('course-id'));
        $('#editMockSeriesForm input[name="total_test"]').val($button.data('total-test'));
        $('#editMockSeriesForm select[name="status"]').val($button.data('status'));
        $('#editMockSeriesForm textarea[name="detail"]').val($button.data('detail'));
        $('#editMockSeriesForm select[name="difficulty"]').val($button.data('difficulty'));
        
        // Set form action
        $('#editMockSeriesForm').attr('action', '/user/mock-series/' + $button.data('id'));
        $('#editMockSeriesModal').modal('show');
    });

    // Delete button click handler
    $(document).on('click', '.delete-btn', function() {
        $('#deleteMockSeriesName').text($(this).data('name'));
        $('#deleteMockSeriesForm').attr('action', '/user/mock-series/' + $(this).data('id'));
        $('#deleteMockSeriesModal').modal('show');
    });

    // Add Mock Series form submission
    $('#addMockSeriesForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        
        submitButton.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Saving...');
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error saving mock series: ' + (xhr.responseJSON?.message || 'Unknown error'));
                submitButton.prop('disabled', false).html('Save Mock Series');
            }
        });
    });

    // Edit Mock Series form submission
    $('#editMockSeriesForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        
        submitButton.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Updating...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error updating mock series: ' + (xhr.responseJSON?.message || 'Unknown error'));
                submitButton.prop('disabled', false).html('Update Mock Series');
            }
        });
    });

    // Delete Mock Series form submission
    $('#deleteMockSeriesForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        
        submitButton.prop('disabled', true).html('Deleting...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error deleting mock series: ' + (xhr.responseJSON?.message || 'Unknown error'));
                submitButton.prop('disabled', false).html('Delete');
            }
        });
    });
    
      $('#courseFilterSelect').change(function() {
        var courseId = $(this).val();
        var currentUrl = new URL(window.location.href);
        
        if (courseId) {
            currentUrl.searchParams.set('course_id', courseId);
        } else {
            currentUrl.searchParams.delete('course_id');
        }
        
        window.location.href = currentUrl.toString();
    });

});
</script>

<script>
    
$(document).on("change", ".sort_order", function() {
    var order = $(this).val();
    var id = $(this).data("id");
    
    // Basic validation
    if (!order || !id) {
        console.error('Order or ID is missing');
        return;
    }

    $.ajax({
        url: '/user/mock-series', // Change this to your actual route
        type: 'GET',
        data: {
            id: id,
            order: order,
        },
        success: function(response) {
            if (response.success) {
             
                console.log('Sort order updated successfully');
            } else {
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});
    
</script>
@stop
