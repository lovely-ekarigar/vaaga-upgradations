<?php
use App\Models\Category;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.categories.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.categories.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.categories.index') }}"
                   class="btn btn-success">@lang('labels.backend.categories.view')</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.categories.store'], 'files' => true,]) !!}

                    <div class="row ">
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!}

                        </div>

<div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Parent Category</label>
                            <!-- <select class="form-control" name="parent">
                                <option value="0"></option>
                                @foreach($cats as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select> -->

                             <select class="form-control js-example-placeholder-single select2" required id="parent" name="parent">
                            <option value="0" >________________</option>
                        @foreach($cats as $c)
                        <option value="{{$c->id}}" @if(old('parent')==$c->id) selected @endif>

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
                        <option value="{{$b['id']}}" >{{$b['name']}}</option>
                        @endforeach
                    </select>
                    </div>
                      
 <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('course_image', 'Category Image', ['class' => 'control-label']) !!}
                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}

                </div>
                <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Description</label>
                            <textarea type="text" class="form-control" name="description" id="summernote" placeholder="Description"></textarea>
                        </div>
                        
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Title</label>
                            <textarea type="text" class="form-control" name="meta_title" id="" placeholder="Meta Title"></textarea>
                        </div>
                        
                         <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Description</label>
                            <textarea type="text" class="form-control" name="meta_description" id="" placeholder="Meta Description"></textarea>
                        </div>
                        
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Keyword</label>
                            <textarea type="text" class="form-control" name="meta_keyword" id="" placeholder="Meta Keyword"></textarea>
                        </div>
                        
              

                        <div class="col-12 form-group text-center">

                            {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#icon').iconpicker({
                cols: 10,
                icon: 'fas fa-bomb',
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


        })

    </script>
@endpush
