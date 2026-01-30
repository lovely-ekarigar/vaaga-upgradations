@extends('backend.layouts.app')
@section('title', __('labels.backend.questions.title').' | '.app_name())

@push('after-styles')
<style>
    .editorjs-holder {
        min-height: 400px;
        border: 1px solid #e2e8f0; /* Light gray border as requested */
        border-radius: 0.5rem; /* similar to rounded-lg */
        background: #fff;
        padding: 1rem;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .editorjs-holder:focus-within {
        border-color: #3b82f6; /* Tailwind blue-500 */
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); /* focus ring */
    }

    /* Make Editor.js look consistent in this layout */
    .editorjs-holder .ce-block__content,
    .editorjs-holder .ce-toolbar__content {
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    
    .ce-inline-tool {
        color: inherit;
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
                    
                    <!-- Enhanced Editor Container -->
                    <div class="form-group shadow-sm border rounded-lg bg-white overflow-hidden mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Question Content
                        </div>
                        <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                    </div>
                    {!! Form::hidden('question', old('question', $question->question), ['id' => 'question_body']) !!}
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
<script type="module">
    import EditorJS from 'https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest/+esm';
    import Header from 'https://cdn.jsdelivr.net/npm/@editorjs/header@latest/+esm';
    import List from 'https://cdn.jsdelivr.net/npm/@editorjs/list@latest/+esm';
    import ImageTool from 'https://cdn.jsdelivr.net/npm/@editorjs/image@latest/+esm';
    import Table from 'https://cdn.jsdelivr.net/npm/@editorjs/table@latest/+esm';
    import InlineCode from 'https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest/+esm';

    (function () {
        const form = document.getElementById('questionForm');
        const holder = document.getElementById('editorjs');
        const hidden = document.getElementById('question_body');
        const errorEl = document.getElementById('editorjsError');

        if (!form || !holder || !hidden || !EditorJS) {
            if (holder) holder.innerHTML = '<div style="color:#b42318;font-size:13px;">Editor failed to load. Please refresh the page.</div>';
            return;
        }

        // ... existing helper functions ...
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
        // ... end helpers ...

        const initialData = parseInitialData(hidden.value);

        const editor = new EditorJS({
            holder: 'editorjs',
            autofocus: true,
            placeholder: 'Type your question here...',
            data: initialData,
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: ['link', 'inlineCode'],
                    config: {
                         placeholder: 'Header',
                         levels: [2, 3, 4],
                         defaultLevel: 2
                    }
                },
                list: {
                    class: List,
                    inlineToolbar: true
                },
                image: {
                    class: ImageTool,
                    config: {
                        uploader: {
                            async uploadByFile(file) {
                                const formData = new FormData();
                                formData.append('image', file);
                                formData.append('_token', '{{ csrf_token() }}');
                                try {
                                    const res = await fetch('{{ route("admin.editor.upload_image") }}', {
                                        method: 'POST',
                                        body: formData
                                    });
                                    const json = await res.json();
                                    if (json.success) return json;
                                    return { success: 0, error: json.error?.message || 'Upload failed' };
                                } catch (e) {
                                    // Fallback to DataURL if server upload fails
                                    return fileToDataUrl(file).then((url) => ({
                                        success: 1,
                                        file: { url }
                                    }));
                                }
                            }
                        }
                    }
                },
                table: {
                    class: Table,
                    inlineToolbar: true
                },
                inlineCode: {
                    class: InlineCode,
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
                        errorEl.textContent = 'Please enter a question before updating.';
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

