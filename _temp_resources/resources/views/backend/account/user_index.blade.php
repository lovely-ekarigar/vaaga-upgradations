@extends('frontend.layout.sub-master')
@section('title')
<title>Student Profile| {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <div class="row">
                <div class="col-md-8">
                   <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Personal Details</h6>
                </div>
                <div class="col-md-4">
                   <div class="profile-share d-flex align-items-center justify-content-center">
<a href="password" class="btn btn-success">Change Password</a>
</div> 
                </div>
              </div>
             
            
                <div>
                    <div class="checkout-form personal-address add-course-info ">
<form action="" method="post" enctype="multipart/form-data">
{{csrf_field()}}

                                    @if(Session::has('flash_message'))      
                              <div class="alert {{ Session::get('alert-class', 'alert-success') }}"> {{ Session::get('flash_message') }} <i class='bx bx-close'></i>
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
                              </div>
                            @endif
                            @if(Session::has("flash_error"))
                      <div class="alert {{ Session::get('alert-class', 'alert-danger') }}"> {{ Session::get('flash_error') }} <i class='bx bx-close'></i>
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
                  </div>
                  @endif
<div class="row">
<div class="col-lg-6"> 
<div class="form-group">
<label class="form-control-label">First Name</label>
<input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}"> 
</div>
</div>
<div class="col-lg-6"> 
<div class="form-group">
<label class="form-control-label">Middle Name</label>
<input type="text" class="form-control" name="middle_name" value="{{ $user->middle_name }}"> 
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Last Name</label>
<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Gender</label>
<select class="form-select select country-select" name="gender" >
  <option value="Male" <?php if($user->gender=="Male"){ echo "selected";} ?>>Male</option>
<option value="Female" <?php if($user->gender=="Female"){ echo "selected";} ?>>Female</option>
</select>
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Phone</label>
<input type="tel" class="form-control" name="phone" id="phoneInput" value="{{ $user->phone }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Email</label>
<input type="text" class="form-control" name="email" value="{{ $user->email }}" disabled>
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Date Of Birth</label>
<input type="date" class="form-control" name="dob" value="{{ $user->dob }}" max="{{ date('Y-m-d') }}" oninput="validateDate()">

</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Address</label>
<input type="text" class="form-control" name="address" value="{{ $user->address }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-labels">State</label>
<input type="text" class="form-control" name="state" value="{{ $user->state }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-labels">Country</label>
<input type="text" class="form-control" name="country" value="{{ $user->country }}">
</div>
</div>

<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">City</label>
<input type="text" class="form-control" name="city" value="{{ $user->city }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">ZipCode</label>
<input type="number" class="form-control" name="pincode" value="{{ $user->pincode }}">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Profile Picture</label>
<input type="file" class="form-control" name="image" >
  <span style="color: #aaa;" class="pt-2">Image extension should be <b>jpg, jpeg, png</b> only</span>
</div>
</div>
<div class="update-profile text-end">
<button type="submit" class="btn btn-primary">Update Profile</button>
</div>
</div>
</form>
</div>
                </div>
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

<script>
    const phoneInput = document.getElementById('phoneInput');

    phoneInput.addEventListener('input', function () {
        const phoneNumber = phoneInput.value.replace(/\D/g, '');

        if (phoneNumber.length > 10) {
            phoneInput.value = phoneNumber.slice(0, 10);
        }
    });
</script>

<script>
function validateDate() {
  var inputDate = document.getElementById('dob').value;
  var currentDate = new Date().toISOString().split('T')[0];

  if (inputDate > currentDate) {
    alert('Please select a past or today\'s date.');
    document.getElementById('dob').value = currentDate;
  }
}
</script>

@stop