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
  /* //for video */
        .video-control-wrapper {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .video-select {
            flex: 0 0 85%;   /* Select thoda chhota */
        }

        .video-add-btn {
            flex: 0 0 14%;   /* Button thoda bada */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Same height */
        .video-select,
        .video-add-btn {
            height: 40px;
        }


        #video-template,
    #video-list .form-group {
    display: flex;
    align-items: stretch;
    gap: 10px;
}

#video-template .form-control,
#video-list .form-group .form-control {
    flex: 0 0 85%;
    height: 40px;
}

#video-template .remove-video,
#video-list .form-group .remove-video {
    flex: 0 0 14%;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
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
 <div class="row">

    <!--{{-- <div class="col-md-6 form-group">-->
    <!--    {!! Form::label('downloadable_files', trans('labels.backend.lessons.fields.downloadable_files').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}-->
    <!--    {!! Form::file('downloadable_files[]', [-->
    <!--        'multiple'=>  true,-->
    <!--        'class' => 'form-control file-upload',-->
    <!--        'id' => 'downloadable_files',-->
    <!--        'accept' => "image/jpeg,image/gif,image/png,application/msword,application/pdf,video/mp4"-->
    <!--    ]) !!}-->
    <!--</div> --}}-->

    <div class="col-md-6 form-group">
        {!! Form::label('add_pdf', trans('labels.backend.lessons.fields.add_pdf')) !!}
        {!! Form::file('add_pdf[]', [
            'class' => 'form-control file-upload',
            'id' => 'add_pdf',
            'multiple'=>true,
            'accept' => ".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
        ]) !!}
        
        <span class="form-text text-muted">
        Allowed formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, GIF, MP3, MP4
    </span>

    </div>

    </div>
        <ul id="fileList"></ul>
            <div id="pdf-preview" class="mt-2"></div>

          <div class="row">
    

    <!-- Video Field -->
    <div class="col-md-6 form-group">
        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

        <div class="d-flex align-items-stretch gap-2 video-control-wrapper">

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


        {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'id'=>'video_file']) !!}

        @lang('labels.backend.lessons.video_guide')
    </div>


    <!-- Audio Field -->
    <div class="col-md-6 form-group">
        {!! Form::label('audio_files', trans('labels.backend.lessons.fields.add_audio'), ['class' => 'control-label']) !!}
        {!! Form::file('add_audio', [
            'class' => 'form-control file-upload',
            'id' => 'add_audio',
            'accept' => "audio/mpeg3"
        ]) !!}
    </div>

            <!-- <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                    {!! Form::select('media_type', ['youtube' => 'Youtube'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                    old comment // {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!} --> 
<!-- 
                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file'  ]) !!}

                    @lang('labels.backend.lessons.video_guide')

                </div>
            </div> -->


     

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
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
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
         $(".js-example-placeholder-singlex").select2({
                placeholder: "Select course content",
            });
            $(document).on('change', '#course_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{ route('admin.lessons.create') }}" + "?course_id=" + course_id
            });

/// added  for multi file upload

let selectedFiles = [];

// $(document).on('change', '#add_pdf', function (e) {
//     let preview = $('#pdf-preview');
//     preview.html('');

//     selectedFiles = Array.from(e.target.files);

//     renderPreview();
// });




$(document).on('change', '#add_pdf', function (e) {
    selectedFiles = selectedFiles.concat(Array.from(e.target.files));
    renderPreview();
 
});
function renderPreview() {
    let preview = $('#pdf-preview');
    preview.html('');

    selectedFiles.forEach((file) => {
        let fileURL = URL.createObjectURL(file);
        let ext = file.name.split('.').pop().toLowerCase();

        let div;

        if (ext === 'pdf') {
            // PDF preview with iframe
            div = $(`
                <div class="mb-3 border p-2" data-name="${file.name}">
                    <p>
                        <strong>${file.name}</strong>
                        <button type="button" 
                            class="btn btn-sm btn-danger float-right remove-btn"
                            data-name="${file.name}">
                            Remove
                        </button>
                    </p>
                    <iframe src="${fileURL}" width="100%" height="500px"></iframe>
                </div>
            `);
        } else {
            // DOC/DOCX preview as download link
            div = $(`
                <div class="mb-3 border p-2" data-name="${file.name}">
                    <p>
                        <strong>${file.name}</strong>
                        <button type="button" 
                            class="btn btn-sm btn-danger float-right remove-btn"
                            data-name="${file.name}">
                            Remove
                        </button>
                    </p>
                    <a href="${fileURL}" target="_blank">Download</a>
                </div>
            `);
        }

        preview.append(div);
    });

    syncInputFiles();
}


$(document).on('click', '.remove-btn', function () {
    let name = $(this).data('name');

    selectedFiles = selectedFiles.filter(f => f.name !== name);

    renderPreview();
});

function syncInputFiles() {
    let dt = new DataTransfer();

    selectedFiles.forEach(file => dt.items.add(file));

    document.getElementById('add_pdf').files = dt.files;
}

$('#media_type').on('change', function () {
    if ($(this).val() === 'youtube') {
        $('#add-video-btn').prop('disabled', false);
    } else {
        $('#add-video-btn').prop('disabled', true);
    }
});
$(document).ready(function () {

    $('#media_type').on('change', function () {
        $('#add-video-btn').prop('disabled', $(this).val() !== 'youtube');
    });

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

    $(document).on('click', '.remove-video', function () {
        $(this).closest('.form-group').remove();
    });

});

//   $(document).on('change', '#add_pdf', function(e) {
//     let preview = $('#pdf-preview');
//     preview.html('');

//     Array.from(e.target.files).forEach((file, index) => {
//         let fileURL = URL.createObjectURL(file);

//         let div = $(`
//             <div class="mb-3 border p-2">
//                 <p>
//                     <strong>${file.name}</strong>
//                     <button type="button" class="btn btn-sm btn-danger float-right" onclick="removePdf(${index})">Remove</button>
//                 </p>
//                 <iframe src="${fileURL}" width="100%" height="350px"></iframe>
//             </div>
//         `);

//         preview.append(div);
//     });
// });

// function removePdf(index) {
//     let input = document.getElementById('add_pdf');
//     let dt = new DataTransfer();
//     let files = input.files;

//     for (let i = 0; i < files.length; i++) {
//         if (i !== index) dt.items.add(files[i]);
//     }

//     input.files = dt.files;
//     input.dispatchEvent(new Event('change'));
// }

    </script>

@endpush