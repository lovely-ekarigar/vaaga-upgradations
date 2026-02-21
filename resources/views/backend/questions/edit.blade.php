@extends('backend.layouts.app')
@section('title', __('labels.backend.questions.title').' | '.app_name())

@push('after-styles')
<style>
    .ckeditor-holder {
        min-height: 400px;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: #fff;
    }
    .cke_editable {
        min-height: 380px;
    }
    
    /* Hide CKEditor security warning */
    .cke_notification_warning {
        display: none !important;
    }
</style>
@endpush

@section('content')

    {!! Form::model($question, ['method' => 'PUT', 'route' => ['admin.questions.update', $question->id], 'files' => true, 'id' => 'questionForm']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.questions.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.questions.index') }}"
                   class="btn btn-success">@lang('labels.backend.questions.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('question',  trans('labels.backend.questions.fields.question').'*', ['class' => 'control-label']) !!}
                    
                    <!-- CKEditor Container -->
                    <div class="form-group shadow-sm border rounded-lg bg-white overflow-hidden mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Question Content
                        </div>
                        <textarea id="question_editor" name="question" class="form-control ckeditor-holder" rows="10">{{ old('question', $question->question) }}</textarea>
                    </div>
                    <p class="help-block"></p>
                    @if($errors->has('question'))
                        <p class="help-block">
                            {{ $errors->first('question') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                @if ($question->question_image)
                    <div class="col-9">
                        {!! Form::label('question_image', trans('labels.backend.questions.fields.question_image'), ['class' => 'control-label']) !!}
                        {!! Form::file('question_image', ['class' => 'form-control', 'style' => 'margin-top: 4px;']) !!}
                        {!! Form::hidden('question_image_max_size', 8) !!}
                        {!! Form::hidden('question_image_max_width', 4000) !!}
                        {!! Form::hidden('question_image_max_height', 4000) !!}
                        <p class="help-block"></p>
                        @if($errors->has('question_image'))
                            <p class="help-block">
                                {{ $errors->first('question_image') }}
                            </p>
                        @endif
                    </div>

                    <div class="col-1 form-group">
                        <a href="{{ asset('storage/uploads/'.$question->question_image) }}" target="_blank">
                            <img height="70px" src="{{ asset('storage/uploads/'.$question->question_image) }}"></a>
                    </div>
                @else
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('question_image', trans('labels.backend.questions.fields.question_image'), ['class' => 'control-label']) !!}
                    {!! Form::file('question_image', ['class' => 'form-control', 'style' => 'margin-top: 4px;']) !!}
                    {!! Form::hidden('question_image_max_size', 8) !!}
                    {!! Form::hidden('question_image_max_width', 4000) !!}
                    {!! Form::hidden('question_image_max_height', 4000) !!}
                    <p class="help-block"></p>
                    @if($errors->has('question_image'))
                        <p class="help-block">
                            {{ $errors->first('question_image') }}
                        </p>
                    @endif
                </div>
                @endif

            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('score', trans('labels.backend.questions.fields.score').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('score', old('score'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('score'))
                        <p class="help-block">
                            {{ $errors->first('score') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('tests', trans('labels.backend.questions.fields.tests'), ['class' => 'control-label']) !!}
                    {!! Form::select('tests[]', $tests, old('tests') ? old('tests') : $question->tests->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('tests'))
                        <p class="help-block">
                            {{ $errors->first('tests') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('mock_tests', 'Assign to Mock Test(s)', ['class' => 'control-label']) !!}
                    {!! Form::select(
                        'mock_tests[]',
                        $mockTests ?? [],
                        old('mock_tests') ? old('mock_tests') : $question->mockTests->pluck('id')->toArray(),
                        ['class' => 'form-control select2', 'multiple' => 'multiple']
                    ) !!}
                    <p class="help-block"></p>
                    @if($errors->has('mock_tests'))
                        <p class="help-block">
                            {{ $errors->first('mock_tests') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @foreach ($question->options as $key=>$option)
        @php $key++ @endphp
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('option_text_' . $option->id, trans('labels.backend.questions.fields.option_text').'*', ['class' => 'control-label']) !!}
                        {!! Form::textarea('option_text_' . $key, $option->option_text, ['class' => 'form-control ', 'rows' => 3]) !!}
                        <p class="help-block"></p>
                        @if($errors->has('option_text_' . $option->id))
                            <p class="help-block">
                                {{ $errors->first('option_text_' . $option->id) }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('explanation_' . $option->id, trans('labels.backend.questions.fields.option_explanation').'*', ['class' => 'control-label']) !!}
                        {!! Form::textarea('explanation_' . $key, $option->explanation, ['class' => 'form-control ', 'rows' => 3]) !!}
                        <p class="help-block"></p>
                        @if($errors->has('explanation_' . $option->id))
                            <p class="help-block">
                                {{ $errors->first('explanation_' . $option->id) }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('correct_' . $key, trans('labels.backend.questions.fields.correct'), ['class' => 'control-label']) !!}
                        {!! Form::hidden('correct_' . $option->id, 0) !!}
                        {!! Form::hidden('option_id_'.$key,  $option->id ) !!}
                        {!! Form::checkbox('correct_' . $key, 1, ($option->correct == 1) ? true : false, []) !!}
                        <p class="help-block"></p>
                        @if($errors->has('correct_' . $question))
                            <p class="help-block">
                                {{ $errors->first('correct_' . $question) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-12 text-center mb-4">
            {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}

        </div>
    </div>


    {!! Form::close() !!}
@stop

@push('after-scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    CKEDITOR.replace('question_editor', {
        height: 380,
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        removeButtons: 'Save,NewPage,Preview,Print,Templates',
        filebrowserUploadUrl: '{{ route("admin.uploadImageCkEditor") }}',
        filebrowserUploadMethod: 'form',
        contentsCss: ['https://cdn.ckeditor.com/4.22.1/full-all/contents.css'],
        allowedContent: true,
        entities: false,
        htmlEncodeOutput: false
    });

    // Form validation
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        var questionContent = CKEDITOR.instances.question_editor.getData().trim();
        if (!questionContent) {
            e.preventDefault();
            alert('Please enter a question before updating.');
            CKEDITOR.instances.question_editor.focus();
            return false;
        }
    });
</script>
@endpush
