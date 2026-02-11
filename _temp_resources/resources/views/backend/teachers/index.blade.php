@extends('backend.layouts.app')
@section('title', __('labels.backend.teachers.title').' | '.app_name())
@push('after-styles')
    <link rel="stylesheet" href="{{asset('assets/css/colors/switch.css')}}">
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
                <h3 class="page-title d-inline">Tutor</h3>
           <!--  @can('course_create')
                <div class="float-right">
                    <a href="{{ route('admin.teachers.create') }}"
                       class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>

                </div>
            @endcan -->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <div class="d-block">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.teachers.index') }}"
                                       style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                                </li>
                                |
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.teachers.index') }}?show_deleted=1"
                                       style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="float-right mb-2">
    <select class="form-control form-select" id="teacherType" name="type" style="width: 130px;display: inline-block;margin-left: 20px;">

    <option value="all" @if(request('type') == 'all') selected @endif>All</option>
    <option value="active" @if(request('type') == 'active') selected @endif>Active</option>
    <option value="non-active" @if(request('type') == 'non-active') selected @endif>Non Active</option>
    
</select>
</div>


                        <table id="myTable"
                               class="table table-bordered table-striped @if(auth()->user()->isAdmin()) @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                            <thead>
                            <tr>

                                @can('category_delete')
                                    @if ( request('show_deleted') != 1 )
                                        <th style="text-align:center;"><input type="checkbox" class="mass"
                                                                              id="select-all"/>
                                        </th>@endif
                                @endcan

                                <th>@lang('labels.general.sr_no')</th>
                                <th>Image</th>
                                <th>@lang('labels.backend.teachers.fields.first_name')</th>
                                <th>@lang('labels.backend.teachers.fields.last_name')</th>
                                <th>@lang('labels.backend.teachers.fields.email')</th>
                                <th>@lang('labels.backend.teachers.fields.status')</th>
                                <th>Is Star</th>
                                <th>Rating</th>
                             
                                <th>Subject Teach</th>
                                @if( request('show_deleted') == 1 )
                                    <th>&nbsp; @lang('strings.backend.general.actions')</th>
                                @else
                                    <th>&nbsp; @lang('strings.backend.general.actions')</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- model -->
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered">
                     <div class="modal-content">
                         <form method="POST" enctype="multipart/form-data" action="">
                             {{csrf_field()}}
                             <div class="modal-header">
                                 <h5 class="modal-title">Upload Document</h5>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                             </div>
                             <div class="modal-body">
                                 <div class="mb-3">

                                     <label class="form-label">Image</label>
                                     <input type="file" class="form-control" name="image" accept="image/*">
                                 </div>
                             </div>
                             <div class="modal-footer">
                             <button type="button" class="btn btn-primary">Save</button>
                             <button type="button" class="btn btn-primary">Cancle</button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
@endsection

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route = '{{route('admin.teachers.get_data')}}?type={{$type}}';

            @if(request('show_deleted') == 1)
                route = '{{route('admin.teachers.get_data',['show_deleted' => 1])}}';
            @endif

            $(document).on("change","#teacherType",function(){

var type = $(this).val();

window.location.href = "?type="+type;


});

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
                            columns: [ 1, 2, 3, 4, 5 ]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5 ]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    {
                        "data": function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                        @endif
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "image", name: 'image'},
                    {data: "first_name", name: 'first_name'},
                    {data: "last_name", name: 'last_name'},
                    {data: "email", name: 'email'},
                    {data: "status", name: 'status'},
                    {data: "is_star", name: 'is_star'},
                    {data: "star_rating", name: 'star_rating'},
                    {data: "subject_teach", name: 'subject_teach'},
                    {data: "actions", name: 'actions'}
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
            @if(auth()->user()->isAdmin())
            $('.actions').html('<a href="' + '{{ route('admin.teachers.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
        });
        $(document).on('click', '.switch-input-r', function (e) {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.teachers.status') }}",
                data: {
                    _token:'{{ csrf_token() }}',
                    id: id,
                     type:'r'
                },
            }).done(function() {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
            });
        })
        $(document).on('click', '.switch-input-s', function (e) {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.teachers.status') }}",
                data: {
                    _token:'{{ csrf_token() }}',
                    id: id,
                    type:'s'
                },
            }).done(function() {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
            });
        });

        $(document).on("change",".cstar",function(){
              var id = $(this).data('id');
              var star = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.teachers.status') }}",
                data: {
                    _token:'{{ csrf_token() }}',
                    id: id,
                    star: star,
                    type:'cstar'
                },
            }).done(function() {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
            });
        })

    </script>

@endpush