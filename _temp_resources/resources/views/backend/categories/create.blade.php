<?php
use App\Models\Category;
?>
@extends('backend.layouts.app')
@section('title', __('labels.backend.categories.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
    <style>
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
     /* Create extra space for the toolbar to be visible if needed */
    .codex-editor__redactor {
        padding-bottom: 50px !important;
    }
    
    /* Ensure the toolbar button is visible */
    .ce-toolbar__plus {
        z-index: 20;
    }
    .ce-toolbar__actions {
        z-index: 20;
    }
    </style>
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.categories.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.categories.index') }}"
                   class="btn btn-success">@lang('labels.backend.categories.view')</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.categories.store'], 'files' => true,]) !!}

                    <div class="row ">
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!}

                        </div>

<div class="col-12 col-lg-4 form-group">
                            <label for="title" class="control-label">Parent Category</label>
                            <!-- <select class="form-control" name="parent">
                                <option value="0"></option>
                                @foreach($cats as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select> -->

                             <select class="form-control js-example-placeholder-single select2" required id="parent" name="parent">
                            <option value="0" >________________</option>
                        @foreach($cats as $c)
                        <option value="{{$c->id}}" @if(old('parent')==$c->id) selected @endif>

                             <?php  

                     $crs = new Category();
                     echo $crs->findParentCat($c->id);
                     ?>

                        </option>

                        @endforeach

                    </select>


                        </div>
                        
                          <div class="col-12 col-lg-4 form-group">
                         <label for="boards_id" class="control-label">Boards</label>
                    <select name="boards_id" class="form-control select2 js-example-placeholder-single" required>
                        <option value="0" >________________</option>
                        @foreach($boards as $b)
                        <option value="{{$b['id']}}" >{{$b['name']}}</option>
                        @endforeach
                    </select>
                    </div>
                      
 <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('course_image', 'Category Image', ['class' => 'control-label']) !!}
                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}

                </div>
                <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Description</label>
                            <!-- Enhanced Editor Container -->
                            <div class="form-group shadow-sm border rounded-lg bg-white mb-3">
                                <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                                    Description
                                </div>
                                <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                            </div>
                            <input type="hidden" name="description" id="description_input">
                        </div>
                        
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Title</label>
                            <textarea type="text" class="form-control" name="meta_title" id="" placeholder="Meta Title"></textarea>
                        </div>
                        
                         <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Description</label>
                            <textarea type="text" class="form-control" name="meta_description" id="" placeholder="Meta Description"></textarea>
                        </div>
                        
                        <div class="col-12 form-group">
                            
                            <label for="dec" class="control-label">Meta Keyword</label>
                            <textarea type="text" class="form-control" name="meta_keyword" id="" placeholder="Meta Keyword"></textarea>
                        </div>
                        
              

                        <div class="col-12 form-group text-center">

                            {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#icon').iconpicker({
                cols: 10,
                icon: 'fas fa-bomb',
                iconset: 'fontawesome5',
                labelHeader: '{0} of {1} pages',
                labelFooter: '{0} - {1} of {2} icons',
                placement: 'bottom', // Only in button tag
                rows: 5,
                search: true,
                searchText: 'Search',
                selectedClass: 'btn-success',
                unselectedClass: ''
            });


        })


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
                placeholder: 'Write description...',
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
                     // Description might be optional, so we don't block empty save if not required
                    // But if backend requires it, validation will fail. Editor.js empty is just blocks:[]
                    if (data && data.blocks && data.blocks.length > 0) {
                         hidden.value = JSON.stringify(data);
                    } else {
                         hidden.value = ''; // Ensure empty string for backend
                    }
                    form.submit();
                } catch (err) {
                    console.error(err);
                     form.submit();
                }
            });
        })();
    </script>
    </script>
@endpush
