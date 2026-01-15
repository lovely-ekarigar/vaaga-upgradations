@extends('backend.layouts.app')
@section('title', __('Teacher Payments').' | '.app_name())

@push('after-styles')
   
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Edit Teacher Payments</h3>
            <div class="float-right">
                <a href="{{ route('admin.teacher_payments') }}"
                   class="btn btn-success">View</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    
                    <form method="post">
                        @csrf

                    <div class="row ">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Teacher Name <span class="text-danger">*</span></label>
                            <select class="form-control " name="teacher_id">
                                <option value="" selected disabled>Select Teacher....</option>
                                @foreach($teacher_list as $tea)
                                <option value="{{$tea->id}}" @if ($teacher->teacher_id == $tea->id) selected @endif>{{$tea->name}}</option>
                                @endforeach
                            </select>
                             
                        </div>

                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Payment Mode <span class="text-danger">*</span></label>
                            <select class="form-control " name="payment_mode">
                                <option value="" selected disabled>Select Teacher....</option>
                                <option value="cash" @if ($teacher->payment_mode == 'cash') selected @endif>Cash</option>
                                <option value="online" @if ($teacher->payment_mode == 'online') selected @endif>Online</option>
                                <option value="bank-transfer" @if ($teacher->payment_mode == 'bank-transfer') selected @endif>Bank Transfer </option>
                            </select>
                          
                        </div>
                        
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" value="{{$teacher->amount}}" class="form-control ">
                           
                        </div>

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Date <span class="text-danger">*</span></label>
                            <input type="text" name="date" value="{{$teacher->date}}" class="form-control " readonly>
                         
                        </div>

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Remark <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="remark" rows="3" cols="3">{{$teacher->remark}}</textarea>
                        
                        </div>


                        <div class="col-12 form-group text-end">

                            {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>
                </form>
                    


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
   
@endpush
