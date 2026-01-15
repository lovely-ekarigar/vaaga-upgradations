@extends('backend.layouts.app')
@section('title')
Add New Team - {{ env('APP_NAME') }}
@stop
@section('content')

<div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Add New Team</h3>
            <div class="float-right">
                <a href="{{ route('admin.team.list') }}"
                   class="btn btn-success">View Teams</a>
            </div>
        </div>
        
         <div class="card-body">
             <form method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                     <label for="compname" class="form-label">Name<span class="text-danger">*</span></label>
                     <input type="text" required value="{{ old('name') }}" class="form-control" id="compname" name="name" placeholder="Name">
                </div>

                <div class="col-12 col-lg-6 form-group">
                  <label for="designation" class="form-label">Designation<span class="text-danger">*</span></label>
                     <input type="text" required value="{{ old('designation') }}" class="form-control" id="designation" name="designation" placeholder="Designation">
            </div>
                <div class="col-12 col-lg-6 form-group">
                  <label for="image" class="form-label">Image</label>
                     <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
            </div>
            <div class="col-12 col-lg-6 form-group">
                  <label for="facebook" class="form-label">Facebook</label>
                     <input type="text" required value="{{ old('facebook') }}" class="form-control" id="facebook" name="facebook" placeholder="Facebook">
            </div>
            <div class="col-12 col-lg-6 form-group">
                  <label for="twitter" class="form-label">Twitter</label>
                     <input type="text" required value="{{ old('twitter') }}" class="form-control" id="twitter" name="twitter" placeholder="Twitter">
            </div>
            <div class="col-12 col-lg-6 form-group">
                  <label for="linkedin" class="form-label">Linkedin</label>
                     <input type="text" required value="{{ old('linkedin') }}" class="form-control" id="linkedin" name="linkedin" placeholder="Linkedin">
            </div>
            <div class="col-12 col-lg-6 form-group">
                  <label for="instagram" class="form-label">Instagram</label>
                     <input type="text" required value="{{ old('instagram') }}" class="form-control" id="instagram" name="instagram" placeholder="Instagram">
            </div>
            <div class="col-12 col-lg-6 form-group">
                  <label for="youtube" class="form-label">YouTube</label>
                     <input type="text" required value="{{ old('youtube') }}" class="form-control" id="youtube" name="youtube" placeholder="YouTube">
            </div>
            
            <div class="col-12 col-lg-12 form-group">
                  <label for="summernote" class="form-label">About</label>
                     <textarea  required value="{{ old('about') }}" class="form-control" id="summernote" name="about" rows="4" placeholder="About"></textarea>
            </div>
        </div>
         <div class="row pt-3">

                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light ">
                       Submit
                    </button>
                </div>

            </div>
            </form>
</div>


@endsection