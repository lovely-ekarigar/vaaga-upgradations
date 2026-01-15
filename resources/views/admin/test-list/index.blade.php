
<?php

use Illuminate\Support\Str;
?>
@extends('backend.layouts.app')

@section('title', 'Test List | ' . env('APP_NAME'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Test Lists</h5>
                <a href="{{ route('admin.testseries.add-test',['id'=>$id]) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus"></i> Add Test
                </a>
            </div>

            <div class="card-body">
                @if($testLists->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Test Name</th>
                                    <th>Description</th>
                                    <th>Total Questions</th>
                                    <th>Duration</th>
                                    <th>Test Type</th>
                                    <th>Sort Order</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testLists as $index => $test)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $test->name ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($test->description, 50) ?? '-' }}</td>
                                        <td>{{ $test->total_questions }}</td>
                                        <td>{{ $test->duration }} min</td>
                                      
                                        <td>
                                            @if($test->is_prev_year=='1')
                                            Previous Year
                                            @else
                                                Regular                                            
                                            @endif
                                            
                                        </td>
                                          <td><input type="number" value="{{$test->sort_order}}" data-id="{{$test->id}}" class="form-control sort_order" style="width:60px;" /></td>
                                        <td>
                                            @if($test->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $test->created_at ? $test->created_at->format('d M Y') : '-' }}</td>
                                     <td class="text-center">
                                           
    <div class="d-flex justify-content-center gap-2" style="gap:10px;">
         <a href="{{route('admin.testseries.subjects',[$test->id])}}" class="btn btn-sm btn-outline-info">Sections</a>
        <a href="{{ route('admin.testseries.edit-test', $test->id) }}" 
           class="btn btn-sm btn-warning" 
           data-bs-toggle="tooltip" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('admin.testseries.delete-test', $test->id) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this test?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" 
                    data-bs-toggle="tooltip" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No tests found.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('page_js')

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
        url: '/user/test-series/{{$id}}/test-list', // Change this to your actual route
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
