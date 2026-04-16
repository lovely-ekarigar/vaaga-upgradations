@extends('backend.layouts.app')
@section('title', 'Create Order | '.app_name())

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline mb-0">Create Order</h3>
            <div class="float-right">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-default border">
                    @lang('strings.backend.general.app_back_to_list')
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">User <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="course_ids">Courses <span class="text-danger">*</span></label>
                            <select name="course_ids[]" id="course_ids" class="form-control" multiple required>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ in_array($course->id, old('course_ids', [])) ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple courses</small>
                            @error('course_ids')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ old('discount', 0) }}">
                            @error('discount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gst">GST</label>
                            <input type="number" step="0.01" name="gst" id="gst" class="form-control" value="{{ old('gst', 0) }}">
                            @error('gst')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="course_mode">Course Mode</label>
                            <select name="course_mode" id="course_mode" class="form-control">
                                <option value="">Select Course Mode</option>
                                <option value="onetoone_full" {{ old('course_mode') == 'onetoone_full' ? 'selected' : '' }}>1:1 Full Course</option>
                                <option value="onetoone_monthly" {{ old('course_mode') == 'onetoone_monthly' ? 'selected' : '' }}>1:1 Monthly Subscription</option>
                                <option value="onetomany_full" {{ old('course_mode') == 'onetomany_full' ? 'selected' : '' }}>1:N Full Course</option>
                                <option value="onetomany_monthly" {{ old('course_mode') == 'onetomany_monthly' ? 'selected' : '' }}>1:N Monthly Subscription</option>
                                <option value="regular_monthly" {{ old('course_mode') == 'regular_monthly' ? 'selected' : '' }}>Regular Monthly Subscription</option>
                                <option value="regular_monthly_1" {{ old('course_mode') == 'regular_monthly_1' ? 'selected' : '' }}>1:1 Regular Monthly Subscription</option>
                                <option value="quarterly" {{ old('course_mode') == 'quarterly' ? 'selected' : '' }}>Quarterly Subscription</option>
                                <option value="monthly" {{ old('course_mode') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="full" {{ old('course_mode') == 'full' ? 'selected' : '' }}>Full Course</option>
                            </select>
                            @error('course_mode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control" required>
                                <option value="0" {{ old('payment_type', '0') == '0' ? 'selected' : '' }}>In Progress</option>
                                <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Stripe/Card</option>
                                <option value="2" {{ old('payment_type') == '2' ? 'selected' : '' }}>PayPal</option>
                                <option value="3" {{ old('payment_type') == '3' ? 'selected' : '' }}>Offline</option>
                                <option value="4" {{ old('payment_type') == '4' ? 'selected' : '' }}>Razorpay</option>
                            </select>
                            @error('payment_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="0" {{ old('status', '0') == '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_cycle">Total Cycle</label>
                            <input type="number" name="total_cycle" id="total_cycle" class="form-control" value="{{ old('total_cycle') }}" min="0">
                            @error('total_cycle')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="paid_cycle">Paid Cycle</label>
                            <input type="number" name="paid_cycle" id="paid_cycle" class="form-control" value="{{ old('paid_cycle', 0) }}" min="0">
                            @error('paid_cycle')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Create Order</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-default border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@stop

@push('after-scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 for better UX
        $('#user_id').select2({
            placeholder: 'Select User'
        });
        
        $('#course_ids').select2({
            placeholder: 'Select Courses',
            allowClear: true
        });
    });
</script>
@endpush
