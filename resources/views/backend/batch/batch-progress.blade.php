@extends('backend.layouts.app')

@section('title', __('Batch Progress').' | '.app_name())

@section('content')
<style type="text/css">
    .bd-bg-info{
            border: 1px solid #17a2b8;
    background-color: #17a2b8;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .bd-bg-secondary{
            border: 1px solid #6c757d;
    background-color: #6c757d;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .bd-bg-Success{
            border: 1px solid #198754;
    background-color: #198754;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .f-12{
        font-size: 12px !important;
    }
    .student-commitments-header {
        background-color: #17a2b8;
        color: white;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 16px;
    }
    .student-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 15px;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .student-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    .student-name {
        font-weight: 600;
        font-size: 16px;
        color: #333;
    }
    .student-uid {
        background-color: #6c757d;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
    }
    .commitment-field label {
        font-size: 12px;
        color: #666;
        margin-bottom: 3px;
        display: block;
    }
    .commitment-field input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
</style>

    {{-- Student Commitments Section --}}
    <div class="card mb-4">
        <div class="student-commitments-header">
            <i class="fa fa-users mr-2"></i> Student Commitments
        </div>
        <div class="card-body">
            <form id="studentCommitmentsForm" method="POST" action="{{ route('admin.batch-progress-list.update', $batch_list->id) }}">
                @csrf
                <div class="row">
                    @foreach($students as $student)
                    <div class="col-md-6">
                        <div class="student-card">
                            <div class="student-card-header">
                                <span class="student-name">
                                    <i class="fa fa-user mr-2 text-info"></i>{{ $student->name }}
                                </span>
                                <span class="student-uid">UID: {{ $student->id }}</span>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="commitment-field">
                                        <label>Total Classes</label>
                                        <input type="number" name="commitments[{{ $student->id }}][total_classes]" 
                                               value="{{ $student->commitment ? $student->commitment->total_classes : 30 }}" 
                                               min="0" class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="commitment-field">
                                        <label>Total Tests</label>
                                        <input type="number" name="commitments[{{ $student->id }}][total_tests]" 
                                               value="{{ $student->commitment ? $student->commitment->total_tests : 8 }}" 
                                               min="0" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="commitment-field">
                                        <label>Batch Joining date</label>
                                        <input type="date" name="commitments[{{ $student->id }}][joining_date]" 
                                               value="{{ $student->commitment ? $student->commitment->joining_date : date('Y-m-d') }}" 
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="commitment-field">
                                        <label>Batch Completion date</label>
                                        <input type="date" name="commitments[{{ $student->id }}][completion_date]" 
                                               value="{{ $student->commitment ? $student->commitment->completion_date : '' }}" 
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="commitments[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save mr-2"></i> Save Commitments
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Batch Progress Section --}}
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

@stop

@push('after-scripts')
   
@endpush
