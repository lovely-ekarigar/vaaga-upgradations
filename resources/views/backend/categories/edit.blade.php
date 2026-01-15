<?php
use App\Models\Category;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.categories.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')
    {!! Form::model($category, ['method' => 'PUT', 'route' => ['admin.categories.update', $category->id], 'files' => true,]) !!}

    <div class="alert alert-danger d-none" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="error-list">
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.categories.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.categories.index') }}?parent={{$category->parent}}"
                   class="btn btn-success">@lang('labels.backend.categories.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row ">
            <div class="col-12 col-lg-4 form-group">
                {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'required' => false]) !!}

            </div>

<div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Parent Category</label>
                         <!--    <select class="form-control" name="parent">
                                <option value="0"></option>
                                @foreach($cats as $c)
                                <option value="{{$c->id}}" <?php if($category->parent==$c->id){ echo 'selected'; } ?> >{{$c->name}}</option>
                                @endforeach
                            </select> -->

                               <select class="form-control js-example-placeholder-single select2" required id="parent" name="parent">
                                 <option value="0" >________________</option>
                        @foreach($cats as $c)
                        <option value="{{$c->id}}" @if($category->parent==$c->id) selected @endif>

                             <?php  

                     $crs = new Category();
                     echo $crs->findParentCat($c->id);
                     ?>

                        </option>

                        @endforeach

                    </select>

                        </div>
                        
                        <div class="col-12 col-lg-4 form-group">
                         <label for="boards_id" class="control-label">Boards</label>
                    <select name="boards_id" class="form-control select2 js-example-placeholder-single" required>
 <option value="0" >________________</option>
                        @foreach($boards as $b)
                        <option value="{{$b['id']}}"  @if($category->board_id==$b['id']) selected @endif>{{$b['name']}}</option>
                        
                        @endforeach
                    </select>
                    </div>
        
 <div class="col-12 col-lg-4 form-group">

                    {!! Form::label('course_image', 'Category Image', ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::file('course_image', ['class' => 'form-control']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}
                    @if ($category->course_image)
                        <a href="{{ asset('storage/uploads/'.$category->course_image) }}" target="_blank"><img
                                    height="50px" src="{{ asset('storage/uploads/'.$category->course_image) }}"
                                    class="mt-1"></a>
                    @endif
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="ec" class="control-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="1" <?php if($category->status==1){ echo 'selected'; } ?>>Active</option>
                        <option value="0" <?php if($category->status==0){ echo 'selected'; } ?>>Inactive</option>
                    </select>
                </div>
                 <div class="col-12 form-group">
                           <!--  {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!} -->
                            <!--<label for="dec" class="control-label">Description</label>-->
                            <!--<input type="text" class="form-control" value="{{ $category->description}}" name="description">-->
                            
                             <label for="dec" class="control-label">Description</label>
                            <textarea type="text" class="form-control" name="description" id="summernote" placeholder="Description">{{ $category->description}}</textarea>
                            
                        </div> 
                         <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Title</label>
                            <textarea type="text" class="form-control" name="meta_title" id="" placeholder="Meta Title">{{ $category->meta_title}}</textarea>
                        </div>
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Description</label>
                            <textarea type="text" class="form-control" name="meta_description" id="" placeholder="Meta Description">{{ $category->meta_description}}</textarea>
                        </div>
                        
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Keyword</label>
                            <textarea type="text" class="form-control" name="meta_keyword" id="" placeholder="Meta Keyword">{{ $category->meta_keyword}}</textarea>
                        </div>
            <div class="col-12 form-group text-center">

                {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
            </div>
        </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>

    <script>
        var icon = 'fas fa-bomb';
        @if($category->icon != "")
                icon = "{{$category->icon}}";
        @endif
        $('#icon').iconpicker({
            cols: 10,
            icon: icon,
            iconset: 'fontawesome5',
            labelHeader: '{0} of {1} pages',
            labelFooter: '{0} - {1} of {2} icons',
            placement: 'bottom', // Only in button tag
            rows: 5,
            search: true,
            searchText: 'Search',
            selectedClass: 'btn-success',
            unselectedClass: ''
        });

        $(document).on('change', '#icon_type', function () {

            if ($(this).val() == 1) {
                $('.upload-image-wrapper').parent('.col-12').removeClass('d-none')

                $('.upload-image-wrapper').removeClass('d-none');
                $('.select-icon-wrapper').addClass('d-none')
            } else if ($(this).val() == 2) {
                $('.upload-image-wrapper').parent('.col-12').removeClass('d-none')

                $('.upload-image-wrapper').addClass('d-none');
                $('.select-icon-wrapper').removeClass('d-none')
            } else {
                $('.upload-image-wrapper').parent('.col-12').addClass('d-none')
                $('.upload-image-wrapper').addClass('d-none');
                $('.select-icon-wrapper').addClass('d-none');


            }
        })

    </script>
@endpush


