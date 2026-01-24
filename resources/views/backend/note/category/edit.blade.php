@extends('backend.layouts.app')
@section('title')
Edit Category - {{ env('APP_NAME') }}
@stop
@section('content')

<div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Edit Category</h3>
            <div class="float-right">
                <a href="{{ route('admin.note.category.list') }}"
                   class="btn btn-success">View Categories</a>
            </div>
        </div>
        
         <div class="card-body">
             <form method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                     <label for="compname" class="form-label">Name<span class="text-danger">*</span></label>
                     <input type="text" required value="{{ $category->name }}" class="form-control" id="compname" name="name">
                </div>

                  <!--<div class="col-12 col-lg-4 form-group">-->
                  <!--          <label for="title" class="control-label">Parent Category</label>-->
                           
                  <!--           <select class="form-control js-example-placeholder-single select2" required id="parent" name="parent_id">-->
                  <!--          <option value="0" >________________</option>-->
                  <!--          @foreach($categories as $cat)-->
                  <!--          <option value="{{$cat->id}}" @if($category->parent_id==$cat->id) selected @endif >{{$cat->name}}</option>-->
                  <!--          @endforeach-->
                  <!--  </select>-->


                  <!--      </div>-->
                        
                         

  
                <div class="col-12 col-lg-4 form-group">
                  <label for="image" class="form-label">Image</label>
                     <input type="file" value="" class="form-control" id="image" name="image" accept="image/jpeg,image/gif,image/png">
            </div>
            <div class="col-12 col-lg-2 form-group pt-4">
                 
                     <img src="/{{$category->image}}" style="height:50px">
            </div>
            <div class="col-12 col-lg-4 form-group">
                            <label for="status" class="control-label">Status</label>
                           
                             <select class="form-control js-example-placeholder-single select" required id="status" name="status">
                            <option value="" >________________</option>
                            <option value="1" <?php if ($category['status'] == '1') { echo 'selected';} ?>>Active</option>
                            <option value="0" <?php if ($category['status'] == '0') { echo 'selected';} ?>>Pending</option>
                       
                    </select>
                    </div>
            
             <div class="col-12 col-lg-8 form-group">
                     <label for="slug" class="form-label">Slug<span class="text-danger">*</span></label>
                     <input type="text" required value="{{ $category->slug }}" class="form-control" id="slug" name="slug">
                </div>
           
                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Title</label>
                    <input type="text" value="{{$category->meta_title }}" class="form-control" name="meta_title" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Description</label>
                    <input type="text" value="{{$category->meta_description }}" class="form-control"  name="meta_description" accept="">
                </div>

                <div class="col-12 col-lg-12 form-group">
                    <label for="file" class="form-label">Meta Keyword</label>
                    <input type="text" value="{{$category->meta_keyword }}" class="form-control"  name="meta_keyword" accept="">
                </div>

            
            <div class="col-12 col-lg-12 form-group">
                  <label for="summernote" class="form-label">Description</label>
                     <!-- Enhanced Editor Container -->
                    <div class="form-group shadow-sm border rounded-lg bg-white mb-3">
                        <div class="bg-gray-50 px-4 py-2 border-b text-sm font-semibold text-gray-600" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 0.5rem 1rem;">
                            Description
                        </div>
                        <div id="editorjs" class="p-4 prose max-w-none editorjs-holder" style="border:none; box-shadow:none;"></div>
                    </div>
                    <textarea name="description" id="description_input" class="d-none">{!! $category->description !!}</textarea>
            </div>
        </div>
         <div class="row pt-3">

                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light ">
                       Update
                    </button>
                </div>

            </div>
            </form>
</div>


@endsection

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
@endpush