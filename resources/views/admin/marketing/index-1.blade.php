@extends('backend.layouts.app')

@section('title')
Marketing | {{ env('APP_NAME') }}
@stop

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style> 
    :root {
        --primary: #6366f1;
        --primary-light: #818cf8;
        --secondary: #10b981;
        --accent: #f59e0b;
        --dark: #1f2937;
        --light: #f8fafc;
        --border: #e2e8f0;
        --text-muted: #64748b;
    }
    
    .modern-dashboard {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: var(--light);
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: white;
        border-bottom: 1px solid var(--border);
        padding: 1.5rem 0;
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    
    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .header-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 2px;
    }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    #aisensyRequired {
    transition: all 0.3s ease;
}

#aisensyCampaignId.border-warning {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-text.text-warning {
    color: #856404 !important;
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    padding: 8px 12px;
    margin-top: 8px;
}
    .btn-modern {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-modern-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-modern-primary:hover {
        background: var(--primary-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-modern-success {
        background: var(--secondary);
        color: white;
    }
    
    .btn-modern-success:hover {
        background: #34d399;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    
    .stat-modern {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }
    
    .stat-icon-primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary);
    }
    
    .stat-icon-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: var(--secondary);
    }
    
    .stat-icon-accent {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: var(--accent);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 1.5rem;
        align-items: start;
    }
    
    .card-modern {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    
    .card-header-modern {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: white;
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .card-body-modern {
        padding: 1.5rem;
    }
    
    .dropzone-modern {
        border: 2px dashed var(--border) !important;
        border-radius: 10px;
        background: var(--light);
        padding: 2rem;
        transition: all 0.3s ease;
        min-height: 140px;
    }
    
    .dropzone-modern:hover {
        border-color: var(--primary) !important;
        background: #f0f4ff;
    }
    
    .dz-message {
        text-align: center;
        color: var(--text-muted);
    }
    
    .template-card {
        background: linear-gradient(135deg, #f0f4ff, #e0e7ff);
        border: 1px solid #c7d2fe;
        border-radius: 10px;
        padding: 1.25rem;
        margin-top: 1rem;
    }
    
    .template-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .template-icon {
        width: 40px;
        height: 40px;
        background: var(--primary);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead {
        background: var(--light);
    }
    
    .table-modern th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-modern tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }
    
    .campaign-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .campaign-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    
    .campaign-details h4 {
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        font-size: 0.95rem;
    }
    
    .campaign-details p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.8rem;
    }
    
    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-email {
        background: #dbeafe;
        color: #1d4ed8;
    }
    
    .badge-sms {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-whatsapp {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-inactive {
        background: #f1f5f9;
        color: #475569;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    
    .action-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .action-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .action-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }
    
    .action-icon-primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary);
    }
    
    .action-icon-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: var(--secondary);
    }
    
    .action-icon-accent {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: var(--accent);
    }
    
    .action-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }
    
    .action-desc {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .modal-modern .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .modal-header-modern {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    
    .modal-title-modern {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .form-modern .form-label {
        font-weight: 500;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .form-modern .form-control {
        border: 1px solid var(--border);
        border-radius: 8px;
        /*padding: 0.75rem;*/
        transition: all 0.2s ease;
    }
    
    .form-modern .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>

<div class="modern-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-title">
                    Marketing Dashboard
                </div>
                <div class="header-actions">
                    <button class="btn-modern btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                        <i class="fas fa-user-plus"></i>
                        Add Lead
                    </button>
                    <button class="btn-modern btn-modern-success createCampaignBtn" id="createCampaignBtn">
                        <i class="fas fa-bullhorn"></i>
                        Create Campaign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        @include('admin.includes.message')

        <!-- Statistics -->
       <div class="stats-grid">
    <div class="stat-modern">
        <div class="stat-icon stat-icon-primary">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">{{ $totalCampaigns ?? 0 }}</div>
        <div class="stat-label">Total Campaigns</div>
    </div>
    <div class="stat-modern">
        <a href="{{ route('admin.marketing.leads') }}" class="text-decoration-none text-dark">
            <div class="stat-icon stat-icon-success">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $totalLeads ?? 0 }}</div>
            <div class="stat-label">Total Leads</div>
            <div class="stat-link mt-2">
                <small class="text-primary">View All Leads <i class="fas fa-arrow-right ms-1"></i></small>
            </div>
        </a>
    </div>
    <div class="stat-modern">
        <a href="{{ route('admin.marketing.leads', ['status' => 'active']) }}" class="text-decoration-none text-dark">
            <div class="stat-icon stat-icon-accent">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-value text-success">{{ $activeLeads ?? 0 }}</div>
            <div class="stat-label">Active Leads</div>
            <div class="stat-link mt-2">
                <small class="text-success">View Active <i class="fas fa-arrow-right ms-1"></i></small>
            </div>
        </a>
    </div>
    <div class="stat-modern">
        <a href="{{ route('admin.marketing.leads', ['status' => 'inactive']) }}" class="text-decoration-none text-dark">
            <div class="stat-icon stat-icon-primary">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-value text-secondary">{{ $inactiveLeads ?? 0 }}</div>
            <div class="stat-label">Inactive Leads</div>
            <div class="stat-link mt-2">
                <small class="text-secondary">View Inactive <i class="fas fa-arrow-right ms-1"></i></small>
            </div>
        </a>
    </div>
</div>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Left Column -->
            <div>
                <!-- Import Section -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <div class="card-title">
                            <i class="fas fa-file-import text-primary"></i>
                            Import Leads
                        </div>
                    </div>
                    <div class="card-body-modern">
                        <form action="{{ route('admin.marketing.import-leads') }}" 
                              class="dropzone dropzone-modern" 
                              id="leadDropzone">
                            @csrf
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                                <h5>Drop files or click to upload</h5>
                                <p class="text-muted">Supports Excel and CSV files up to 5MB</p>
                            </div>
                        </form>
                        
                        <div class="template-card">
                            <div class="template-header">
                                <div class="template-icon">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">Download Template</h4>
                                    <p class="mb-0 text-muted">Use our template for proper formatting</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.marketing.download-template') }}" class="btn btn-outline-primary btn-sm">
                                Download Excel Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Campaigns Section -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-title">
                            <i class="fas fa-bullhorn text-primary"></i>
                            Marketing Campaigns
                            <span class="badge bg-primary rounded-pill ms-2">{{ count($campaigns ?? []) }}</span>
                        </div>
                    </div>
                    <div class="card-body-modern p-0">
                        @if(!empty($campaigns) && count($campaigns))
                            <div class="table-responsive">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>Type</th>
                                            <th>Leads</th>
                                            <th>Created</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($campaigns as $campaign)
                                        <tr>
                                            <td>
                                                <div class="campaign-info">
                                                    <div class="campaign-icon">
                                                        <i class="fas fa-bullhorn"></i>
                                                    </div>
                                                    <div class="campaign-details">
                                                        <h4>{{ $campaign->name }}</h4>
                                                        @if($campaign->description)
                                                        <p>{{ $campaign->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($campaign->type == 'email')
                                                    <span class="badge-modern badge-email">Email</span>
                                                @elseif($campaign->type == 'sms')
                                                    <span class="badge-modern badge-sms">SMS</span>
                                                @else
                                                    <span class="badge-modern badge-whatsapp">WhatsApp</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $campaign->leads()->count() ?? 0 }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $campaign->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                @if($campaign->status == 'active')
                                                    <span class="badge-modern badge-active">Active</span>
                                                @else
                                                    <span class="badge-modern badge-inactive">Inactive</span>
                                                @endif
                                            </td>
                                           
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <h4>No Campaigns Yet</h4>
                                <p class="mb-3">Get started by creating your first marketing campaign</p>
                                <button class="btn btn-primary createCampaignBtn" >
                                    Create First Campaign
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions -->
            <div>
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-title">
                            <i class="fas fa-bolt text-accent"></i>
                            Quick Actions
                        </div>
                    </div>
                    <div class="card-body-modern">
                        <div class="actions-grid">
                            <div class="action-card" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                                <div class="action-icon action-icon-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="action-title">Add Lead</div>
                                <div class="action-desc">Add a single lead manually</div>
                            </div>
                            <div class="action-card" id="createCampaignBtn">
                                <div class="action-icon action-icon-success">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="action-title">New Campaign</div>
                                <div class="action-desc">Create marketing campaign</div>
                            </div>
                            <div class="action-card">
                                <div class="action-icon action-icon-accent">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="action-title">Email Leads</div>
                                <div class="action-desc">Send bulk emails</div>
                            </div>
                            <a class="action-card" href="{{route('admin.marketing.list')}}">
                                <div class="action-icon action-icon-primary">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="action-title">Lead List</div>
                                <div class="action-desc">View List</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lead Modal -->
<div class="modal fade modal-modern" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-modern">
        <h5 class="modal-title-modern">
            <i class="fas fa-user-plus text-primary"></i>
            Add New Lead
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="bootstrap.Modal.getInstance(document.getElementById('addLeadModal')).hide()"><i class="fa fa-close"></i></button>
      </div>
      <form id="addLeadForm" action="{{ route('admin.marketing.store-lead') }}" method="POST" class="form-modern">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
                <label for="leadName" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="leadName" name="name" required>
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
                <label for="leadSource" class="form-label">Source</label>
                <select class="form-select form-control" id="leadSource" name="source">
                  <option value="website">Website</option>
                  <option value="referral">Referral</option>
                  <option value="social_media">Social Media</option>
                  <option value="event">Event</option>
                  <option value="other">Other</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label for="leadStatus" class="form-label">Status</label>
                <select class="form-select form-control" id="leadStatus" name="status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="converted">Converted</option>
                </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="bootstrap.Modal.getInstance(document.getElementById('addLeadModal')).hide()">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Lead</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Create Campaign Modal -->
<div class="modal fade modal-modern" id="createCampaignModal" tabindex="-1" aria-labelledby="createCampaignLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-modern">
        <h5 class="modal-title-modern">
            <i class="fas fa-bullhorn text-success"></i>
            Create Campaign
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="bootstrap.Modal.getInstance(document.getElementById('createCampaignModal')).hide()"><i class="fa fa-close"></i></button>
      </div>
      <form id="createCampaignForm" action="{{ route('admin.marketing.store-campaign') }}" method="POST" class="form-modern">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
                <label for="campaignName" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="campaignName" name="name" required>
            </div>
            <div class="col-12">
                <label for="campaignDescription" class="form-label">Description</label>
                <textarea class="form-control" id="campaignDescription" name="description" rows="3"></textarea>
            </div>
            <div class="col-12 col-sm-6">
                <label for="campaignType" class="form-label">Type <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="campaignType" name="type" required>
                  <option value="email">Email</option>
                  <option value="sms">SMS</option>
                  <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label for="campaignAudience" class="form-label">Audience <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="campaignAudience" name="audience" required>
                  <option value="all">All Leads</option>
                  <option value="active">Active Leads</option>
                  <option value="inactive">Inactive Leads</option>
                  <option value="converted">Converted Leads</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="subject" name="subject" required>
                  <option value="all">All Subject</option>
                  @foreach($subjects as $sub)
                  <option value="{{$sub->subject}}">{{$sub->subject}}</option>
                  @endforeach
                </select>
            </div>
              <div class="col-12 col-sm-6">
                <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="grade" name="grade" required>
                  <option value="all">All Grade</option>
                  @foreach($grades as $sub)
                  <option value="{{$sub->grade}}">{{$sub->grade}}</option>
                  @endforeach
                </select>
            </div>
            
              <div class="col-12 col-sm-6">
                <label for="skip" class="form-label">Skip <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="skip" name="skip" required>
                  <option value="all">All Skip</option>
                  @foreach($skips as $sub)
                  <option value="{{$sub->skip}}">{{$sub->skip}}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label for="list" class="form-label">List <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="list" name="list" required>
                  <option value="none">None</option>
                  @foreach($lists as $list)
                  <option value="{{$list->id}}">{{$list->name}}</option>
                  @endforeach
                </select>
            </div>
            
            <div class="col-12">
                <label for="aisensyCampaignId" class="form-label">
                    Aisensy Campaign ID 
                    <span class="text-danger" id="aisensyRequired">*</span>
                    <small class="text-muted ms-1" id="aisensyHelp">(Required for WhatsApp campaigns)</small>
                </label>
                <input type="text" class="form-control" id="aisensyCampaignId" name="aisensy_campaign_id" 
                       placeholder="Enter Aisensy Campaign ID">
                <div class="form-text" id="aisensyDescription">
                    For WhatsApp campaigns, you must provide the Aisensy Campaign ID to connect with your WhatsApp Business account.
                </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="bootstrap.Modal.getInstance(document.getElementById('createCampaignModal')).hide()">Cancel</button>
          <button type="submit" class="btn btn-success">Create Campaign</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const campaignType = document.getElementById('campaignType');
    const aisensyCampaignId = document.getElementById('aisensyCampaignId');
    const aisensyRequired = document.getElementById('aisensyRequired');
    const aisensyHelp = document.getElementById('aisensyHelp');
    const aisensyDescription = document.getElementById('aisensyDescription');
    const campaignForm = document.getElementById('createCampaignForm');

    function toggleAisensyField() {
        const isWhatsApp = campaignType.value === 'whatsapp';
        
        if (isWhatsApp) {
            // Make field required for WhatsApp
            aisensyCampaignId.required = true;
            aisensyRequired.style.display = 'inline';
            aisensyHelp.textContent = '(Required for WhatsApp campaigns)';
            aisensyHelp.className = 'text-danger ms-1';
            aisensyDescription.className = 'form-text text-warning';
            aisensyDescription.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Aisensy Campaign ID is required for WhatsApp campaigns to connect with your WhatsApp Business account.';
            
            // Add visual emphasis
            aisensyCampaignId.classList.add('border-warning');
        } else {
            // Make field optional for other types
            aisensyCampaignId.required = false;
            aisensyRequired.style.display = 'none';
            aisensyHelp.textContent = '(Optional)';
            aisensyHelp.className = 'text-muted ms-1';
            aisensyDescription.className = 'form-text text-muted';
            aisensyDescription.textContent = 'For WhatsApp campaigns, you must provide the Aisensy Campaign ID to connect with your WhatsApp Business account.';
            
            // Remove visual emphasis
            aisensyCampaignId.classList.remove('border-warning');
        }
    }

    // Initial state
    toggleAisensyField();

    // Update on type change
    campaignType.addEventListener('change', toggleAisensyField);

    // Form validation
    campaignForm.addEventListener('submit', function(e) {
        const isWhatsApp = campaignType.value === 'whatsapp';
        const aisensyValue = aisensyCampaignId.value.trim();
        
        if (isWhatsApp && !aisensyValue) {
            e.preventDefault();
            alert('Please enter Aisensy Campaign ID for WhatsApp campaigns.');
            aisensyCampaignId.focus();
            aisensyCampaignId.classList.add('is-invalid');
        }
    });

    // Remove invalid state when user starts typing
    aisensyCampaignId.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});
</script>
<script>
    // Open campaign modal
    // Handle all elements with class "createCampaignBtn"
document.querySelectorAll('.createCampaignBtn').forEach(button => {
    button.addEventListener('click', function() {
        const campaignModal = new bootstrap.Modal(document.getElementById('createCampaignModal'));
        campaignModal.show();
    });
});

// Also handle quick action cards
document.querySelectorAll('.action-card').forEach(card => {
    if (card.classList.contains('createCampaignBtn') || card.querySelector('.fa-bullhorn')) {
        card.addEventListener('click', function() {
            const campaignModal = new bootstrap.Modal(document.getElementById('createCampaignModal'));
            campaignModal.show();
        });
    }
});


    // Dropzone Config
    Dropzone.autoDiscover = false;
    const leadDropzone = new Dropzone("#leadDropzone", {
        maxFilesize: 5, // MB
        acceptedFiles: ".xlsx,.xls,.csv",
        parallelUploads: 1,
        dictDefaultMessage: "Drop files here to upload",
        init: function() {
            this.on("success", function(file, response) {
                console.log('Upload success', response);
                if(response.success) {
                    alert('Leads imported successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            });
            this.on("error", function(file, response) {
                console.error('Upload failed', response);
                alert('Error uploading file: ' + (response.message || 'Unknown error'));
            });
        }
    });

    // Form submission handling
    document.getElementById('addLeadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Lead added successfully!');
                bootstrap.Modal.getInstance(document.getElementById('addLeadModal')).hide();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the lead.');
        });
    });

    document.getElementById('createCampaignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Campaign created successfully!');
                bootstrap.Modal.getInstance(document.getElementById('createCampaignModal')).hide();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the campaign.');
        });
    });

    // Fix for modal close buttons - explicit handling
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const modal = this.closest('.modal');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // Fallback if no instance found
                    $(modal).modal('hide');
                }
            }
        });
    });

    // Also handle ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show').forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }
    });
</script>

@stop