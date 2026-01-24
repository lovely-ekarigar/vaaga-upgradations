@extends('backend.layouts.app')
@section('title', __('labels.backend.questions.title').' | '.app_name())

@push('after-styles')
<style>
    .editorjs-holder {
        min-height: 300px;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        background: #fff;
        padding: 14px 16px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .editorjs-holder:focus-within {
        border-color: rgba(13, 110, 253, .55);
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
    }

    /* Make Editor.js look consistent in this layout */
    .editorjs-holder .ce-block__content,
    .editorjs-holder .ce-toolbar__content {
        max-width: 100%;
    }
</style>
@endpush

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.store'], 'files' => true, 'id' => 'questionForm']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.questions.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.questions.index') }}"
                   class="btn btn-success">@lang('labels.backend.questions.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('question', trans('labels.backend.questions.fields.question').'*', ['class' => 'control-label']) !!}
                    <div id="editorjs" class="border rounded-lg p-4 bg-white editorjs-holder"></div>
                    {!! Form::hidden('question', old('question'), ['id' => 'question_body']) !!}
                    <div id="editorjsError" class="invalid-feedback d-block" style="display:none"></div>
                    <p class="help-block"></p>
                    @if($errors->has('question'))
                        <p class="help-block">
                            {{ $errors->first('question') }}
                        </p>
                    @endif
                </div>
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
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('score', trans('labels.backend.questions.fields.score').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('score', old('score', 1), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::select('tests[]', $tests, old('tests'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('tests'))
                        <p class="help-block">
                            {{ $errors->first('tests') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @for ($question=1; $question<=4; $question++)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('option_text_' . $question, trans('labels.backend.questions.fields.option_text').'*', ['class' => 'control-label']) !!}
                    {!! Form::textarea('option_text_' . $question, old('option_text'), ['class' => 'form-control ', 'rows' => 3]) !!}
                    <p class="help-block"></p>
                    @if($errors->has('option_text_' . $question))
                        <p class="help-block">
                            {{ $errors->first('option_text_' . $question) }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('explanation_' . $question, trans('labels.backend.questions.fields.option_explanation'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('explanation_' . $question, old('explanation_'.$question), ['class' => 'form-control ', 'rows' => 3]) !!}
                    <p class="help-block"></p>
                    @if($errors->has('explanation_' . $question))
                        <p class="help-block">
                            {{ $errors->first('explanation_' . $question) }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('correct_' . $question, trans('labels.backend.questions.fields.correct'), ['class' => 'control-label']) !!}
                    {!! Form::hidden('correct_' . $question, 0) !!}
                    {!! Form::checkbox('correct_' . $question, 1, false, []) !!}
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
    @endfor
    <div class="col-12 text-center">
        {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-danger mb-4 form-group']) !!}
    </div>

    {!! Form::close() !!}
@stop

@push('after-scripts')
<script type="module">
    import EditorJS from 'https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest/+esm';
    import Header from 'https://cdn.jsdelivr.net/npm/@editorjs/header@latest/+esm';
    import List from 'https://cdn.jsdelivr.net/npm/@editorjs/list@latest/+esm';
    import ImageTool from 'https://cdn.jsdelivr.net/npm/@editorjs/image@latest/+esm';

    (function () {
        const form = document.getElementById('questionForm');
        const holder = document.getElementById('editorjs');
        const hidden = document.getElementById('question_body');
        const errorEl = document.getElementById('editorjsError');

        if (!form || !holder || !hidden || !EditorJS) {
            // Visible hint in case scripts are blocked
            if (holder) holder.innerHTML = '<div style="color:#b42318;font-size:13px;">Editor failed to load. Please refresh the page.</div>';
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

                if (type === 'paragraph' || type === 'header') {
                    return stripHtml(d.text).length > 0;
                }

                if (type === 'list') {
                    return Array.isArray(d.items) && d.items.some(i => stripHtml(i).length > 0);
                }

                if (type === 'image') {
                    return !!(d.file && d.file.url);
                }

                // Fallback: any non-empty data counts as content
                return Object.keys(d).length > 0;
            });
        }

        function wrapLegacyText(text) {
            const trimmed = (text || '').trim();
            if (!trimmed) return undefined;

            return {
                time: Date.now(),
                blocks: [
                    {
                        type: 'paragraph',
                        data: { text: trimmed }
                    }
                ]
            };
        }

        function parseInitialData(raw) {
            const trimmed = (raw || '').trim();
            if (!trimmed) return undefined;

            try {
                const parsed = JSON.parse(trimmed);
                if (parsed && Array.isArray(parsed.blocks)) return parsed;
            } catch (e) {
                // ignore
            }

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
            autofocus: true,
            placeholder: 'Type your question here...',
            data: initialData,
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: ['link']
                },
                list: {
                    class: List,
                    inlineToolbar: true
                },
                image: {
                    class: ImageTool,
                    config: {
                        uploader: {
                            uploadByFile(file) {
                                return fileToDataUrl(file).then((url) => ({
                                    success: 1,
                                    file: { url }
                                }));
                            }
                        }
                    }
                }
            }
        });

        let isSubmitting = false;

        form.addEventListener('submit', async function (e) {
            if (isSubmitting) return;
            e.preventDefault();

            if (errorEl) {
                errorEl.style.display = 'none';
                errorEl.textContent = '';
            }

            try {
                const data = await editor.save();

                if (isEditorDataEmpty(data)) {
                    if (errorEl) {
                        errorEl.textContent = 'Please enter a question before saving.';
                        errorEl.style.display = 'block';
                    }
                    holder.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                hidden.value = JSON.stringify(data);
                isSubmitting = true;
                form.submit();
            } catch (err) {
                console.error(err);
                if (errorEl) {
                    errorEl.textContent = 'Could not save the editor content. Please try again.';
                    errorEl.style.display = 'block';
                }
            }
        });
    })();
</script>
@endpush

