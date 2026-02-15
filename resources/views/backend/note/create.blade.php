<?php

use App\Models\Course;
?>
@extends('backend.layouts.app')
@section('title')
Add New Notes - {{ env('APP_NAME') }}
@stop

@section('page_css')
<style>
    .tox-statusbar__branding {
        display: none;
    }

    /* Hide CKEditor notifications */
    .cke_notifications_area { display: none !important; }
</style>
@stop
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="page-title float-left mb-0">Add New Notes</h3>
        <div class="float-right">
            <a href="{{ route('admin.note.list') }}" class="btn btn-success">View Notes</a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="compname" class="form-label">Title<span class="text-danger">*</span></label>
                    <input type="text" required value="{{ old('name') }}" class="form-control" id="compname" name="name" placeholder="Title">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="title" class="control-label">Category</label>

                    <select class="form-control js-example-placeholder-single select2" required id="category_id" name="category_id">
                        <option value="0">________________</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>


                </div>


                <div class="col-12 col-lg-4 form-group">
                    <label for="title" class="control-label">Course</label>

                    <select class="form-control js-example-placeholder-single select2" required id="course_id" name="course_id">
                        <option value="0">________________</option>
                        @foreach($courses as $course)
                        <option value="{{$course->id}}"><?php $crs = new Course();
                                                        echo $crs->getCouseNameWithCat($course->id); ?></option>
                        @endforeach
                    </select>


                </div>

                <div class="col-12 col-lg-4 form-group">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="file" class="form-label">Upload File</label>
                    <input type="file" value="" class="form-control" id="image" name="file" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Title</label>
                    <input type="text" value="" class="form-control" id="meta_title" name="meta_title" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Description</label>
                    <input type="text" value="" class="form-control" id="meta_description" name="meta_description" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Keyword</label>
                    <input type="text" value="" class="form-control" id="meta_keyword" name="meta_keyword" accept="">
                </div>


                <div class="col-12 col-lg-12 form-group">
                    <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                    <textarea id="description" name="description" class="form-control" rows="10">{{ old('description') }}</textarea>
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

    @section('page_js')
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('description', {
            height: 400,
            filebrowserUploadUrl: '{{ route("admin.uploadImageCkEditor") }}',
            filebrowserUploadMethod: 'form',
            extraAllowedContent: 'img[src,alt,width,height]',
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' }
            ]
        });
        
        // Update form submission
        document.querySelector('form').addEventListener('submit', function () {
            if (CKEDITOR.instances.description) CKEDITOR.instances.description.updateElement();
        });
    </script>
    @endsection
