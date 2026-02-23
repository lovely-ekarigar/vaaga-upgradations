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

        /* Media Preview Styles */
        .media-preview-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
            margin-bottom: 1rem;
        }
        .media-preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .media-preview-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .media-preview-icon {
            font-size: 2rem;
        }
        .media-preview-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .media-preview-meta {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .video-preview-container {
            background: #000;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .pdf-preview-container {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .audio-preview-container {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
        }
    </style>
@endpush

@section('content')
    {!! Form::model($lesson, ['method' => 'PUT', 'route' => ['admin.lessons.update', $lesson->id], 'files' => true]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.lessons.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.lessons.index') }}" class="btn btn-success">@lang('labels.backend.lessons.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-lg-6 form-group">
                    {!! Form::hidden('course_id', $lesson->course_id) !!}
                    {!! Form::label('course_id', trans('labels.backend.lessons.fields.course'), ['class' => 'control-label']) !!}
                    <select class="form-control js-example-placeholder-single select2" id="course_id" name="course_id" disabled='disabled'>
                        @foreach($courses as $k => $c)
                        <option value="{{$k}}" @if($lesson->course_id == $k) selected @endif>
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
                        <option value="{{$ct->id}}" @if($lesson->content_id == $ct->id) selected @endif>{{$ct->title}}</option>
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
                @if ($lesson->lesson_image)
                    <div class="col-12 col-lg-5 form-group">
                        {!! Form::label('lesson_image', trans('labels.backend.lessons.fields.lesson_image').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                        {!! Form::file('lesson_image', ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png,image/webp', 'style' => 'margin-top: 4px;']) !!}
                        {!! Form::hidden('lesson_image_max_size', 8) !!}
                        {!! Form::hidden('lesson_image_max_width', 4000) !!}
                        {!! Form::hidden('lesson_image_max_height', 4000) !!}
                    </div>
                    <div class="col-lg-1 col-12 form-group">
                        <a href="{{ asset('storage/uploads/'.$lesson->lesson_image) }}" target="_blank">
                            <img src="{{ asset('storage/uploads/'.$lesson->lesson_image) }}" height="65px" width="65px" class="rounded">
                        </a>
                    </div>
                @else
                    <div class="col-12 col-lg-6 form-group">
                        {!! Form::label('lesson_image', trans('labels.backend.lessons.fields.lesson_image').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                        {!! Form::file('lesson_image', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/webp']) !!}
                        {!! Form::hidden('lesson_image_max_size', 8) !!}
                        {!! Form::hidden('lesson_image_max_width', 4000) !!}
                        {!! Form::hidden('lesson_image_max_height', 4000) !!}
                    </div>
                @endif
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
                    {!! Form::textarea('full_text', old('full_text', $lesson->full_text), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.lessons.fields.full_text')]) !!}
                </div>
            </div>

            {{-- Downloadable Files Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('downloadable_files', trans('labels.backend.lessons.fields.downloadable_files').' (Max 50MB each)', ['class' => 'control-label']) !!}
                    {!! Form::file('downloadable_files[]', [
                        'multiple',
                        'class' => 'form-control file-upload',
                        'id' => 'downloadable_files',
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.mp4,.mp3,.jpg,.jpeg,.png'
                    ]) !!}
                    
                    {{-- Existing Downloadable Files --}}
                    @if($lesson->downloadableMedia && $lesson->downloadableMedia->count() > 0)
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">Existing Files:</h6>
                            @foreach($lesson->downloadableMedia as $media)
                                <div class="media-preview-card">
                                    <div class="media-preview-header">
                                        <div class="media-preview-info">
                                            <i class="bi {{ $media->icon_class }} media-preview-icon"></i>
                                            <div>
                                                <div class="media-preview-title">{{ $media->name }}</div>
                                                <div class="media-preview-meta">
                                                    {{ $media->formatted_size }} • {{ strtoupper($media->file_type) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ $media->file_url }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Preview
                                            </a>
                                            <a href="#" data-media-id="{{$media->id}}" class="btn btn-sm btn-danger remove-file">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- PDF Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('add_pdf', trans('labels.backend.lessons.fields.add_pdf').' (Max 50MB)', ['class' => 'control-label']) !!}
                    {!! Form::file('add_pdf', [
                        'class' => 'form-control file-upload',
                        'id' => 'add_pdf',
                        'accept' => '.pdf'
                    ]) !!}
                    
                    @if($lesson->mediaPDF)
                        <div class="media-preview-card mt-3">
                            <div class="media-preview-header">
                                <div class="media-preview-info">
                                    <i class="bi bi-file-pdf text-danger media-preview-icon" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <div class="media-preview-title">{{ $lesson->mediaPDF->name }}</div>
                                        <div class="media-preview-meta">{{ $lesson->mediaPDF->formatted_size }}</div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $lesson->mediaPDF->file_url }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View PDF
                                    </a>
                                    <a href="#" data-media-id="{{$lesson->mediaPDF->id}}" class="btn btn-sm btn-danger remove-file">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="pdf-preview-container mt-2">
                                <iframe src="{{ $lesson->mediaPDF->file_url }}" width="100%" height="400px"></iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audio Section --}}
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('add_audio', trans('labels.backend.lessons.fields.add_audio').' (MP3, WAV, OGG - Max 50MB)', ['class' => 'control-label']) !!}
                    {!! Form::file('add_audio', [
                        'class' => 'form-control file-upload',
                        'id' => 'add_audio',
                        'accept' => '.mp3,.wav,.ogg,.m4a'
                    ]) !!}
                    
                    @if($lesson->mediaAudio)
                        <div class="media-preview-card mt-3">
                            <div class="media-preview-header">
                                <div class="media-preview-info">
                                    <i class="bi bi-music-note-beamed text-success media-preview-icon" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <div class="media-preview-title">{{ $lesson->mediaAudio->name }}</div>
                                        <div class="media-preview-meta">{{ $lesson->mediaAudio->formatted_size }}</div>
                                    </div>
                                </div>
                                <a href="#" data-media-id="{{$lesson->mediaAudio->id}}" class="btn btn-sm btn-danger remove-file">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                            <div class="audio-preview-container mt-2">
                                <audio controls class="w-100">
                                    <source src="{{ $lesson->mediaAudio->file_url }}" type="{{ $lesson->mediaAudio->mime_type ?? 'audio/mpeg' }}">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    @endif
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
                    ], optional($lesson->mediaVideo)->type, ['class' => 'form-control', 'placeholder' => 'Select One', 'id' => 'media_type']) !!}

                    {!! Form::text('video', optional($lesson->mediaVideo)->url, ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'), 'id' => 'video']) !!}

                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'id' => 'video_file', 'accept' => 'video/mp4,video/avi,video/mov,video/webm']) !!}
                    
                    {!! Form::textarea('embed_code', optional($lesson->mediaVideo)->url, ['class' => 'form-control mt-3 d-none', 'placeholder' => 'Paste embed code here', 'id' => 'embed_code', 'rows' => 4]) !!}
                    
                    <input type="hidden" name="old_video_file" value="{{ optional($lesson->mediaVideo)->type == 'upload' ? $lesson->mediaVideo->url : '' }}">

                    {{-- Existing Videos --}}
                    @if($lesson->mediaVideo)
                        <div class="media-preview-card mt-3">
                            <div class="media-preview-header">
                                <div class="media-preview-info">
                                    <i class="bi bi-play-circle-fill text-primary media-preview-icon" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <div class="media-preview-title">
                                            {{ $lesson->mediaVideo->name }} 
                                            <span class="badge bg-info">{{ ucfirst($lesson->mediaVideo->type) }}</span>
                                        </div>
                                        @if($lesson->mediaVideo->type == 'upload')
                                            <div class="media-preview-meta">{{ $lesson->mediaVideo->formatted_size }}</div>
                                        @endif
                                    </div>
                                </div>
                                <a href="#" data-media-id="{{$lesson->mediaVideo->id}}" class="btn btn-sm btn-danger remove-file">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                            
                            {{-- Video Preview --}}
                            @if($lesson->mediaVideo->is_video)
                                <div class="video-preview-container mt-2 ratio ratio-16x9">
                                    @if($lesson->mediaVideo->is_external && $lesson->mediaVideo->embed_url)
                                        <iframe src="{{ $lesson->mediaVideo->embed_url }}" 
                                                title="{{ $lesson->mediaVideo->name }}"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                        </iframe>
                                    @elseif($lesson->mediaVideo->type == 'upload')
                                        <video controls preload="metadata">
                                            <source src="{{ $lesson->mediaVideo->file_url }}" type="{{ $lesson->mediaVideo->mime_type ?? 'video/mp4' }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6 col-lg-3 form-group">
                    <div class="form-check">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, old('published', $lesson->published), ['class' => 'form-check-input', 'id' => 'published']) !!}
                        {!! Form::label('published', trans('labels.backend.lessons.fields.published'), ['class' => 'form-check-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-6 col-lg-3 form-group">
                    <div class="form-check">
                        {!! Form::hidden('free_lesson', 0) !!}
                        {!! Form::checkbox('free_lesson', 1, old('free_lesson', $lesson->free_lesson), ['class' => 'form-check-input', 'id' => 'free_lesson']) !!}
                        {!! Form::label('free_lesson', 'Free Lesson', ['class' => 'form-check-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12 text-left form-group mt-3">
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-primary']) !!}
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

        // Remove media file
        $(document).on('click', '.remove-file', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $parent = $this.closest('.media-preview-card');
            var confirmation = confirm('{{trans('strings.backend.general.are_you_sure')}}');
            
            if (confirmation) {
                var media_id = $this.data('media-id');
                $.post('{{route('admin.media.destroy')}}', {
                    media_id: media_id, 
                    _token: '{{csrf_token()}}'
                }, function (data, status) {
                    if (data.success) {
                        $parent.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                });
            }
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
            } else if (mediaType === 'upload') {
                $('#video_file').removeClass('d-none').prop('required', false);
            } else if (mediaType === 'embed') {
                $('#embed_code').removeClass('d-none').prop('required', true);
            }
        }

        // Initial state
        toggleMediaFields();

        // On change
        $('#media_type').on('change', toggleMediaFields);

        // Select2 initialization
        $(".js-example-placeholder-singlex").select2({
            placeholder: "Select course content",
        });
    </script>
@endpush
