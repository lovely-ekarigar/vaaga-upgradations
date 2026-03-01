@extends('backend.layouts.app')

@section('title')
Question Reports | {{ env('APP_NAME') }}
@stop

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Question Reports</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="reportTable" class="table table-striped table-bordered">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                
                                <th>Question ID</th>
                                <th>Message</th>
                                <th>Reported At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $key => $report)
                                {{-- Debug row - remove after fixing --}}
                                @if($key === 0)
                                <tr class="table-warning">
                                    <td colspan="5">
                                        <small><strong>Debug:</strong></small>
                                        <pre style="font-size:10px; margin:0;">{{ json_encode($report->toArray(), JSON_PRETTY_PRINT) }}</pre>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ ($reports->firstItem() ?? 1) + $key }}</td>
                                    <td>{{ $report->user->name ?? '-' }}</td>
                                    <td><a href="/user/questions-bank/{{$report->question_id}}/edit" target="_blank">{{ $report->question_id }}</a></td>
                                    <td>{{ $report->message ?? '-' }}</td>
                                    <td>{{ $report->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No reports found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($reports, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {!! $reports->links() !!}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@stop
