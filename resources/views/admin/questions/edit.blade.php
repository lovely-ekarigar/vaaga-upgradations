<?php

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Str;

?>
@extends('backend.layouts.app')

@section('title', "Edit Question | " . env('APP_NAME'))

@section('page_css')
<style>
    /* Modern Compact Design */
    svg{
        height: 30px;
    }

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
<div class="">
    <div class="max-w-6xl mx-auto px-4">
        @include('admin.includes.message')

        <div class="card bg-white mb-6">
            <div class="card-header">
                <h4 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Edit Question
                </h4>
            </div>

            <div class="card-body">
                  <form method="POST" action="" id="questionForm">
            @csrf
            @method('PUT')

            @php
                $questionText = json_decode($question->question_text, true);
                $solutionText = json_decode($question->solution, true);
                $savedOptions = json_decode($question->options, true) ?? [];
                $savedAnswers = is_array($question->correct_answer) 
                                ? $question->correct_answer 
                                : explode(',', $question->correct_answer);
            @endphp

            <div class="row">
                
                 <div class="mb-3 col-md-4 ">
                         <label class="form-label small text-muted fw-bold">Select Chapter</label>
                         <select class="form-control form-select selectChapter" name="chapter_id" data-id="{{$question->id}}" style="width:100%">
                                            @php $chapters = Lesson::where("course_id",$question->course_id)->get(); @endphp
                                            <option value="">Select Chapter</option>
                                            @foreach($chapters as $c)
                                                <option value="{{$c->id}}" @if($c->id==$question->chapter_id) selected @endif>
                                                    {{ Str::limit($c->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                    </div>
                {{-- Left Column --}}
                <div class="col-lg-12">
                   
                    {{-- Question Text --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-question-mark me-2"></i>Question Text</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small text-muted fw-bold">English</label>
                                    <div id="editorjs_question_en" class="border rounded-lg p-4 bg-white editorjs-holder"></div>
                                    <textarea id="question_text_en" name="question_text[en]" class="form-control question-input d-none" rows="3">{{ old('question_text.en', $questionText['en'] ?? '') }}</textarea>
                                    <div id="editorjsQuestionError" class="invalid-feedback d-block" style="display:none"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    

                    {{-- Options --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2"></i>Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="options-container">
                                @foreach($savedOptions as $key => $option)
                                    <div class="col-md-6 option-item">
                                        <div class="border rounded-3 p-3 option-card" data-option-id="{{ $key }}">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light text-dark">Option {{ $key }}</span>
                                                <div class="form-check form-switch">
                                                    <input type="radio" name="correct_answer[]" value="{{ $key }}"
                                                        class="form-check-input correct-answer-switch"
                                                        {{ in_array($key, old('correct_answer', $savedAnswers)) ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Correct</label>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small text-muted">English</label>
                                                <div id="editorjs_option_{{ $key }}_en" class="border rounded-lg p-4 bg-white editorjs-holder editorjs-sm"></div>
                                                <textarea id="option_{{ $key }}_en" name="options[{{ $key }}][en]" class="form-control option-input d-none" rows="2">{{ old("options.$key.en", $option['en'] ?? '') }}</textarea>
                                            </div>
                                           
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-12">
                    {{-- Solution --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-check-circle me-2"></i>Solution</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">English</label>
                                <div id="editorjs_solution_en" class="border rounded-lg p-4 bg-white editorjs-holder"></div>
                                <textarea id="solution_en" name="solution[en]" class="form-control solution-input d-none" rows="3">{{ old('solution.en', $solutionText['en'] ?? '') }}</textarea>
                            </div>
                            
                        </div>
                    </div>

                    {{-- Marks & Settings --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-cog me-2"></i>Question Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Marks</label>
                                <input type="number" name="marks" min="1" class="form-control marks-input" 
                                    value="{{ old('marks', $question->marks) }}">
                            </div>
                             <div class="">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                        <select name="difficulty" id="selectDifficulty" class="form-control form-select" required>
                              <option value="">-- Select Difficulty --</option>
                            <option value="easy" @if($question->difficulty=='easy') selected @endif>Easy</option>
                            <option value="medium" @if($question->difficulty=='medium') selected @endif>Medium</option>
                            <option value="hard" @if($question->difficulty=='hard') selected @endif>Hard</option>
                        </select>
                    </div>
                    
                     <div class="">
                            <label class="form-label fw-semibold">Is Previuos Year Question?</label>
                        <select name="is_prev_year" id="is_prev_year" class="form-control form-select" required>
                         
                            <option value="0" @if($question->is_prev_year=='0') selected @endif >No</option>
                            <option value="1"  @if($question->is_prev_year=='1') selected @endif>Yes</option>
                        </select>
                    </div>
                    
                    
                           <div class="">
                            <label class="form-label fw-semibold">Verification</label>
                        <select name="verification_status" id="verification_status" class="form-control form-select" >
                              <option value="">-- Select Verification --</option>
                            <option value="approved" @if($question->verification_status=='approved') selected @endif>Approved</option>
                            <option value="rejected" @if($question->verification_status=='rejected') selected @endif>Rejected</option>
                        </select>
                    </div>
                           
                        </div>
                    </div>

                    
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-2 sticky-bottom py-3 bg-light mt-4 rounded-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bx bx-save me-1"></i> Update Question
                </button>
               
                <a href="{{ url()->previous() }}" class="btn btn-light px-4 ms-auto">
    Cancel
</a>

            </div>

        </form>
            </div>
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

        const editors = [];
        const qEditor = makeEditor('editorjs_question_en', 'question_text_en');
        if (qEditor) editors.push({ editor: qEditor, textareaId: 'question_text_en', required: true });

        const sEditor = makeEditor('editorjs_solution_en', 'solution_en');
        if (sEditor) editors.push({ editor: sEditor, textareaId: 'solution_en', required: false });

        // Options (supports any keys present)
        document.querySelectorAll('[id^="editorjs_option_"][id$="_en"]').forEach((holder) => {
            const id = holder.id; // editorjs_option_{key}_en
            const key = id.replace('editorjs_option_', '').replace('_en', '');
            const textareaId = `option_${key}_en`;
            const ed = makeEditor(id, textareaId);
            if (ed) editors.push({ editor: ed, textareaId, required: false });
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
                for (const item of editors) {
                    const data = await item.editor.save();
                    if (item.required && isEmptyEditorData(data)) {
                        if (errorEl) {
                            errorEl.textContent = 'Please enter a question before updating.';
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
@endpush
