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
                    Lead List
                </div>
                <div>
                    <a href="{{ route('admin.marketing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        @include('admin.includes.message')

      

        <!-- Filters -->
        <div class="filters-card">
            <form action="" method="GET" id="filterForm">
                <div class="row g-3">
                  
                    <div class="col-md-3">
                        <label class="form-label">Source</label>
                        <select name="list_id" class="form-select form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Select List</option>
                            @foreach($lists as $list)
                            <option value="{{$list->id}}" {{ request('list_id') == $list->id ? 'selected' : '' }}>{{$list->name}}</option>
                            @endforeach
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
                           
                            <a class="btn btn-outline-danger delete-lead-btn" onclick="return confirm('Do you want to remove this lead from the list?')" href="?list_id={{request('list_id')}}&lead_id={{$lead->id}}" >
                                <i class="fas fa-trash"></i>
                            </a>
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

   
</div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stop