@extends('backend.layouts.app')

@section('title', __('Batch Progress').' | '.app_name())

@section('content')
<style type="text/css">
    .bd-bg-info {
        border: 1px solid #17a2b8;
        background-color: #17a2b8;
        color: #fff;
        padding: 1px 6px;
        border-radius: 5px;
    }

    .bd-bg-secondary {
        border: 1px solid #6c757d;
        background-color: #6c757d;
        color: #fff;
        padding: 1px 6px;
        border-radius: 5px;
    }

    .bd-bg-Success {
        border: 1px solid #198754;
        background-color: #198754;
        color: #fff;
        padding: 1px 6px;
        border-radius: 5px;
    }

    .f-12 {
        font-size: 12px !important;
    }
</style>

<div class="card">
    <div class="card-header">

        <h3 class="page-title d-inline">Batch Progress</h3>


    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled">
                    @foreach($list as $lesson)
                    <li class="pb-3">
                        <h6 class="pb-2">{{$lesson->title}}</h6>

                        @foreach($lesson->lesson_lists as $ll)
                        @foreach($lession_complete_list as $lession_complete)
                        @if($ll->id == $lession_complete->lession_id)

                        @if($lession_complete->status == 'completed')
                        <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-Success f-12">Completed</span></p>
                        @elseif($lession_complete->status == 'ongoing')
                        <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-info f-12">Ongoing</span></p>
                        @else
                        <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-secondary f-12">Not Started</span></p>
                        @endif

                        @endif


                        @endforeach

                        @endforeach

                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@if ($logged_in_user->isAdmin())
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Student Commitments</h5>
    </div>
    <form method="POST">
        @csrf
        <div class="card-body">
            @foreach($userProgress as $userItem)
            <div class="border rounded p-3 mb-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">
                        <i class="fas fa-user-graduate text-info me-2"></i>
                        {{ $userItem->user->name ?? 'Unknown User' }}
                    </h6>
                    <span class="badge bg-secondary">UID: {{ $userItem->uid }}</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Total Classes</label>
                        <input type="text" name="commitments[{{ $userItem->uid }}][total_class]"
                            value="{{ $userItem->commit->total_class ?? '' }}" class="form-control"
                            placeholder="Enter total classes">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Total Tests</label>
                        <input type="text" name="commitments[{{ $userItem->uid }}][total_test]"
                            value="{{ $userItem->commit->total_test ?? '' }}" class="form-control"
                            placeholder="Enter total tests">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Batch Joining date</label>
                        <input type="date" name="commitments[{{ $userItem->uid }}][joining_date]"
                            value="{{ $userItem->commit->joining_date ?? '' }}" class="form-control"
                            placeholder="Enter date">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Batch Completion date</label>
                        <input type="date" name="commitments[{{ $userItem->uid }}][completion_date]"
                            value="{{ $userItem->commit->completion_date ?? '' }}" class="form-control"
                            placeholder="Enter date">
                    </div>




                </div>
            </div>
            @endforeach
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Save Commitments
            </button>
        </div>
    </form>
</div>

@endif

@stop

@push('after-scripts')

@endpush