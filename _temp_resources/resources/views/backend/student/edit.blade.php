@extends('backend.layouts.app')
@section('title', __('labels.backend.student.title').' | '.app_name())

@push('after-styles')
   
@endpush
@section('content')
    
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Edit Student</h3>
            <div class="float-right">
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-success">View Student</a>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                @csrf
            <div class="row ">
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $student->first_name}}" name="first_name">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Middle Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $student->middle_name}}" name="middle_name">
                </div>
                 <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $student->last_name}}" name="last_name">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" value="{{ $student->email}}" name="email">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Mobile <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="phoneInput" value="{{ $student->phone}}" name="phone">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Birthday </label>
                    <input type="date" class="form-control" value="{{ $student->dob}}" name="dob">
                </div>
                <div class="form-group col-12 col-lg-4">
                    <label class="radio-inline mr-3 mb-0">
                        <input type="radio" name="gender" value="female" <?php if($student['gender']=="female"){ echo "checked";} ?>> Female
                    </label>
                    <label for="dec" class="control-label">Gender <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <label class="radio-inline mr-3 mb-0">
                            <input type="radio" name="gender" value="male" <?php if($student['gender']=="male"){ echo "checked";} ?>> Male
                        </label>
                        </div>
                    </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Image </label>
                    <input type="file" class="form-control" value="{{asset($student->avatar_location)}}" accept="image/*" name="image">
                    <span style="color: #aaa;" class="pt-2">Image extension should be <b>jpg, jpeg, png</b> only</span>
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Country </label>
                    <input type="text" class="form-control" value="{{ $student->country}}" name="country">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">State </label>
                    <input type="text" class="form-control" value="{{ $student->state}}" name="state">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">City </label>
                    <input type="text" class="form-control" value="{{ $student->city}}" name="city">
                </div> 
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Pin-Code </label>
                    <input type="number" class="form-control" value="{{ $student->pincode}}" name="pincode">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="dec" class="control-label">Address </label>
                    <input type="text" class="form-control" value="{{ $student->address}}" name="address">
                </div>
                     
                <div class="col-12 form-group text-center">
                    <button type="submit" class="btn btn-info">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    
@endsection

@push('after-scripts')
<script>
    const phoneInput = document.getElementById('phoneInput');

    phoneInput.addEventListener('input', function () {
        const phoneNumber = phoneInput.value.replace(/\D/g, '');

        if (phoneNumber.length > 10) {
            phoneInput.value = phoneNumber.slice(0, 10);
        }
    });
</script>
@endpush


