<?php

use App\Models\Course;
?>
@extends('backend.layouts.app')
@section('title')
Add New Notes - {{ env('APP_NAME') }}
@stop

@section('page_css')
<style>
    .tox-statusbar__branding {
        display: none;
    }

    .editorjs-holder {
        min-height: 400px;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: #fff;
        padding: 1rem;
        transition: border-color .15s ease, box-shadow .15s ease;
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

    .editorjs-holder {
        min-height: 400px;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: #fff;
        padding: 1rem;
        transition: border-color .15s ease, box-shadow .15s ease;
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
</style>
@stop
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="page-title float-left mb-0">Add New Note</h3>
        <div class="float-right">
            <a href="{{ route('admin.note.list') }}" class="btn btn-success">View Notes</a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    <label for="compname" class="form-label">Title<span class="text-danger">*</span></label>
                    <input type="text" required value="{{ old('name') }}" class="form-control" id="compname" name="name" placeholder="Title">
                </div>

                <div class="col-12 col-lg-6 form-group">
                    <label for="title" class="control-label">Category</label>

                    <select class="form-control js-example-placeholder-single select2" required id="category_id" name="category_id">
                        <option value="0">________________</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>


                </div>


                <div class="col-12 col-lg-4 form-group">
                    <label for="title" class="control-label">Course</label>

                    <select class="form-control js-example-placeholder-single select2" required id="course_id" name="course_id">
                        <option value="0">________________</option>
                        @foreach($courses as $course)
                        <option value="{{$course->id}}"><?php $crs = new Course();
                                                        echo $crs->getCouseNameWithCat($course->id); ?></option>
                        @endforeach
                    </select>


                </div>

                <div class="col-12 col-lg-4 form-group">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
                </div>
                <div class="col-12 col-lg-4 form-group">
                    <label for="file" class="form-label">Upload File</label>
                    <input type="file" value="" class="form-control" id="image" name="file" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Title</label>
                    <input type="text" value="" class="form-control" id="meta_title" name="meta_title" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Description</label>
                    <input type="text" value="" class="form-control" id="meta_description" name="meta_description" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Keyword</label>
                    <input type="text" value="" class="form-control" id="meta_keyword" name="meta_keyword" accept="">
                </div>


                <div class="col-12 col-lg-12 form-group">
                    <label for="editor" class="form-label">Description<span class="text-danger">*</span></label>
                    <div class="form-group shadow-sm border rounded-lg bg-white overflow-hidden mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Note Description
                        </div>
                        <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                    </div>
                    <input type="hidden" name="description" id="description_input" value="{{ old('description') }}">
                </div>
            </div>
            <div class="row pt-3">

                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light ">
                        Submit
                    </button>
                </div>

            </div>
        </form>
    </div>


    @endsection

    @section('page_js')

    <script src="https://cdn.tiny.cloud/1/vlr81mg0cx8hu4bcrhk8jjmunq6xq5ycvurgvcbth7scst88/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
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
                placeholder: 'Write your note description...',
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
                    if (isEditorDataEmpty(data)) {
                        alert('Content cannot be empty');
                        return;
                    }
                    hidden.value = JSON.stringify(data);
                    form.submit();
                } catch (err) {
                    console.error(err);
                }
            });
        })();
    </script>

    @stop