<?php
use App\Models\Course;
?>
@extends('backend.layouts.app')
@section('title')
Edit Notes - {{ env('APP_NAME') }}
@stop
@section('page_css')
<style>
    .tox-statusbar__branding{
        display: none;
    }
</style>
@stop
@section('content')

<div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Edit Note</h3>
            <div class="float-right">
                <a href="{{ route('admin.note.list') }}"
                   class="btn btn-success">View Notes</a>
            </div>
        </div>
        
         <div class="card-body">
             <form method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                     <label for="compname" class="form-label">Title<span class="text-danger">*</span></label>
                     <input type="text" required value="{{$note->name }}" class="form-control" id="compname" name="name">
                </div>

                  <div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Category</label>
                           
                             <select class="form-control js-example-placeholder-single select2" required id="category_id" name="category_id">
                            <option value="0" >________________</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($note->category_id==$category->id) selected @endif >{{$category->name}}</option>
                            @endforeach
                    </select>


                        </div>
                        
                         <div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Course</label>
                           
                             <select class="form-control js-example-placeholder-single select2" required id="course_id" name="course_id">
                            <option value="0" >________________</option>
                            @foreach($courses as $course)
                            <option value="{{$course->id}}" @if($note->course_id==$course->id) selected @endif ><?php  $crs = new Course();
                     echo $crs->getCouseNameWithCat($course->id);?></option>
                            @endforeach
                    </select>


                        </div>
  
                <div class="col-12 col-lg-4 form-group">
                  <label for="image" class="form-label">Image</label>
                     <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
            </div>
            <div class="col-12 col-lg-2 form-group pt-4">
                 
                     <img src="/{{$note->image}}" style="height:50px">
            </div>
            
                <div class="col-12 col-lg-6 form-group">
                  <label for="file" class="form-label">Upload File</label>
                     <input type="file" value="" class="form-control" id="image" name="file" accept="">
            </div>
            
            <div class="col-12 col-lg-12 form-group">
                     <label for="compname" class="form-label">Slug<span class="text-danger">*</span></label>
                     <input type="text" required value="{{$note->slug }}" class="form-control" id="compname" name="slug">
                </div>
                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Title</label>
                    <input type="text" value="{{$note->meta_title }}" class="form-control" name="meta_title" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Description</label>
                    <input type="text" value="{{$note->meta_description }}" class="form-control"  name="meta_description" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Keyword</label>
                    <input type="text" value="{{$note->meta_keyword }}" class="form-control"  name="meta_keyword" accept="">
                </div>
            
            <div class="col-12 col-lg-12 form-group">
                  <label for="editor" class="form-label">Description</label>
                     <textarea  class="form-control" id="editor" name="description" rows="4" >{!! $note->description !!}</textarea>
            </div>
        </div>
         <div class="row pt-3">

                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light ">
                       Update
                    </button>
                </div>

            </div>
            </form>
</div>


@endsection
@section('page_js')

 <script src="https://cdn.tiny.cloud/1/vlr81mg0cx8hu4bcrhk8jjmunq6xq5ycvurgvcbth7scst88/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>


    tinymce.init({
      selector: '#editor',
      menubar: false,
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount fullscreen',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat | fullscreen',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      images_upload_url:'/user/upload-image?_token={{csrf_token()}}'

 

    });
  </script>

@stop