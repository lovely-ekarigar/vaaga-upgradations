<?php
use App\Models\Category;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">@lang('labels.backend.courses.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.courses.index') }}"
                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
            </div>
        </div>

        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('tutors','Tutors', ['class' => 'control-label']) !!}
                        {!! Form::select('teachers[]', $teachers, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                    </div>
                   <!--  <div class="col-2 d-flex form-group flex-column">
                        OR <a target="_blank" class="btn btn-primary mt-auto"
                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                    </div> -->
                </div>
            @endif

            <div class="row">
                <div class="col-10 form-group">
                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                 <!--    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!} -->


                    <select class="form-control js-example-placeholder-single select2" required id="category_id" name="category_id">
                        <option value="" disabled selected>Select Category....</option>
                        @foreach($categories as $k=>$c)
                        <option value="{{$k}}" @if(old('category_id')==$k) selected @endif>

                             <?php  

                     $crs = new Category();
                     echo $crs->findParentCat($k);
                     ?>

                        </option>

                        @endforeach

                    </select>



                   
                </div>
                <div class="col-2 d-flex form-group flex-column">
                    OR <a target="_blank" class="btn btn-primary mt-auto"
                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                </div>
            </div>

            <div class="row">
                <div class="col-10 form-group">
                    <label for="boards_id" class="control-label">Boards</label>
                    <select name="boards_id" class="form-control select2 js-example-placeholder-single" required>
                        <option value="" disabled selected>Select Boards....</option>
                        @foreach($boards as $b)
                        <option value="{{$b['id']}}">{{$b['name']}}</option>
                        @endforeach
                    </select>
                 
                </div>
                <div class="col-2 d-flex form-group flex-column">
                    OR <a target="_blank" class="btn btn-primary mt-auto"
                          href="{{route('admin.boards.create')}}">Add Boards</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title'), 'required' => false]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('slug',  trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-4 form-group">
                   <label for="type">Experience Level</label>
                   <select class="form-control" id="elevel" name="elevel">
                       <option value="Beginner">Beginner</option>
                       <option value="Intermediate">Intermediate</option>
                       <option value="Advanced">Advanced</option>
                   </select>

                </div>
                <input type="hidden" name="type" value="course">
            </div>



            <div class="row">
  <div class="col-12 form-group">
                    {!! Form::label('pre_requisite','Pre-requisites', ['class' => 'control-label']) !!}
                    {!! Form::text('pre_requisite', old('pre_requisite'), ['class' => 'form-control', 'placeholder' => 'Pre-requisites']) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['id'=>'summernote','class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}

                </div>
            </div>
            <div class="row">
               {{-- <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('price',  'Full Course Price 1:M (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'), 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('price_1',  'Full Course Price 1:1 (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price_1', old('price_1'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'), 'pattern' => "[0-9]"]) !!}
                </div>

                 <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('monthly_price',  'Monthly Price 1:M  (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('monthly_price', old('monthly_price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'), 'pattern' => "[0-9]"]) !!}
                </div>

                 <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('monthly_price_1',  'Monthly Price 1:1 (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('monthly_price_1', old('monthly_price_1'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'), 'pattern' => "[0-9]"]) !!}
                </div> --}}
                <div class="col-12 col-lg-4 form-group">
                        <label for="monthly_price" class="control-label">Monthly Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="monthly_price" type="number" value="{{old('monthly_price')}}" id="monthly_price">
                </div>
                <div class="col-12 col-lg-4 form-group">
                        <label for="quarterly_price" class="control-label">Quarterly Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="quarterly_price" type="number" value="{{old('quarterly_price')}}" id="quarterly_price">
                </div>
                <div class="col-12 col-lg-4 form-group">
                        <label for="full_price" class="control-label">Full Course Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="full_price" type="number" value="{{old('full_price')}}" id="full_price">
                </div>

              
       <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon Monthly Course Price</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_monthly_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}" {{old('coupon_id_monthly_price') == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                 <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon 'Quarterly Course Price</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_quarterly_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}"  {{old('coupon_id_quarterly_price') == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                 <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon Full Price Course</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_full_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}"  {{old('coupon_id_full_price') == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                 <!-- <div class="col-12 col-lg-3 form-group">
                   <label for="type">Default Coupon Monthly Price 1:1</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_monthly_price_1">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}">{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div> -->




                 <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('duration',  'Course Duration (in Months)', ['class' => 'control-label']) !!}
                    {!! Form::number('duration', old('duration'), ['class' => 'form-control', 'placeholder' => 'in Months', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('duration_text',  'Course Duration Text', ['class' => 'control-label']) !!}
                    {!! Form::text('duration_text', old('duration_text'), ['class' => 'form-control', 'placeholder' => '', ]) !!}
                </div>



                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('course_image',  trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label']) !!}
                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}

                </div>
                <div class="col-12 col-lg-4  form-group">
                    {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date'), ['class' => 'control-label']) !!}
                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date','pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)', 'autocomplete' => 'off']) !!}

                </div>
            </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                        {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                        {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}


                        {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file'  ]) !!}

                        @lang('labels.backend.lessons.video_guide')

                    </div>
                </div>

                <div class="row">
                <div class="col-12 form-group">
                    <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, []) !!}
                        {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>


                   <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('featured', 0) !!}
                        {!! Form::checkbox('featured', 1, false, []) !!}
                        {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
 <!-- 
                    <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('trending', 0) !!}
                        {!! Form::checkbox('trending', 1, false, []) !!}
                        {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

                    <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('popular', 0) !!}
                        {!! Form::checkbox('popular', 1, false, []) !!}
                        {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

                    <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('free', 0) !!}
                        {!! Form::checkbox('free', 1, false, []) !!}
                        {!! Form::label('free',  trans('labels.backend.courses.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div> -->


                </div>

            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title'), ['class' => 'control-label']) !!}
                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title')]) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_description',trans('labels.backend.courses.fields.meta_description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description')]) !!}
                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords')]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12  text-center form-group">

                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{'Select Tutors'}}",
            });
        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        })


        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false)
                $('#video').addClass('d-none').attr('required', false)
            }
        })


    </script>

@endpush