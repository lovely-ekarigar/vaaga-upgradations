@extends('backend.layouts.app')

@section('title', 'Assign Mock Tests - Batch | '.app_name())

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="page-title float-left mb-0">Assign Mock Tests - Batch #{{ $id }}</h3>
        <div class="float-right">
            <a href="{{ route('admin.batch.index') }}" class="btn btn-secondary">Back to Batches</a>
        </div>
    </div>
    
    <div class="card-body">
        <div id="course-info" class="mb-3"></div>
        
        <div id="loading-state">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3">Loading available mock tests...</p>
            </div>
        </div>
        
        <div id="error-state" style="display: none;">
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i> <span id="error-message">Error loading mock tests</span>
            </div>
        </div>
        
        <form id="assignMockTestsForm" method="POST" action="{{ route('admin.batch.save-mock-tests', $id) }}" style="display: none;">
            @csrf
            <input type="hidden" name="batch_id" value="{{ $id }}">
            
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> <strong>Note:</strong> Select mock tests to assign to this batch.
            </div>
            
            <div id="mock-tests-list"></div>
            
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save Mock Tests
                    </button>
                </div>
            </div>
        </form>
        
        <div id="no-mock-tests" style="display: none;">
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> No mock tests available for this batch.
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchId = {{ $id }};
        
        // Fetch available mock tests
        fetch('{{ url("/user/batch") }}/' + batchId + '/available-mock-tests')
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading-state').style.display = 'none';
                
                if (data.success) {
                    document.getElementById('course-info').innerHTML = '<h5>Course: <span class="text-primary">' + data.courseName + '</span></h5>';
                    
                    if (data.mockTests && data.mockTests.length > 0) {
                        let html = '';
                        let currentSeriesId = null;
                        
                        data.mockTests.forEach(function(mockTest) {
                            var isChecked = data.assignedMockIds && data.assignedMockIds.includes(mockTest.id) ? 'checked' : '';
                            
                            // Show series header if it's a new series
                            if (currentSeriesId !== mockTest.series_id) {
                                if (currentSeriesId !== null) {
                                    html += '</div></div>'; // Close previous card
                                }
                                currentSeriesId = mockTest.series_id;
                                html += '<div class="card mb-3">';
                                html += '<div class="card-header bg-primary text-white">';
                                html += '<strong>' + mockTest.series_name + '</strong>';
                                if (mockTest.series_detail) {
                                    html += '<br><small>' + mockTest.series_detail + '</small>';
                                }
                                html += '</div>';
                                html += '<div class="card-body">';
                            }
                            
                            // Individual mock test checkbox
                            html += '<div class="custom-control custom-checkbox mb-2">';
                            html += '<input type="checkbox" class="custom-control-input" id="mock_test_' + mockTest.id + '" name="mock_test_ids[]" value="' + mockTest.id + '" ' + isChecked + '>';
                            html += '<label class="custom-control-label" for="mock_test_' + mockTest.id + '">';
                            html += '<strong>' + mockTest.name + '</strong>';
                            if (mockTest.description) {
                                html += '<br><small class="text-muted">' + mockTest.description + '</small>';
                            }
                            html += '<br><small>';
                            if (mockTest.total_questions) {
                                html += '<span class="badge badge-info mr-1">Questions: ' + mockTest.total_questions + '</span>';
                            }
                            if (mockTest.duration) {
                                html += '<span class="badge badge-success">Duration: ' + mockTest.duration + ' min</span>';
                            }
                            html += '</small>';
                            html += '</label>';
                            html += '</div>';
                        });
                        
                        // Close last card
                        if (currentSeriesId !== null) {
                            html += '</div></div>';
                        }
                        
                        document.getElementById('mock-tests-list').innerHTML = html;
                        document.getElementById('assignMockTestsForm').style.display = 'block';
                    } else {
                        document.getElementById('no-mock-tests').style.display = 'block';
                    }
                } else {
                    document.getElementById('error-message').textContent = data.message || 'Failed to load mock tests';
                    document.getElementById('error-state').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loading-state').style.display = 'none';
                document.getElementById('error-message').textContent = 'Error loading mock tests: ' + error.message;
                document.getElementById('error-state').style.display = 'block';
            });
    });
</script>
@endpush
