@extends('backend.layouts.app')
@section('title')
Add New Category - {{ env('APP_NAME') }}
@stop
@section('content')

<div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Add New Category</h3>
            <div class="float-right">
                <a href="{{ route('admin.note.category.list') }}"
                   class="btn btn-success">View Categories</a>
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

                  <!--<div class="col-12 col-lg-3 form-group">-->
                  <!--          <label for="title" class="control-label">Parent Category</label>-->
                           
                  <!--           <select class="form-control js-example-placeholder-single select2" required id="parent" name="parent_id">-->
                  <!--          <option value="0" >________________</option>-->
                  <!--          @foreach($categories as $category)-->
                  <!--          <option value="{{$category->id}}" >{{$category->name}}</option>-->
                  <!--          @endforeach-->
                  <!--  </select>-->


                  <!--      </div>-->
  
                <div class="col-12 col-lg-6 form-group">
                  <label for="image" class="form-label">Image</label>
                     <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
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
                  <label for="summernote" class="form-label">Description</label>
                     <textarea  required value="{{ old('description') }}" class="form-control" id="summernote" name="description" rows="4" placeholder="Description"></textarea>
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