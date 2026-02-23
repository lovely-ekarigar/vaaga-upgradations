<?php
use App\Models\Course;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }
        .bootstrap-tagsinput {
            width: 100% !important;
            display: inline-block;
        }
        .bootstrap-tagsinput .tag {
            line-height: 1;
            margin-right: 2px;
            background-color: #2f353a;
            color: white;
            padding: 3px;
            border-radius: 3px;
        }
        .editorjs-holder {
            min-height: 400px;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: #fff;
            padding: 1rem;
            transition: border-color .15s ease, box-shadow .15s ease;
            position: relative;
            z-index: 10;
        }
        .editorjs-holder:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .editorjs-holder .ce-block__content,
        .editorjs-holder .ce-toolbar__content {
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .ce-inline-tool { color: inherit; }
        .codex-editor__redactor {
            padding-bottom: 50px !important;
        }
        .ce-toolbar__plus {
            z-index: 20;
        }
        .ce-toolbar__actions {
            z-index: 20;
        }
        .cke_notification_warning {
            display: none !important;
        }
        .file-upload-info {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>
@endpush

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true]) !!}
    {!! Form::hidden('model_id', 0, ['id' => 'lesson_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.lessons.create')</h3>
            <div class="float-right">
                @if(request('course_id'))
                    <a target="_blank" href="/user/content?course_id={{request('course_id')}}" class="btn btn-primary">Course Content</a>
                @endif
                <a href="{{ route('admin.lessons.index') }}" class="btn btn-success">@lang('labels.backend.lessons.view')</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6 col-lg-6 form-group">
                    {!! Form::label('course_id', trans('labels.backend.lessons.fields.course'), ['class' => 'control-label']) !!}
                    <select class="form-control js-example-placeholder-single select2" id="course_id" name="course_id">
                        @foreach($courses as $k => $c)
                        <option value="{{$k}}" @if(request('course_id') == $k) selected @endif>
                            <?php 
                            $crs = new Course();
                            echo $crs->getCouseNameWithCat($k);
                            ?>
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-6 form-group">
                    <label class="control-label" for="content_id">Course content*</label>
                    <select class="form-control js-example-placeholder-singlex select2" required="required" name="content_id" id="content_id">
                        <option></option>
                        @foreach($contents as $ct)
                        <option value="{{$ct->id}}" @if(request('content_id') == $ct->id) selected @endif>{{$ct->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.lessons.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title'), 'required' => '']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug', trans('labels.backend.lessons.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.slug_placeholder')]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('lesson_image', trans('labels.backend.lessons.fields.lesson_image'), ['class' => 'control-label']) !!}
                    {!! Form::file('lesson_image', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/webp']) !!}
                    {!! Form::hidden('lesson_image_max_size', 8) !!}
                    {!! Form::hidden('lesson_image_max_width', 4000) !!}
                    {!! Form::hidden('lesson_image_max_height', 4000) !!}
                    <div class="file-upload-info">Max file size: 2MB. Accepted: JPG, PNG, WEBP</div>
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('duration', 'Duration (HH:MM:SS)', ['class' => 'control-label']) !!}
                    {!! Form::text('duration', old('duration'), ['class' => 'form-control', 'placeholder' => '00:00:00', 'pattern' => '[0-9]{2}:[0-9]{2}:[0-9]{2}']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('short_text', trans('labels.backend.lessons.fields.short_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('short_text', old('short_text'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.short_description_placeholder'), 'rows' => 3]) !!}
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('full_text', trans('labels.backend.lessons.fields.full_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text', old('full_text'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.lessons.fields.full_text')]) !!}
                </div>
            </div>

            {{-- Downloadable Files Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('downloadable_files', trans('labels.backend.lessons.fields.downloadable_files'), ['class' => 'control-label']) !!}
                    {!! Form::file('downloadable_files[]', [
                        'multiple',
                        'class' => 'form-control file-upload',
                        'id' => 'downloadable_files',
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.mp4,.mp3,.jpg,.jpeg,.png'
                    ]) !!}
                    <div class="file-upload-info">Max 50MB per file. Accepted: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, MP4, MP3, JPG, PNG</div>
                </div>
            </div>

            {{-- PDF Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('add_pdf', trans('labels.backend.lessons.fields.add_pdf'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_pdf', [
                        'class' => 'form-control file-upload',
                        'id' => 'add_pdf',
                        'accept' => '.pdf'
                    ]) !!}
                    <div class="file-upload-info">Max 50MB. PDF files only</div>
                </div>
            </div>

            {{-- Audio Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('add_audio', trans('labels.backend.lessons.fields.add_audio'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_audio', [
                        'class' => 'form-control file-upload',
                        'id' => 'add_audio',
                        'accept' => '.mp3,.wav,.ogg,.m4a'
                    ]) !!}
                    <div class="file-upload-info">Max 50MB. Accepted: MP3, WAV, OGG, M4A</div>
                </div>
            </div>

            {{-- Video Section --}}
            <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}
                    {!! Form::select('media_type', [
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo',
                        'upload' => 'Upload Video',
                        'embed' => 'Embed Code'
                    ], null, ['class' => 'form-control', 'placeholder' => 'Select One', 'id' => 'media_type']) !!}

                    {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'), 'id' => 'video']) !!}
                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'id' => 'video_file', 'accept' => 'video/mp4,video/avi,video/mov,video/webm']) !!}
                    {!! Form::textarea('embed_code', null, ['class' => 'form-control mt-3 d-none', 'placeholder' => 'Paste embed code here', 'id' => 'embed_code', 'rows' => 4]) !!}
                    
                    <div class="file-upload-info mt-2" id="video-help-text">
                        Select a video source to see upload options
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6 col-lg-3 form-group">
                    <div class="form-check">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, ['class' => 'form-check-input', 'id' => 'published']) !!}
                        {!! Form::label('published', trans('labels.backend.lessons.fields.published'), ['class' => 'form-check-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-6 col-lg-3 form-group">
                    <div class="form-check">
                        {!! Form::hidden('free_lesson', 0) !!}
                        {!! Form::checkbox('free_lesson', 1, false, ['class' => 'form-check-input', 'id' => 'free_lesson']) !!}
                        {!! Form::label('free_lesson', 'Free Lesson', ['class' => 'form-check-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12 text-left form-group mt-3">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@stop

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('full_text', {
            height: 400,
            filebrowserUploadUrl: '{{ route("admin.uploadImageCkEditor") }}',
            filebrowserUploadMethod: 'form',
            extraAllowedContent: 'img[src,alt,width,height]'
        });
        
        document.querySelector('form').addEventListener('submit', function () {
            if (CKEDITOR.instances.full_text) CKEDITOR.instances.full_text.updateElement();
        });

        // File size validation
        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            var maxSize = 500 * 1024 * 1024; // 500MB
            
            Array.from(this.files).forEach(function(file) {
                if (file.size > maxSize) {
                    alert('"' + file.name + '" exceeds the maximum file size of 500MB');
                    $this.val('');
                }
            });
        });

        // Media type toggle
        function toggleMediaFields() {
            var mediaType = $('#media_type').val();
            
            // Hide all first
            $('#video, #video_file, #embed_code').addClass('d-none').prop('required', false);
            
            if (mediaType === 'youtube' || mediaType === 'vimeo') {
                $('#video').removeClass('d-none').prop('required', true);
                $('#video-help-text').text('Enter the full YouTube/Vimeo URL');
            } else if (mediaType === 'upload') {
                $('#video_file').removeClass('d-none').prop('required', false);
                $('#video-help-text').text('Max 500MB. Accepted: MP4, AVI, MOV, WEBM');
            } else if (mediaType === 'embed') {
                $('#embed_code').removeClass('d-none').prop('required', true);
                $('#video-help-text').text('Paste the embed iframe code');
            } else {
                $('#video-help-text').text('Select a video source to see upload options');
            }
        }

        // On change
        $('#media_type').on('change', toggleMediaFields);

        // Select2 initialization
        $(".js-example-placeholder-singlex").select2({
            placeholder: "Select course content",
        });
        
        // Course change handler
        $(document).on('change', '#course_id', function (e) {
            var course_id = $(this).val();
            window.location.href = "{{ route('admin.lessons.create') }}" + "?course_id=" + course_id;
        });
    </script>
@endpush
