@extends('backend.layouts.app')

@section('title')
Mock Sections Management | {{ env('APP_NAME') }}
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

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Sections for {{$mock->name}}</h5>
                    <div class="d-flex gap-2">
                        <!-- Manual Add Subject Button -->
                        <button class="btn btn-primary btn-sm" id="openAddModal">
                            <i class="fas fa-plus"></i> Add Section
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                   <table id="subjects" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Section</th>
                                <th>Difficulty</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $key => $subject)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ ucwords($subject->difficulty) }}</td>
                                <td>
                                    <span class="badge {{ $subject->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($subject->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{route('mockseries.chapters',[$subject->id])}}">Chapters</a>
                                    <button class="btn btn-sm btn-warning editSubjectBtn" 
                                            data-id="{{ $subject->id }}" 
                                            data-name="{{ $subject->name }}"
                                            data-status="{{ $subject->status }}"
                                            data-difficulty="{{ $subject->difficulty }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('mockseries.subjects.destroy', $subject->id) }}" 
                                          method="POST" 
                                          class="d-inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this section?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('mockseries.subjects.store',[$mock->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="mock_id" value="{{ $mock->id }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Section</h5>
                <button type="button" class="btn-close closeAddModal"><i class="fas fa-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Section Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter section name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-control">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Difficulty</label>
                    <select name="difficulty" class="form-control form-select">
                        <option value="">-- Select Difficulty --</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary closeAddModal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editSubjectForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close closeEditModal"><i class="fas fa-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Section Name</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-select form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Difficulty</label>
                    <select name="difficulty" id="editDifficulty" class="form-control form-select">
                        <option value="">-- Select Difficulty --</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary closeEditModal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>
@stop

@section('page_js')
<script>
$(document).ready(function () {
    $("#subjects").DataTable();

    // Open Add Modal
    $("#openAddModal").on("click", function () {
        $("#addSubjectModal").modal("show");
    });

    // Close Add Modal
    $(".closeAddModal").on("click", function () {
        $("#addSubjectModal").modal("hide");
    });

    // Open Edit Modal manually
    $(".editSubjectBtn").on("click", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let status = $(this).data("status");
        let difficulty = $(this).data("difficulty");

        $("#editName").val(name);
        $("#editStatus").val(status);
        $("#editDifficulty").val(difficulty);
        $("#editSubjectForm").attr("action", "/user/mock-series/subjects/update/" + id);

        $("#editSubjectModal").modal("show");
    });

    // Close Edit Modal
    $(".closeEditModal").on("click", function () {
        $("#editSubjectModal").modal("hide");
    });
});
</script>
@stop
