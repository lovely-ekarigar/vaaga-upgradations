<?php
use App\Models\Category;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')

    {!! Form::model($course, ['method' => 'PUT', 'route' => ['admin.courses.update', $course->id], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.courses.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.courses.index') }}"
                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
            </div>
        </div>

        <div class="card-body">

            @if (Auth::user()->isAdmin())
                <div class="row">

                    <div class="col-10 form-group">
                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                        {!! Form::select('teachers[]', $teachers, old('teachers') ? old('teachers') : $course->teachers->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    </div>
                    <!-- <div class="col-2 d-flex form-group flex-column">
                        OR <a target="_blank" class="btn btn-primary mt-auto"
                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                    </div> -->
                </div>
            @endif

            <div class="row">
                <div class="col-10 form-group">
                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                   <!--  {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!} -->


                    <select class="form-control js-example-placeholder-single select2" required id="category_id" name="category_id">
                        @foreach($categories as $k=>$c)
                        <option value="{{$k}}"  @if($course->category_id==$k) selected @endif>

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
                        
                        @foreach($boards as $b)
                        <option value="{{$b['id']}}" @if($course->board_id==$b['id']) selected @endif>{{$b['name']}}</option>
                        
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
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('slug', trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug', $course->slug), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}
                </div>


                 <div class="col-12 col-lg-4 form-group">
                   <label for="type">Experience Level</label>
                   <select class="form-control" id="elevel" name="elevel">
                       <option value="Beginner" <?php if($course->elevel=='Beginner'){ echo "selected"; } ?>>Beginner</option>
                       <option value="Intermediate" <?php if($course->elevel=='Intermediate'){ echo "selected"; } ?>>Intermediate</option>
                       <option value="Advanced" <?php if($course->elevel=='Advanced'){ echo "selected"; } ?>>Advanced</option>
                   </select>

                </div>
                <input type="hidden" name="type" value="{{$course->type}}">

            </div>

      



            <div class="row">
                  {{-- <div class="col-12 form-group">
                    {!! Form::label('pre_requisite','Pre-requisites', ['class' => 'control-label']) !!}
                    {!! Form::text('pre_requisite', old('pre_requisite'), ['class' => 'form-control', 'placeholder' => 'Pre-requisites']) !!}

                </div> --}}

                {{-- <div class="col-12 form-group">
                    {!! Form::label('description',trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                     <!-- Enhanced Editor Container -->
                    <div class="form-group shadow-sm border rounded-lg bg-white mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Description
                        </div>
                        <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                    </div>
                    {!! Form::hidden('description', old('description', $course->description), ['id' => 'description_input']) !!}
                </div> --}}
            </div>
            
            <!-- Set price for 1:1 classes Section -->
            <div class="row" style="padding-left: 15px;">
                <div class="col-12">
                    <h5 class="mb-3 mt-3" style="font-weight: 600; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                        Set price for 1:1 classes
                    </h5>
                </div>
            </div>

            <div class="row" style="padding-left: 15px;">
                {{-- <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('price', 'Full Course Price 1:M (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'pattern' => "[0-9]"]) !!}
                </div> --}}
                 <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('monthly_price_1', 'Monthly Price 1:1 (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('monthly_price_1', old('monthly_price_1'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'pattern' => "[0-9]"]) !!}
                 </div>
                <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('price_1', 'Full Course Price 1:1 (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price_1', old('price_1'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'pattern' => "[0-9]"]) !!}
                </div>
{{-- 
                <div class="col-12 col-lg-3 form-group">
                    {!! Form::label('monthly_price', 'Monthly Price 1:M (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('monthly_price', old('monthly_price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'pattern' => "[0-9]"]) !!}
                </div> --}}

                
            </div>

            <!-- Group Classes Section -->
            <div class="row" style="padding-left: 15px;">
                <div class="col-12">
                    <h5 class="mb-3 mt-3" style="font-weight: 600; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                        Group Classes
                    </h5>
                </div>
            </div>

            <div class="row" style="padding-left: 15px;">

                <div class="col-12 col-lg-4 form-group">
                        <label for="monthly_price" class="control-label">Monthly Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="monthly_price" type="number" value="{{old('monthly_price',$course->monthly_price)}}" id="monthly_price">
                </div>
                <div class="col-12 col-lg-4 form-group">
                        <label for="quarterly_price" class="control-label">Quarterly Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="quarterly_price" type="number" value="{{old('quarterly_price',$course->quarterly_price)}}" id="quarterly_price">
                </div>
                <div class="col-12 col-lg-4 form-group">
                        <label for="full_price" class="control-label">Full Course Price   (in ₹)</label>
                        <input class="form-control" placeholder="Price" pattern="[0-9]" name="full_price" type="number" value="{{old('full_price',$course->full_price)}}" id="full_price">
                 </div>
            </div>
            
            <!-- Regular Classes Section -->
            <div class="row" style="padding-left: 15px;">
                <div class="col-12">
                    <h5 class="mb-3 mt-3" style="font-weight: 600; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                        Regular Classes
                    </h5>
                </div>
            </div>

            <div class="row" style="padding-left: 15px;">
                <div class="col-12 col-lg-4 form-group">
                    <label for="regular_monthly" class="control-label">Monthly Price (in ₹)</label>
                    <input class="form-control" placeholder="Price" pattern="[0-9]" name="regular_monthly" type="number" value="{{old('regular_monthly',$course->regular_monthly)}}" id="regular_monthly">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="regular_monthly_1" class="control-label">Monthly Price 1:1 (in ₹)</label>
                    <input class="form-control" placeholder="Price" pattern="[0-9]" name="regular_monthly_1" type="number" value="{{old('regular_monthly_1',$course->regular_monthly_1)}}" id="regular_monthly_1">
                </div>
            </div>

              
       <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon Monthly Course Price</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_monthly_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}" {{old('coupon_id_monthly_price',$course->coupon_id_monthly_price) == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                 <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon 'Quarterly Course Price</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_quarterly_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}"  {{old('coupon_id_quarterly_price',$course->coupon_id_quarterly_price) == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                 <div class="col-12 col-lg-4 form-group">
                   <label for="type">Default Coupon Full Price Course</label>
                   <select class="form-control select2" id="coupon_id" name="coupon_id_full_price">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}"  {{old('coupon_id_full_price',$course->coupon_id_full_price) == $c->id ? 'selected' : ''}}>{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div>
                   <div class="col-12 col-lg-3 form-group">
                   <label for="type">Default Coupon</label>
                   <select class="form-control select2" id="coupon_id_monthly_price_1" name="coupon_id_monthly_price_1">
                     <option value="">____________</option>
                    @foreach($coupons as $c)
                       <option value="{{$c->id}}" @if($course->coupon_id_monthly_price_1==$c->id) selected @endif >{{$c->name}}</option>

                       @endforeach
                      
                 
                   </select>

                </div> 

                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('duration', 'Course Duration (in Months)', ['class' => 'control-label']) !!}
                    {!! Form::number('duration', old('duration'), ['class' => 'form-control', 'placeholder' => 'in Months' ,'pattern' => "[0-9]"]) !!}
                </div>
<div class="col-12 col-lg-4 form-group">
                    {!! Form::label('duration_text',  'Course Duration Text', ['class' => 'control-label']) !!}
                    {!! Form::text('duration_text', old('duration_text'), ['class' => 'form-control', 'placeholder' => '', ]) !!}
                </div>

                <div class="col-12 col-lg-4 form-group">

                    {!! Form::label('course_image', trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::file('course_image', ['class' => 'form-control']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}
                    @if ($course->course_image)
                        <a href="{{ asset('storage/uploads/'.$course->course_image) }}" target="_blank"><img
                                    height="50px" src="{{ asset('storage/uploads/'.$course->course_image) }}"
                                    class="mt-1"></a>
                    @endif
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date'), ['class' => 'control-label']) !!}
                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date', 'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('start_date'))
                        <p class="help-block">
                            {{ $errors->first('start_date') }}
                        </p>
                    @endif
                </div>
            </div>
              <div class="row" style="padding-left: 15px;">
                <div class="col-md-12 form-group">
                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}
                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($course->mediavideo) ? $course->mediavideo->type : null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}


                    {!! Form::text('video', ($course->mediavideo) ? $course->mediavideo->url : null, ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file','accept' =>'video/mp4'  ]) !!}
                    <input type="hidden" name="old_video_file"
                           value="{{($course->mediavideo && $course->mediavideo->type == 'upload') ? $course->mediavideo->url  : ""}}">
                    @if($course->mediavideo != null)
                        <div class="form-group">
                            <a href="#" data-media-id="{{$course->mediaVideo->id}}"
                               class="btn btn-xs btn-danger my-3 delete remove-file">@lang('labels.backend.lessons.remove')</a>
                        </div>
                    @endif



                    @if($course->mediavideo && ($course->mediavideo->type == 'upload'))
                        <video width="300" class="mt-2 d-none video-player" controls>
                            <source src="{{($course->mediavideo && $course->mediavideo->type == 'upload') ? $course->mediavideo->url  : ""}}"
                                    type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>

                    @endif

                    @lang('labels.backend.lessons.video_guide')
                </div>
            </div>

           <div class="row" style="padding-left: 15px;">
                <!--<div class="col-12 form-group">-->
                <!--    <div class="checkbox d-inline mr-4">-->
                <!--        {!! Form::hidden('published', 0) !!}-->
                <!--        {!! Form::checkbox('published', 1, old('published'), []) !!}-->
                <!--        {!! Form::label('published', trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}-->
                <!--    </div>-->
                <!--     <div class="checkbox d-inline mr-4">-->
                <!--        {!! Form::hidden('featured', 0) !!}-->
                <!--        {!! Form::checkbox('featured', 1, old('featured'), []) !!}-->
                <!--        {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}-->
                <!--    </div>-->
                
                <!--shruti-->
                  <div class="col-12 form-group">
        <div class="checkbox d-inline mr-4">
            {!! Form::hidden('published', 0) !!}
            {!! Form::checkbox('published', 1, old('published', $course->published), []) !!}
            {!! Form::label('published', trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
        </div>
        <div class="checkbox d-inline mr-4">
            {!! Form::hidden('featured', 0) !!}
            {!! Form::checkbox('featured', 1, old('featured', $course->featured), []) !!}
            {!! Form::label('featured', trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
        </div>
    </div>
<!--
                    <div class="checkbox d-inline mr-4">
                        {!! Form::hidden('trending', 0) !!}
                        {!! Form::checkbox('trending', 1, old('trending'), []) !!}
                        {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

                    <div class="checkbox d-inline mr-4">
                        {!! Form::hidden('popular', 0) !!}
                        {!! Form::checkbox('popular', 1, old('popular'), []) !!}
                        {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                    <div class="checkbox d-inline mr-4">
                        {!! Form::hidden('free', 0) !!}
                        {!! Form::checkbox('free', 1, old('free'), []) !!}
                        {!! Form::label('free',  trans('labels.backend.courses.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div> -->

                </div>
  

           <div class="row" style="padding-left: 15px;">
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
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>
              </div>

    {!! Form::close() !!}
@stop

@push('after-scripts')
    <script type="module">
        import EditorJS from 'https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest/+esm';
        import Header from 'https://cdn.jsdelivr.net/npm/@editorjs/header@latest/+esm';
        import List from 'https://cdn.jsdelivr.net/npm/@editorjs/list@latest/+esm';
        import ImageTool from 'https://cdn.jsdelivr.net/npm/@editorjs/image@latest/+esm';
        import Table from 'https://cdn.jsdelivr.net/npm/@editorjs/table@latest/+esm';
        import InlineCode from 'https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest/+esm';

        (function () {
            const form = document.querySelector('form');
            const holder = document.getElementById('editorjs');
            const hidden = document.getElementById('description_input');

            if (!form || !holder || !hidden || !EditorJS) {
                return;
            }

            function stripHtml(html) {
                 const div = document.createElement('div');
                 div.innerHTML = html || '';
                 return (div.textContent || div.innerText || '').trim();
            }

            function isEditorDataEmpty(data) {
                if (!data || !Array.isArray(data.blocks) || data.blocks.length === 0) return true;
                return !data.blocks.some((block) => {
                    const type = block && block.type;
                    const d = (block && block.data) || {};
                    if (type === 'paragraph' || type === 'header') return stripHtml(d.text).length > 0;
                    if (type === 'list') return Array.isArray(d.items) && d.items.some(i => stripHtml(i).length > 0);
                    if (type === 'image') return !!(d.file && d.file.url);
                    if (type === 'table') return d.content && d.content.some(row => row.some(cell => stripHtml(cell).length > 0)); 
                    return Object.keys(d).length > 0;
                });
            }

            function wrapLegacyText(text) {
                const trimmed = (text || '').trim();
                if (!trimmed) return undefined;
                return {
                    time: Date.now(),
                    blocks: [{ type: 'paragraph', data: { text: trimmed } }]
                };
            }

            function parseInitialData(raw) {
                const trimmed = (raw || '').trim();
                if (!trimmed) return undefined;
                try {
                    const parsed = JSON.parse(trimmed);
                    if (parsed && Array.isArray(parsed.blocks)) return parsed;
                } catch (e) { /* ignore */ }
                return wrapLegacyText(trimmed);
            }

             function fileToDataUrl(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = () => reject(reader.error || new Error('File read failed'));
                    reader.readAsDataURL(file);
                });
            }

            const initialData = parseInitialData(hidden.value);

            const editor = new EditorJS({
                holder: 'editorjs',
                autofocus: false,
                placeholder: 'Write description...',
                data: initialData,
                tools: {
                    header: {
                        class: Header,
                        inlineToolbar: ['link', 'inlineCode'],
                         config: { levels: [2, 3, 4], defaultLevel: 2 }
                    },
                    list: { class: List, inlineToolbar: true },
                    image: {
                        class: ImageTool,
                        config: {
                            uploader: {
                                uploadByFile(file) {
                                    return fileToDataUrl(file).then((url) => ({ success: 1, file: { url } }));
                                }
                            }
                        }
                    },
                    table: { class: Table, inlineToolbar: true },
                    inlineCode: { class: InlineCode }
                }
            });

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                try {
                    const data = await editor.save();
                    // Description might be optional, so we don't block empty save if not required
                    // But if backend requires it, validation will fail. Editor.js empty is just blocks:[]
                    if (data && data.blocks && data.blocks.length > 0) {
                         hidden.value = JSON.stringify(data);
                    } else {
                         hidden.value = ''; // Ensure empty string for backend
                    }
                    
                    form.submit();
                } catch (err) {
                    console.error(err);
                    // Submit anyway to let backend handle validation or if save failed
                     form.submit();
                }
            });
        })();
    </script>
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
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
        });
        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 50000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        $(document).ready(function () {
            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var parent = $(this).parent('.form-group');
                var confirmation = confirm('{{trans('strings.backend.general.are_you_sure')}}')
                if (confirmation) {
                    var media_id = $(this).data('media-id');
                    $.post('{{route('admin.media.destroy')}}', {media_id: media_id, _token: '{{csrf_token()}}'},
                        function (data, status) {
                            if (data.success) {
                                parent.remove();
                                $('#video').val('').addClass('d-none').attr('required', false);
                                $('#video_file').attr('required', false);
                                $('#media_type').val('');
                                @if($course->mediavideo && $course->mediavideo->type ==  'upload')
                                $('.video-player').addClass('d-none');
                                $('.video-player').empty();
                                @endif


                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
        });


        @if($course->mediavideo)
        @if($course->mediavideo->type !=  'upload')
        $('#video').removeClass('d-none').attr('required', false);
        $('#video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($course->mediavideo->type == 'upload')
        $('#video').addClass('d-none').attr('required', false);
        $('#video_file').removeClass('d-none').attr('required', false);
        $('.video-player').removeClass('d-none');
        @else
        $('.video-player').addClass('d-none');
        $('#video_file').addClass('d-none').attr('required', false);
        $('#video').addClass('d-none').attr('required', false);
        @endif
        @endif

        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', false); // true to false
                    $('#video_file').addClass('d-none').attr('required', false);
                    $('.video-player').addClass('d-none')
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false);
                    $('#video_file').removeClass('d-none').attr('required', false);//shruti true - flase
                    $('.video-player').removeClass('d-none')
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false);
                $('#video').addClass('d-none').attr('required', false)
            }
        })


    </script>

@endpush