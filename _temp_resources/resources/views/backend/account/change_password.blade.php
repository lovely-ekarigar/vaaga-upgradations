@extends('frontend.layout.sub-master')
@section('title')
<title>Change Password | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Change Password</h6>
                <div>
                    <form action="" method="post">
      {{csrf_field()}}

                                    @if(Session::has('flash_message'))      
                              <div class="alert {{ Session::get('alert-class', 'alert-danger') }}"> {{ Session::get('flash_message') }} <i class='bx bx-close'></i>
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
                              </div>
                            @endif
<div class="row">
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Old Password</label>
<input type="password" class="form-control" name="old_password" value="">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">New Password</label>
<input type="password" class="form-control" name="new_password" value="">
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label class="form-control-label">Password Confirmation</label>
<input type="password" class="form-control" name="password_confirmation" value="">
</div>
</div>

<div class="update-profile text-end">
<button type="submit" class="btn btn-primary">Update</button>
</div>
</div>
</form>
                </div>
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop