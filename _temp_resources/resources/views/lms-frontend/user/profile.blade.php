@extends('frontend.layout.user-master')
@section('content')

<div class="page-content">
<div class="container">
<div class="row">

 @include("frontend.include.user-menu")


<div class="col-xl-9 col-md-8">
<div class="settings-widget profile-details">
<div class="settings-menu p-0">
<div class="profile-heading">
<h4>Personal Details</h4>
<p>Edit your personal information and address.</p>
</div>
<div class="checkout-form personal-address add-course-info ">
<div class="personal-info-head">

</div>
<form action="#">
<div class="row">
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">First Name</label>
<input type="text" class="form-control" placeholder="Enter your first Name">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Last Name</label>
<input type="text" class="form-control" placeholder="Enter your last Name">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Phone</label>
<input type="text" class="form-control" placeholder="Enter your Phone">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Email</label>
<input type="text" class="form-control" placeholder="Enter your Email">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Birthday</label>
<input type="text" class="form-control" placeholder="Birth of Date">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-label">Country</label>
<select class="form-select select country-select" name="sellist1">
<option>Select country</option>
<option>India</option>
<option>America</option>
<option>London</option>
</select>
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Address Line 1</label>
<input type="text" class="form-control" placeholder="Address">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Address Line 2 (Optional)</label>
<input type="text" class="form-control" placeholder="Address">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">City</label>
<input type="text" class="form-control" placeholder="Enter your City">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">ZipCode</label>
<input type="text" class="form-control" placeholder="Enter your Zipcode">
</div>
</div>
<div class="update-profile">
<button type="button" class="btn btn-primary">Update Profile</button>
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

@stop