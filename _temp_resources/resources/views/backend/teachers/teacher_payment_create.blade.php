@extends('backend.layouts.app')
@section('title', __('Tutor Payments').' | '.app_name())

@push('after-styles')
   
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Create Tutor Payments</h3>
           <!--  <div class="float-right">
                <a href="{{ route('admin.teacher_payments') }}"
                   class="btn btn-success">View</a>

            </div> -->
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.teacher_payments_store'], 'files' => true,]) !!}

                    <div class="row ">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Tutor Name <span class="text-danger">*</span></label>
                            @if(request()->get('teacher_id'))

                            <input type="text" name="teacher_id" value="{{$teacher_list_data->first_name}} {{$teacher_list_data->last_name}}" class="form-control" readonly>
                            <input type="hidden" name="teacher_id" value="{{$teacher_list_data->id}}">

                            @else

                            <select class="form-control " name="teacher_id">
                                <option value="" selected disabled>Select Tutor....</option>
                                @foreach($teacher_list as $teacher)
                                <option value="{{$teacher->id}}" @if (old('teacher_id') == $teacher->id) selected @endif>{{$teacher->name}}</option>
                                @endforeach
                            </select>

                            @endif
                             
                        </div>

                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Payment Mode <span class="text-danger">*</span></label>
                            <select class="form-control " name="payment_mode">
                                <option value="" selected disabled>Select Tutor....</option>
                                <option value="cash" @if (old('payment_mode') == 'cash') selected @endif>Cash</option>
                                <option value="online" @if (old('payment_mode') == 'online') selected @endif>Online</option>
                                <option value="bank-transfer" @if (old('payment_mode') == 'bank-transfer') selected @endif>Bank Transfer </option>
                            </select>
                          
                        </div>
                        
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" value="{{old('amount')}}" class="form-control ">
                           
                        </div>

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Date <span class="text-danger">*</span></label>
                            <input type="text" name="date" value="{{ date('d-m-Y ') }}" class="form-control" readonly>
                         
                        </div>

                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Remark <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="remark" rows="3" cols="3">{{old('remark')}}</textarea>
                         
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
