@extends('backend.layouts.app')

@section('title')
Leads Management | {{ env('APP_NAME') }}
@stop

@section('content')
<style>
    .leads-management {
        font-family: 'Inter', system-ui, sans-serif;
        background: #f8fafc;
        min-height: 100vh;
    }
    
    .page-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 0;
    }
    
    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .me-2{
        margin-right: 10px;
    }
    
    .header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .filters-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .leads-table {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .table-header {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: #64748b;
        font-size: 0.875rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-inactive {
        background: #f1f5f9;
        color: #475569;
    }
    
    .badge-converted {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }
    
    .pagination {
        margin: 0;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
</style>

<div class="leads-management">
    <!-- Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-title">
                    <i class="fas fa-users text-primary"></i>
                    Leads Management
                </div>
                <div>
                    <a href="{{ route('admin.marketing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                        <i class="fas fa-user-plus"></i> Add Lead
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        @include('admin.includes.message')

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalLeads }}</div>
                <div class="stat-label">Total Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-success">{{ $activeLeads }}</div>
                <div class="stat-label">Active Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-secondary">{{ $inactiveLeads }}</div>
                <div class="stat-label">Inactive Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $convertedLeads }}</div>
                <div class="stat-label">Converted Leads</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form action="{{ route('admin.marketing.leads') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, phone, or email..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Sources</option>
                            <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="social_media" {{ request('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="event" {{ request('source') == 'event' ? 'selected' : '' }}>Event</option>
                            <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Leads Table -->
       <div class="leads-table">
    <div class="table-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Leads List</h5>

        <!-- Bulk Actions -->
        <div id="bulkActions" class="d-none">
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignListModal">
                <i class="fas fa-tasks me-1"></i> Assign to List
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAllLeads">
                    </th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Grade</th>
                    <th>Subject</th>
                    <th>Skip</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>
                        <input type="checkbox" class="lead-checkbox" value="{{ $lead->id }}">
                    </td>
                    <td>
                        <strong>{{ $lead->name }}</strong>
                        @if($lead->notes)
                            <br><small class="text-muted">{{ Str::limit($lead->notes, 30) }}</small>
                        @endif
                    </td>
                    <td>
                        <div><i class="fas fa-phone text-muted me-1"></i> {{ $lead->phone }}</div>
                        @if($lead->email)
                            <div><i class="fas fa-envelope text-muted me-1"></i> {{ $lead->email }}</div>
                        @endif
                    </td>
                    <td class="text-capitalize">{{ $lead->source }}</td>
                    <td>
                        @if($lead->status == 'active')
                            <span class="badge badge-active">Active</span>
                        @elseif($lead->status == 'inactive')
                            <span class="badge badge-inactive">Inactive</span>
                        @else
                            <span class="badge badge-converted">Converted</span>
                        @endif
                    </td>
                    <td class="text-capitalize">{{ $lead->grade }}</td>
                    <td class="text-capitalize">{{ $lead->subject }}</td>
                    <td class="text-capitalize">{{ $lead->skip }}</td>
                    <td><small class="text-muted">{{ $lead->created_at->format('M d, Y') }}</small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary edit-lead-btn" data-lead-id="{{ $lead->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger delete-lead-btn" data-lead-id="{{ $lead->id }}" data-lead-name="{{ $lead->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h4>No Leads Found</h4>
                            <p class="mb-3">Get started by adding your first lead</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                                <i class="fas fa-user-plus"></i> Add First Lead
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
    <div class="pagination">
        {{ $leads->appends(request()->query())->links() }}
    </div>
    @endif
</div>
    </div>
</div>
<!-- Assign to List Modal -->
<div class="modal fade" id="assignListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="assignListForm" method="POST" action="{{ route('admin.marketing.assign-list') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-list-alt text-primary me-2"></i>Assign Selected Leads</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="lead_ids" id="selectedLeadIds">
                    <div class="mb-3">
                        <label class="form-label">Choose Existing List</label>
                        <select name="list_id" class="form-control">
                            <option value="">-- Select List --</option>
                            @foreach($lists as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center text-muted my-2">or</div>
                    <div class="mb-3">
                        <label class="form-label">Create New List</label>
                        <input type="text" name="new_list_name" class="form-control" placeholder="Enter new list name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Add Lead Modal -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus text-primary me-2"></i>Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            <form id="addLeadForm" action="{{ route('admin.marketing.store-lead') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                         <div class="col-6">
                <label for="leadPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="leadPhone" name="phone" required>
            </div>
            <div class="col-6">
                <label for="leadEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="leadEmail" name="email">
            </div>
            
              <div class="col-6">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" class="form-control" id="grade" name="grade">
            </div>
              <div class="col-6">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject">
            </div>
              <div class="col-6">
                <label for="skip" class="form-label">Skip</label>
                <input type="text" class="form-control" id="skip" name="skip">
            </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Source</label>
                            <select class="form-select form-control" name="source">
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="social_media">Social Media</option>
                                <option value="event">Event</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="converted">Converted</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit text-primary me-2"></i>Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            <form id="editLeadForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" id="edit_phone" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email">
                        </div>
                           <div class="col-6">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" class="form-control" id="edit_grade" name="grade">
            </div>
              <div class="col-6">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="edit_subject" name="subject">
            </div>
              <div class="col-6">
                <label for="skip" class="form-label">Skip</label>
                <input type="text" class="form-control" id="edit_skip" name="skip">
            </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Source</label>
                            <select class="form-select form-control" name="source" id="edit_source">
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="social_media">Social Media</option>
                                <option value="event">Event</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Status</label>
                            <select class="form-select form-control" name="status" id="edit_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="converted">Converted</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" id="edit_notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete lead "<strong id="deleteLeadName"></strong>"?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteLeadForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Lead</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bulk selection handling
    const selectAll = document.getElementById('selectAllLeads');
    const checkboxes = document.querySelectorAll('.lead-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedInput = document.getElementById('selectedLeadIds');

    function updateBulkUI() {
        const selected = Array.from(checkboxes).filter(chk => chk.checked).map(chk => chk.value);
        bulkActions.classList.toggle('d-none', selected.length === 0);
        selectedInput.value = selected.join(',');
    }

    selectAll.addEventListener('change', e => {
        checkboxes.forEach(chk => chk.checked = e.target.checked);
        updateBulkUI();
    });

    checkboxes.forEach(chk => chk.addEventListener('change', updateBulkUI));

    // Submit assign list form
    document.getElementById('assignListForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('assignListModal')).hide();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong!');
        });
    });
</script>

<script>
    // Edit Lead
    document.querySelectorAll('.edit-lead-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const leadId = this.getAttribute('data-lead-id');
            
            fetch(`/user/leads/${leadId}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const lead = data.lead;
                        
                        // Populate form
                        document.getElementById('edit_name').value = lead.name;
                        document.getElementById('edit_phone').value = lead.phone;
                        document.getElementById('edit_email').value = lead.email || '';
                        document.getElementById('edit_source').value = lead.source;
                        document.getElementById('edit_status').value = lead.status;
                        document.getElementById('edit_notes').value = lead.notes || '';
                        document.getElementById('edit_grade').value = lead.grade || '';
                        document.getElementById('edit_subject').value = lead.subject || '';
                        document.getElementById('edit_skip').value = lead.skip || '';
                        
                        // Set form action
                        document.getElementById('editLeadForm').action = `/user/leads/${leadId}`;
                        
                        // Show modal
                        const editModal = new bootstrap.Modal(document.getElementById('editLeadModal'));
                        editModal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading lead data');
                });
        });
    });

    // Delete Lead Confirmation
    document.querySelectorAll('.delete-lead-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const leadId = this.getAttribute('data-lead-id');
            const leadName = this.getAttribute('data-lead-name');
            
            document.getElementById('deleteLeadName').textContent = leadName;
            document.getElementById('deleteLeadForm').action = `/user/leads/${leadId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteLeadModal'));
            deleteModal.show();
        });
    });

    // Form submissions
    document.getElementById('addLeadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitLeadForm(this, 'Lead added successfully!');
    });

    document.getElementById('editLeadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitLeadForm(this, 'Lead updated successfully!');
    });

    document.getElementById('deleteLeadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteLeadModal')).hide();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the lead.');
        });
    });

    function submitLeadForm(form, successMessage) {
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(successMessage);
                bootstrap.Modal.getInstance(form.closest('.modal')).hide();
                window.location.reload();
            } else {
                let errorMessage = data.message;
                if (data.errors) {
                    errorMessage += '\n' + Object.values(data.errors).flat().join('\n');
                }
                alert('Error: ' + errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the form.');
        });
    }
</script>
@stop