@extends('backend.layouts.app')

@section('title', __('Create Notification').' | '.app_name())

@section('content')

<style type="text/css">
    .batch_list_div{
        display: none;
    }
</style>
@push('after-styles')
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

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Create Notification</h3>
            <div class="float-right mb-2">
   <a href="{{route('admin.notifications')}}" class="btn btn-sm btn-primary">All Notifications</a>
</div>

        </div>
        <div class="card-body">
            <form method="post">
                @csrf
            <div class="row">
                <div class="col-12 mb-3">
                        <label for="title">Notification Title*</label>
                    <input type="text" name="title" required id="title" class="form-control">

                </div>
                <div class="col-6 mb-3">
                        <label for="batch_type">Batch Type*</label>
                   <select id="batch_type" name="batch_type" required class="form-control form-select select2">
                     <option value="">_________</option>
                    <option value="active">Active Batch</option>
                    <option value="completed">Completed Batch</option>
                    <option value="all">All Batch</option>
                    <option value="selected">Selected Batch</option>
                       
                   </select>

                </div>

                 <div class="col-6 mb-3">
                        <label for="user_type">User Type*</label>
                   <select id="user_type" required name="user_type" class="form-control form-select select2">
                    <option value="">_________</option>
                    <option value="student">Only Student</option>
                    <option value="tutor">Only Tutor</option>
                    <option value="both">Student & Tutor both</option>
                       
                   </select>

                </div>


                <div class="col-12 mb-3 batch_list_div">
                        <label for="batch_list">Select Batch*</label>
                    
                <select id="batch_list"  name="batch_list[]" class="form-control form-select select2" multiple>
                    @foreach($batches as $batch)
                    <option value="{{$batch->id}}">{{$batch->name}}</option>

                    @endforeach
                       
                   </select>
                </div>

                <div class="col-12 mb-3">
                        <label for="title">Notification Message*</label>
                     <!-- Enhanced Editor Container -->
                    <div class="form-group shadow-sm border rounded-lg bg-white mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Message
                        </div>
                        <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                    </div>
                    <input type="hidden" name="message" id="message_input">

                </div>

                 <div class="col-12 mb-3 text-center">
                    <input type="submit" name="submit" value="Create Now" class="btn btn-primary">
                 </div>

            </div>

            </form>
        </div>
    </div>

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
            const form = document.querySelector('form');
            const holder = document.getElementById('editorjs');
            const hidden = document.getElementById('message_input');

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
                placeholder: 'Write message...',
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
    <script>

        $(document).ready(function () {
             $(document).on('change','#batch_type',function(){

                var bt = $(this).val();
                if(bt=='selected'){
                    $('.batch_list_div').show();
                }else{
                     $('.batch_list_div').hide();
                }
             })

        });

    </script>
@endpush
