@extends('backend.layouts.app')

@section('title', "Add Question | " . env('APP_NAME'))

@section('page_css')
<style>
    svg { height: 30px; }

    .editorjs-holder {
        min-height: 180px;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        background: #fff;
        padding: 14px 16px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .editorjs-holder.editorjs-sm {
        min-height: 140px;
        padding: 12px 14px;
    }

    .editorjs-holder:focus-within {
        border-color: rgba(13, 110, 253, .55);
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
    }

    .editorjs-holder .ce-block__content,
    .editorjs-holder .ce-toolbar__content {
        max-width: 100%;
    }
</style>
@stop

@section('content')
<div class="max-w-6xl mx-auto px-4">
    @include('admin.includes.message')

    <div class="card bg-white mb-6">
        <div class="card-header">
            <h4 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Add Question
            </h4>
        </div>

        <div class="card-body">
            <form method="POST" action="" id="questionForm">
                @csrf

                <div class="row">
                    {{-- Select Course --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select form-control" required>
                            <option value="">-- Choose Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Chapter --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Select Chapter</label>
                        <select name="chapter_id" id="chapter_id" class="form-select  form-control" required>
                            <option value="">-- Choose Chapter --</option>
                            {{-- Filled dynamically via AJAX when course is selected --}}
                        </select>
                    </div>

                    {{-- Question Text --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Question Text</label>
                        <div id="editorjs_question_en" class="border rounded-lg p-4 bg-white editorjs-holder"></div>
                        <textarea id="question_text_en" name="question_text[en]" class="form-control d-none" rows="3">{{ old('question_text.en') }}</textarea>
                        <div id="editorjsQuestionError" class="invalid-feedback d-block" style="display:none"></div>
                    </div>

                    {{-- Options --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Options</label>
                        <div class="row g-3">
                            @for($i=1; $i<=4; $i++)
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-light text-dark">Option {{ $i }}</span>
                                            <div class="form-check">
                                                <input type="radio" name="correct_answer[]" value="{{ $i }}" class="form-check-input"
                                                    {{ (old('correct_answer') == $i) ? 'checked' : '' }}>
                                                <label class="form-check-label small">Correct</label>
                                            </div>
                                        </div>
                                        <div id="editorjs_option_{{ $i }}_en" class="border rounded-lg p-4 bg-white editorjs-holder editorjs-sm"></div>
                                        <textarea id="option_{{ $i }}_en" name="options[{{ $i }}][en]" class="form-control d-none" rows="2">{{ old("options.$i.en") }}</textarea>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Solution --}}
                    <div class="col-lg-12 mb-4">
                        <label class="form-label fw-semibold">Solution</label>
                        <div id="editorjs_solution_en" class="border rounded-lg p-4 bg-white editorjs-holder"></div>
                        <textarea id="solution_en" name="solution[en]" class="form-control d-none" rows="3">{{ old('solution.en') }}</textarea>
                    </div>

                    {{-- Marks --}}
                    <div class="col-lg-4 mb-4">
                        <label class="form-label fw-semibold">Marks</label>
                        <input type="number" name="marks" min="1" class="form-control" value="{{ old('marks', 1) }}">
                    </div>
                       <div class="col-md-4">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select" required>
                            <option value="">-- Select Difficulty --</option>
                            <option value="easy" selected >Easy</option>
                            <option value="medium" >Medium</option>
                            <option value="hard" >Hard</option>
                        </select>
                    </div>
                    
                     <div class="col-md-4">
                            <label class="form-label fw-semibold">Is Previuos Year Question?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                         
                            <option value="0" selected >No</option>
                            <option value="1" >Yes</option>
                        </select>
                    </div>
                    
                    
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> Save Question
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('after-scripts')
<script>
    // Prevent UMD builds from thinking CommonJS is available
    window.module = undefined;
    window.exports = undefined;
</script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.31.1/dist/editorjs.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.8.8/dist/header.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@2.0.9/dist/editorjs-list.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.10.3/dist/image.umd.js"></script>

<script>
    (function () {
        const form = document.getElementById('questionForm');
        const errorEl = document.getElementById('editorjsQuestionError');

        if (!form || !window.EditorJS) return;

        const ListTool = window.EditorjsList; // list tool UMD global
        const ImageTool = window.ImageTool;
        const HeaderTool = window.Header;

        function isEmptyEditorData(data) {
            if (!data || !Array.isArray(data.blocks) || data.blocks.length === 0) return true;
            return !data.blocks.some((b) => {
                const t = b && b.type;
                const d = (b && b.data) || {};
                if (t === 'paragraph' || t === 'header') return !!(d.text && String(d.text).replace(/<[^>]*>/g, '').trim());
                if (t === 'list') return Array.isArray(d.items) && d.items.some(i => String(i || '').replace(/<[^>]*>/g, '').trim());
                if (t === 'image') return !!(d.file && d.file.url);
                return Object.keys(d).length > 0;
            });
        }

        function htmlToInitialData(html) {
            const trimmed = (html || '').trim();
            if (!trimmed) return undefined;
            return {
                time: Date.now(),
                blocks: [
                    { type: 'paragraph', data: { text: trimmed } }
                ]
            };
        }

        function editorDataToHtml(data) {
            if (!data || !Array.isArray(data.blocks)) return '';
            return data.blocks.map((b) => {
                const t = b.type;
                const d = b.data || {};
                if (t === 'header') {
                    const level = Number(d.level) || 2;
                    return `<h${level}>${d.text || ''}</h${level}>`;
                }
                if (t === 'paragraph') return `<p>${d.text || ''}</p>`;
                if (t === 'list') {
                    const tag = d.style === 'ordered' ? 'ol' : 'ul';
                    const items = Array.isArray(d.items) ? d.items : [];
                    return `<${tag}>${items.map(i => `<li>${i || ''}</li>`).join('')}</${tag}>`;
                }
                if (t === 'image') {
                    const url = d.file && d.file.url ? d.file.url : '';
                    const caption = d.caption ? `<figcaption>${d.caption}</figcaption>` : '';
                    if (!url) return '';
                    return `<figure><img src="${url}" alt=""/>${caption}</figure>`;
                }
                return '';
            }).join('');
        }

        function makeEditor(holderId, textareaId) {
            const textarea = document.getElementById(textareaId);
            if (!textarea) return null;

            return new EditorJS({
                holder: holderId,
                autofocus: false,
                data: htmlToInitialData(textarea.value),
                tools: {
                    header: { class: HeaderTool, inlineToolbar: ['link'] },
                    list: { class: ListTool, inlineToolbar: true },
                    image: {
                        class: ImageTool,
                        config: {
                            uploader: {
                                uploadByFile(file) {
                                    return new Promise((resolve, reject) => {
                                        const reader = new FileReader();
                                        reader.onload = () => resolve({ success: 1, file: { url: reader.result } });
                                        reader.onerror = () => reject(reader.error || new Error('File read failed'));
                                        reader.readAsDataURL(file);
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }

        const editors = [
            { editor: makeEditor('editorjs_question_en', 'question_text_en'), textareaId: 'question_text_en', required: true },
            { editor: makeEditor('editorjs_solution_en', 'solution_en'), textareaId: 'solution_en', required: false },
            { editor: makeEditor('editorjs_option_1_en', 'option_1_en'), textareaId: 'option_1_en', required: false },
            { editor: makeEditor('editorjs_option_2_en', 'option_2_en'), textareaId: 'option_2_en', required: false },
            { editor: makeEditor('editorjs_option_3_en', 'option_3_en'), textareaId: 'option_3_en', required: false },
            { editor: makeEditor('editorjs_option_4_en', 'option_4_en'), textareaId: 'option_4_en', required: false },
        ].filter(x => x.editor);

        let isSubmitting = false;

        form.addEventListener('submit', async function (e) {
            if (isSubmitting) return;
            e.preventDefault();

            if (errorEl) {
                errorEl.style.display = 'none';
                errorEl.textContent = '';
            }

            try {
                for (const item of editors) {
                    const data = await item.editor.save();
                    if (item.required && isEmptyEditorData(data)) {
                        if (errorEl) {
                            errorEl.textContent = 'Please enter a question before saving.';
                            errorEl.style.display = 'block';
                        }
                        document.getElementById('editorjs_question_en')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    const html = editorDataToHtml(data);
                    const textarea = document.getElementById(item.textareaId);
                    if (textarea) textarea.value = html;
                }

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

<script>


// load chapters dynamically
document.getElementById('course_id').addEventListener('change', function() {
    let courseId = this.value;
    let chapterSelect = document.getElementById('chapter_id');
    chapterSelect.innerHTML = '<option value="">Loading...</option>';

    fetch(`/user/chapters/by-subject/${courseId}`)
        .then(response => response.json())
        .then(data => {
            chapterSelect.innerHTML = '<option value="">-- Choose Chapter --</option>';
            data.forEach(chapter => {
                chapterSelect.innerHTML += `<option value="${chapter.id}">${chapter.title}</option>`;
            });
        });
});
</script>
@endpush
