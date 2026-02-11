@extends('backend.layouts.app')

@section('title', __('Boards').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Students</h3>
            

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
<div class="d-block">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.students.index') }}" style="">All</a>
                                </li>
                                |
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.students.index', ['show_deleted' => 1]) }}" style="font-weight: 700">Trash</a>
                                </li>
                            </ul>
                        </div>
<div class="float-right mb-2">
    <select class="form-control form-select" id="studentType" name="type" style="width: 130px;display: inline-block;margin-left: 20px;">

    <option value="all" @if(request('type') == 'all') selected @endif>All</option>
    <option value="active" @if(request('type') == 'active') selected @endif>Active</option>
    <option value="non-active" @if(request('type') == 'non-active') selected @endif>Non Active</option>
    
</select>
</div>
                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>Image</th>
                                <th>@lang('labels.backend.contacts.fields.name')</th>
                                <th>@lang('labels.backend.contacts.fields.email')</th>
                                <th>@lang('labels.backend.contacts.fields.phone')</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                           @php
                            $sl = 1;
                            @endphp
                                @foreach($users as $student)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td><img src= "{{$student->picture}}"   onerror="this.src='/profile-user.png'" style="height:35px;width:35px;border-radius: 50%;" /></td>
                                    <td>{{$student->name}}</td>
                                    <td>{{$student->email}}</td>
                                    <td>{{$student->phone}}</td>
                                    <td>
                                        <label class="switch switch-lg switch-3d switch-primary">
<input onclick="window.location='{{ route('admin.students.updatestatus', ['id'=>$student->id]) }}'" name="student_active" class="switch-input" type="checkbox" value="{{ $student->id}}" id="{{ $student->id}}" data-id="{{ $student->id}}" {{ $student->active ? 'checked' : '' }}>
                                            <span class="switch-label"></span><span class="switch-handle"></span>
                                        </label>
                                    </td>
                                    <td>

                                        @if(!request('show_deleted'))

                                        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-xs btn-primary mb-1"><i class="icon-eye"></i></a>
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-xs btn-primary mb-1"><i class="icon-pencil"></i></a>

                                      <a href="{{ route('admin.students.delete', $student->id) }}" onclick="return confirm('Are you sure you want to delete Student?');" class="btn btn-xs btn-danger mb-1">
                                        <i class="fa fa-trash"></i>
                                      </a>

                                      <a href="{{route('admin.students.batch.list', ['id' => $student->id])}}" class="btn btn-outline-warning mb-1 ml-1">Batch</a>
                                      <a href="{{route('admin.students.orders', ['id' => $student->id])}}" class="btn btn-outline-info mb-1 ml-1">Orders</a>

                                      @else
<a href="{{ route('admin.students.recover', $student->id) }}" class="btn btn-xs btn-primary mb-1">Recover</a>

                                      @endif
                                      
                                    </td>
                                </tr>
                                @endforeach

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {

            $(document).on("change","#studentType",function(){

var type = $(this).val();

window.location.href = "?type="+type;


});
           

            $('#myTable').DataTable({
                processing: false,
                serverSide: false,
                iDisplayLength: 10,
                retrieve: false,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    'colvis'
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });

        });

    </script>
@endpush