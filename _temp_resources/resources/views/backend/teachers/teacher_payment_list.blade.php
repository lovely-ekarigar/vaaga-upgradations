<?php
use App\Models\Batch;
?>
@extends('backend.layouts.app')

@section('title', __('Tutor Payments').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Tutor Payments</h3>
            <div class="float-right">
                <a href="{{ route('admin.teacher_payment_create') }}"
                   class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable"
                               class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.categories.fields.name')</th>
                                <th>Payment Mode</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Remark</th>
                                <th>@lang('strings.backend.general.actions')</th>
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                                @foreach($teacher_payment_list as $teacher)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$teacher->teacher->name}}</td>
                                    <td>{{$teacher->payment_mode}}</td>
                                    <td>{{$teacher->date}}</td>
                                    <td>{{$teacher->amount}}</td>
                                    <td>{{$teacher->remark}}</td>
                                    <td>
                                      <a href="{{route('admin.teacher_payment_edit',['id' => $teacher->id])}}" class="btn btn-xs btn-info mb-1">
                                        <i class="icon-pencil"></i>
                                      </a>
                                      <a href="{{route('admin.teacher_payment_destroy',['id' => $teacher->id])}}" class="btn btn-xs btn-danger mb-1 delete_warning">
                                        <i class="fa fa-trash"></i>
                                      </a>
                                      
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
         
            $('#myTable').DataTable();
           


            $(document).on('click', '.delete_warning', function () {
                const link = $(this);
                const cancel = (link.attr('data-trans-button-cancel')) ? link.attr('data-trans-button-cancel') : 'Cancel';

                const title = (link.attr('data-trans-title')) ? link.attr('data-trans-title') : "{{ trans('labels.backend.categories.not_allowed') }}";

                swal({
                    title: title,
                    icon: 'error',
                    showCancelButton: true,
                    cancelButtonText: cancel,
                    type: 'info'
                })
            });


        });
    </script>
@endpush