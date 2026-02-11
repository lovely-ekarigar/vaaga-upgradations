@extends('backend.layouts.app')
@section('title', __('Teacher Attendance').' | '.app_name())

@push('after-styles')
   
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Create Teacher Attendance</h3>
             @if(auth()->user()->hasRole('teacher'))
            <div class="float-right">
                <a href="{{ route('admin.teacher_attendance') }}"
                   class="btn btn-success">View</a>

            </div>
              @endif
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.teacher_attendance_store'], 'files' => true,]) !!}

                    <div class="row ">
                        
                        <input type="hidden" value="{{auth()->user()->id}}" name="teacher_id">

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Batch <span class="text-danger">*</span></label>
                            <select class="form-control " name="batch_id">
                                <option value="" selected disabled>Select Batch....</option>
                                @foreach($batch_list as $batches)
                                <option value="{{$batches->id}}" @if (old('batch_id') == $batches->id) selected @endif>{{$batches->name}}</option>
                                @endforeach
                            </select>
                             
                        </div>
                        
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Hours <span class="text-danger">*</span></label>
                            <input type="number" name="hours" value="{{old('hours')}}" class="form-control ">
                           
                        </div>

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" value="{{old('date')}}" class="form-control ">
                         
                        </div>

                        


                        <div class="col-12 form-group text-end">

                            {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    
@endpush
