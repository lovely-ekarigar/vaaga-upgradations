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
        
        
 /* //for video */
    /* VIDEO CONTROL */

.video-control-wrapper {
    width: 100%;
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
}

.video-select {
    flex: 1;
}

.video-add-btn button {
    width: 120px;
}

/* ADD VIDEO DEFAULT STATE */
.video-add-btn button {
    background: #ccc;
    border-color: #ccc;
    color: #333;
}

/* ACTIVE STATE */
.video-add-btn button.active {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}


/* TOP SECTION : SELECT + ADD VIDEO */

.video-control-wrapper {
    width: 100%;
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
}

.video-select {
    flex: 1;
}

.video-add-btn button {
    width: 110px;
    height: 40px;
}


/* YOUTUBE DYNAMIC FIELD */

#video-template,
#video-list .form-group {
    display: flex;
    gap: 10px;
    width: 100%;
}

#video-template .form-control,
#video-list .form-group .form-control {
    flex: 1;   /* instead of 85% */
    height: 40px;
    min-width: 0;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#video-template .remove-video,
#video-list .form-group .remove-video {
    flex: 0 0 90px;
    height: 40px;
}


/* MOBILE RESPONSIVE */

@media (max-width:768px){

    /*.video-control-wrapper{*/
    /*    flex-direction: column;*/
    /*}*/

    /*.video-add-btn button{*/
    /*    width: 100%;*/
    /*}*/

    #video-template,
    #video-list .form-group{
        flex-direction: row;
    }
    
.video-add-btn button {
    width: 110px;
    height: 40px;
}

    #video-template .remove-video,
    #video-list .form-group .remove-video{
        width: 90px;
    }

}


#youtube-preview-row iframe {
    width: 100%;
    height: 200px;
    border-radius: 6px;
}

.youtube-video-wrapper {
    width: 100%;
}

.youtube-controls {
    width: 100%;
}

.youtube-controls input {
    width: 100%;
}

