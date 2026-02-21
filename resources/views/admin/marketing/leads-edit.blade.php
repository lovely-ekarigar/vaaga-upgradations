@extends('backend.layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Edit Lead: {{ $lead->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.leads.update', $lead) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $lead->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', $lead->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $lead->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Source</label>
                                <select class="form-select @error('source') is-invalid @enderror" name="source">
                                    <option value="website" {{ $lead->source == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="facebook" {{ $lead->source == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="google_ads" {{ $lead->source == 'google_ads' ? 'selected' : '' }}>Google Ads</option>
                                    <option value="referral" {{ $lead->source == 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="instagram" {{ $lead->source == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="other" {{ $lead->source == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Grade</label>
                                <input type="text" class="form-control" name="grade" value="{{ old('grade', $lead->grade) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" value="{{ old('subject', $lead->subject) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Skip</label>
                                <input type="text" class="form-control" name="skip" value="{{ old('skip', $lead->skip) }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ $lead->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $lead->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>Converted</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $lead->notes) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.marketing.leads') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Leads
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
