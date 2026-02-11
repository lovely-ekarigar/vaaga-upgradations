@extends('backend.layouts.app')

@section('title', __('Feedback').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Feedback</h3>
            

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable"
                               class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Name</th>
                                <th>Batch Name</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                                @foreach($feedback_list as $feedback)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$feedback->user->first_name}} {{$feedback->user->last_name}}</td>
                                    <td>{{$feedback->batch->name}}</td>
                                    <td>{{$feedback->message}}</td>
                                    
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
         
            $('#myTable').DataTable({
               
                iDisplayLength: 10,
                                
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [1, 2, 3, 5]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 5]
                        }
                    },
                    'colvis'
                ],
                

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons: {
                        colvis: '{{trans("datatable.colvis")}}',
                        pdf: '{{trans("datatable.pdf")}}',
                        csv: '{{trans("datatable.csv")}}',
                    }
                }
            });
           


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