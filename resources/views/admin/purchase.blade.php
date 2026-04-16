@extends('backend.layouts.app')

@section('title')
Test Series Management | {{ env('APP_NAME') }}
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
                    <h5 class="mb-0 text-black">Test Series Purchases</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="purchaseTable" class="table table-striped table-bordered">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseList as $key => $purchase)
                                <tr>
                                    <td>{{ $purchaseList->firstItem() + $key }}</td>
                                    <td>{{ $purchase->user->name ?? '-' }}</td>
                                    <td>{{ $purchase->user->email ?? '-' }}</td>
                                    <td>{{ $purchase->user->phone ?? '-' }}</td>
                                    <td>{{ $purchase->course->title ?? '-' }}</td>
                                    <td>{{ number_format($purchase->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($purchase->payment_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $purchase->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.testseries.purchaseInvoice', ['id' => $purchase->id, 'type' => 'show']) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                                        <a href="{{ route('admin.testseries.purchaseInvoice', ['id' => $purchase->id, 'type' => 'download']) }}" class="btn btn-sm btn-success">Download</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No purchases found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {!! $purchaseList->links() !!}
                </div>
            </div>
        </div>

    </div>
</div>
@stop