.youtube-controls button {
    width: 100%;
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

           <!-- downloadble and pdf   -->

<div class="row">

    <div class="col-md-6 form-group">
        {!! Form::label('add_pdf', trans('labels.backend.lessons.fields.add_pdf')) !!}

        {!! Form::file('add_pdf[]', [
            'class' => 'form-control file-upload',
            'id' => 'add_pdf',
            'multiple' => true,
            'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp3,.mp4'
        ]) !!}

        <span class="form-text text-muted">
            Allowed formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, GIF, MP3, MP4
        </span>
    </div>

</div>


<div class="row mt-2" id="old-pdf-list">
    @foreach($lesson->media->where('type','lesson_pdf') as $media)
        <div class="col-md-6 mb-2 old-pdf" data-id="{{ $media->id }}">
            <div class="border p-2 d-flex justify-content-between align-items-center">
               <span>
    {{ $media->name }} ({{ number_format($media->size, 2) }} KB)
</span>

                <button type="button"
                        class="btn btn-sm btn-danger remove-old-pdf"
                        data-id="{{ $media->id }}">
                    Remove
                </button>
            </div>
        </div>
    @endforeach
</div>


<div class="row mt-2" id="pdf-preview"></div>

<input type="hidden" name="removed_old_pdfs" id="removed_old_pdfs">


       <div class="row">

    <div class="col-md-6 form-group">

        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

        <div class="video-control-wrapper">

            {!! Form::select('media_type', [
                '' => 'Select One',
                'youtube' => 'Youtube'
            ], null, [
                'class' => 'form-control video-select',
                'id' => 'media_type'
            ]) !!}

            <button type="button"
                    class="btn btn-primary video-add-btn"
                    id="add-video-btn"
                    disabled>
                Add Video
            </button>

        </div>

        <!-- Hidden template for new video input -->
        <div class="form-group d-none" id="video-template">
            <input type="text" name="video[]" value="" class="form-control mb-2 video-input" placeholder="Paste YouTube link">
            <a href="#" class="btn btn-xs btn-danger remove-video">@lang('labels.backend.lessons.remove')</a>
        </div>

        <!-- Container where new video fields will appear -->
        <div id="video-list"></div>

        {!! Form::file('video_file', [
            'class' => 'form-control mt-3 d-none',
            'id' => 'video_file'
        ]) !!}

        @lang('labels.backend.lessons.video_guide')
    </div>



    <div class="col-md-6 form-group">

        {!! Form::label('pdf_files', trans('labels.backend.lessons.fields.add_audio'), ['class' => 'control-label']) !!}

        {!! Form::file('add_audio', [
            'class' => 'form-control file-upload',
            'id' => 'add_audio',
            'accept' => 'audio/mpeg3'
        ]) !!}

        <div class="photo-block mt-3">
            <div class="files-list">
                @if($lesson->media)
                    @foreach($lesson->media as $media)
                        @if($media->type=='lesson_audio')
                            <p class="form-group">
                                <a href="{{ asset('storage/uploads/'.$media->name) }}"
                                   target="_blank">
                                    {{ $media->name }} ({{ $media->size }} KB)
                                </a>

                                <a href="#"
                                   data-media-id="{{$media->id}}"
                                   class="btn btn-xs btn-danger delete remove-file">
                                    @lang('labels.backend.lessons.remove')
                                </a>

                                <audio controls>
                                    <source src="{{ $media->url }}" type="audio/mp3" />
                                </audio>
                            </p>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

    </div>

</div>



<div class="row mt-4" id="youtube-preview-row">

    @foreach($lesson->media->where('type','youtube') as $media)
        <div class="col-md-3 mb-4 youtube-item" data-id="{{ $media->id }}">

            <div class="youtube-video-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/{{ strtok($media->file_name, '?') }}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>

            <div class="youtube-controls mt-2">
                <input type="text"
                       name="video[]"
                       value="{{ $media->url }}"
                       class="form-control mb-2">

                <button type="button"
                        class="btn btn-sm btn-danger w-100 remove-old-video"
                        data-id="{{ $media->id }}">
                    Remove
                </button>
            </div>

        </div>
    @endforeach

</div>

<input type="hidden" name="removed_old_videos" id="removed_old_videos">
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

    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
//     $(document).ready(function() {
//   $('.editor').summernote({height: 250});
// });
</script>
    <script>
        // $('.editor').each(function () {

        //     CKEDITOR.replace($(this).attr('id'), {
        //         filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        //         filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
        //         filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        //         filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',

        //         extraPlugins: 'smiley,lineutils,widget,codesnippet,prism',
        //     });

        // });
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
                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
       

        var uploadField = $('input[type="file"]');


        $(document).on('change', 'input[name="lesson_image"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        @if($lesson->mediavideo)
        @if($lesson->mediavideo->type !=  'upload')
        $('#video').removeClass('d-none').attr('required', true);
        $('#video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($lesson->mediavideo->type == 'upload')
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
        console.log("Hello");
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
$(".js-example-placeholder-singlex").select2({
                placeholder: "Select course content",
            });



            //updae to multi ?
            let selectedFiles = [];
let removedOldPdfs = [];

$(document).on('change', '#add_pdf', function (e) {
    selectedFiles = selectedFiles.concat(Array.from(e.target.files));
    renderPreview();
});
function renderPreview() {
    let preview = $('#pdf-preview');
    preview.html('');

    selectedFiles.forEach((file) => {
        preview.append(`
            <div class="col-md-6 mb-2 pdf-item">
                <div class="border p-2 d-flex justify-content-between align-items-center">
                    <span>${file.name}</span>
                    <button type="button"
                            class="btn btn-sm btn-danger remove-new"
                            data-name="${file.name}">
                        Remove
                    </button>
                </div>
            </div>
        `);
    });

    syncInput();
}
$(document).on('click', '.remove-new', function () {
    let name = $(this).data('name');
    selectedFiles = selectedFiles.filter(f => f.name !== name);
    renderPreview();
});

function syncInput() {
    let dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('add_pdf').files = dt.files;
}

// OLD PDF remove
$(document).on('click', '.remove-old-pdf', function () {
    let id = $(this).data('id');
    removedOldPdfs.push(id);
    $(this).closest('.old-pdf').remove();
});

// form submit se pehle
$('form').on('submit', function () {
    $('#removed_old_pdfs').val(removedOldPdfs.join(','));
});
// ================= VIDEO MODULE (SAME AS CREATE) =================

$(document).ready(function () {

    function toggleVideoButton() {
        $('#add-video-btn').prop(
            'disabled',
            $('#media_type').val() !== 'youtube'
        );
    }

    // Run on page load
    toggleVideoButton();

    // On change
    $('#media_type').on('change', function () {
        toggleVideoButton();
    });
// Add Video Button Click
$(document).off('click', '#add-video-btn').on('click', '#add-video-btn', function (e) {
    e.preventDefault();

    let lastInput = $('#video-list .video-input').last();

    if (lastInput.length && lastInput.val().trim() === '') {
        alert('Please enter YouTube link first');
        lastInput.focus();
        return;
    }

    let template = $('#video-template')
        .clone()
        .removeClass('d-none')
        .removeAttr('id');

    $('#video-list').append(template);
});


// Remove NEW YouTube video field
$(document).on('click', '.remove-video', function (e) {
    e.preventDefault();
    $(this).closest('.form-group').remove();
});

   // Remove OLD YouTube video (DB wale)
$(document).on('click', '.remove-old-video', function (e) {
    e.preventDefault();

    let mediaId = $(this).data('id');

    let removed = $('#removed_old_videos').val();

    if (removed) {
        removed += ',' + mediaId;
    } else {
        removed = mediaId;
    }

    $('#removed_old_videos').val(removed);

    $(this).closest('.youtube-item').remove();
});
});

// document.addEventListener('DOMContentLoaded', function () {
//     const mediaSelect = document.getElementById('media_type');
//     const addBtn = document.getElementById('add-video-btn');
//     const videoList = document.getElementById('video-list');
//     const template = document.getElementById('video-template');

//     // Show Add button only if YouTube selected
//     mediaSelect.addEventListener('change', function() {
//         if(this.value === 'youtube'){
//             addBtn.classList.remove('d-none');
//         } else {
//             addBtn.classList.add('d-none');
//         }
//     });

//     // Add new field
//     addBtn.addEventListener('click', function () {
//         const clone = template.cloneNode(true);
//         clone.classList.remove('d-none');
//         clone.removeAttribute('id');
//         videoList.appendChild(clone);
//     });

//     // Remove field
//     videoList.addEventListener('click', function(e){
//         if(e.target && e.target.classList.contains('remove-video')){
//             e.preventDefault();
//             e.target.closest('.form-group').remove();
//         }
//     });
// });

 });
    </script>
@endpush