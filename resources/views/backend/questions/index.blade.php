@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.questions.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.questions.title')</h3>
            @can('question_create')
                <div class="float-right">
                    <a href="{{ route('admin.questions.create') }}"
                       class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>

                </div>
            @endcan
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('test_id', trans('labels.backend.questions.test'), ['class' => 'control-label']) !!}
                    {!! Form::select('test_id', $tests,  (request('test_id')) ? request('test_id') : old('test_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'test_id']) !!}
                </div>
            </div>
            <div class="d-block">
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{ route('admin.questions.index') }}"
                                                    style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                    </li>
                    |
                    <li class="list-inline-item"><a href="{{ route('admin.questions.index') }}?show_deleted=1"
                                                    style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                    </li>
                </ul>
            </div>
            <table id="myTable"
                   class="table table-bordered table-striped @if ( request('show_deleted') != 1 ) dt-select @endif ">
                <thead>
                <tr>
                    @can('question_delete')
                        @if ( request('show_deleted') != 1 )
                            <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all"/></th>@endif
                    @endcan
                        <th>@lang('labels.general.sr_no')</th>
                        <th>@lang('labels.backend.questions.fields.question')</th>
                    <th>@lang('labels.backend.questions.fields.question_image')</th>
                    <th>@lang('labels.backend.questions.fields.score')</th>
                    @if( request('show_deleted') == 1 )
                        <th>@lang('strings.backend.general.actions')</th>
                    @else
                        <th>@lang('strings.backend.general.actions')</th>
                    @endif
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            function stripHtml(html) {
                const div = document.createElement('div');
                div.innerHTML = html || '';
                return (div.textContent || div.innerText || '').trim();
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text || '').replace(/[&<>"']/g, (m) => map[m]);
            }

            function extractPreviewFromEditorJsJson(raw) {
                if (!raw) return '';
                const s = String(raw).trim();
                if (!s) return '';

                let parsed;
                try {
                    parsed = JSON.parse(s);
                } catch (e) {
                    parsed = null;
                }

                if (!parsed || !Array.isArray(parsed.blocks)) {
                    return stripHtml(s);
                }

                const parts = [];
                for (const b of parsed.blocks) {
                    if (!b || !b.type) continue;
                    const d = b.data || {};

                    if (b.type === 'header' || b.type === 'paragraph') {
                        const t = stripHtml(d.text);
                        if (t) parts.push(t);
                    } else if (b.type === 'list' && Array.isArray(d.items)) {
                        for (const item of d.items) {
                            const t = stripHtml(item);
                            if (t) parts.push(t);
                        }
                    } else if (b.type === 'image' && d.file && d.file.url) {
                        parts.push('[Image]');
                    }

                    if (parts.join(' ').length > 220) break;
                }

                return parts.join(' ').trim();
            }

            function truncate(text, maxLen) {
                const t = String(text || '');
                if (t.length <= maxLen) return t;
                return t.slice(0, maxLen - 1).trimEnd() + '…';
            }

            var route = '{{route('admin.questions.get_data')}}';

            @if(request('show_deleted') == 1)
                route = '{{route('admin.questions.get_data',['show_deleted' => 1])}}';
            @endif

            @if(request('test_id') != "")
                route = '{{route('admin.questions.get_data',['test_id' => request('test_id')])}}';
            @endif

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    { "data": function(data){
                        return '<input type="checkbox" class="single" name="id[]" value="'+ data.id +'" />';
                    }, "orderable": false, "searchable":false, "name":"id" },
                        @endif
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {
                        data: "question",
                        name: 'question',
                        render: function (data, type, row) {
                            // Keep sorting/filtering stable while improving display
                            if (type !== 'display') return data;
                            const preview = extractPreviewFromEditorJsJson(data);
                            return escapeHtml(truncate(preview, 220));
                        }
                    },
                    {data: "question_image", name: 'question_image'},
                    {data: "score", name: "score"},
                    {data: "actions", name: "actions"}
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });

            $(document).on('change', '#test_id', function (e) {
                var course_id = $(this).val();
                window.location.href = "{{route('admin.questions.index')}}" + "?test_id=" + course_id
            });
            @can('question_delete')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.questions.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan

        });

    </script>
@endpush