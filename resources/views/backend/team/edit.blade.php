@extends('backend.layouts.app')

@section('title')
Edit Team - {{ env('APP_NAME') }}
@stop

{{-- OUTSIDE @section --}}
@push('after-styles')
<style>
    .cke_notification_warning {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="page-title float-left mb-0">Edit Team</h3>
        <div class="float-right">
            <a href="{{ route('admin.team.list') }}" class="btn btn-success">View Teams</a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="compname" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" required value="{{ $team->name }}" class="form-control" id="compname" name="name">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="designation" class="form-label">Designation<span class="text-danger">*</span></label>
                    <input type="text" required value="{{ $team->designation }}" class="form-control" id="designation" name="designation">
                </div>

                <div class="col-12 col-lg-4 form-group">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>

                <div class="col-12 col-lg-2 form-group pt-4">
                    <img src="/{{ $team->image }}" style="height:50px">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="slug" class="form-label">Slug<span class="text-danger">*</span></label>
                    <input type="text" required value="{{ $team->slug }}" class="form-control" id="slug" name="slug">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="facebook" class="form-label">Facebook</label>
                    <input type="text" value="{{ $team->facebook }}" class="form-control" id="facebook" name="facebook">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="twitter" class="form-label">Twitter</label>
                    <input type="text" value="{{ $team->twitter }}" class="form-control" id="twitter" name="twitter">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="linkdin" class="form-label">LinkedIn</label>
                    <input type="text" value="{{ $team->linkdin }}" class="form-control" id="linkdin" name="linkdin">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="instagram" class="form-label">Instagram</label>
                    <input type="text" value="{{ $team->instagram }}" class="form-control" id="instagram" name="instagram">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="youtube" class="form-label">YouTube</label>
                    <input type="text" value="{{ $team->youtube }}" class="form-control" id="youtube" name="youtube" placeholder="YouTube">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="about" class="form-label">About</label>
                    <textarea class="form-control" id="about" name="about" rows="10">{!! $team->about !!}</textarea>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light">Update</button>
                </div>
            </div>
        </form>
    </div>{{-- Card-body close --}}
</div>{{-- card close --}}
@endsection

{{-- OUTSIDE @section, with load event --}}
@push('after-scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    window.addEventListener('load', function () {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('about', {
                height: 300,
                removePlugins: 'elementspath',
                resize_enabled: true
            });
        }
    });
</script>
@endpush
