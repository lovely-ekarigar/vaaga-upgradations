@extends('backend.layouts.app')

@section('title', __('Tutor Attendance').' | '.app_name())

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Tutor Attendance</h3>
             @if(auth()->user()->hasRole('administrator'))
            <div class="float-right" style="width: 70%;">
                <div class="row">
                    <div class="col-4">
                         <select class="form-control select2" name="tid" id="tid">
                    <option value="">Select Specific Tutor</option>
                    @foreach($tutors as $t)
                    <option value="{{$t->id}}" @if(request('tid')==$t->id) selected @endif >{{$t->name}}</option>
                    @endforeach
                </select> 
                    </div>
                    @if(request('tid')!=null && request('tid')!='')
                     <div class="col-4">
                         <select class="form-control select2" name="bid" id="bid">
                    <option value="">Select Batch</option>
                    @foreach($batches as $t)
                    <option value="{{$t->id}}"  @if(request('bid')==$t->id) selected @endif>{{$t->name}}</option>
                    @endforeach
                </select> 
                    </div>


 <div class="col-4">
<input type="text" name="dates" class="form-control" value="{{date('m/d/Y',strtotime($from))}} -  {{date('m/d/Y',strtotime($to))}}" >

 </div>



                    @endif
                </div>
               

            </div>
            @endif

        </div>
        <div class="card-body">
               @if(auth()->user()->hasRole('administrator'))
               <div class="row">
               <div class="col-md-4 col-12">
                        
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$tnx}}</h1>
                                    <h3>Total Mins. Taught</h3>
                                </div>
                            </div>
                           
                        </div>

                           <div class="col-md-4 col-12">
                        
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{floor($tnx/60)}}</h1>
                                    <h3>Total Hrs. Taught</h3>
                                </div>
                            </div>
                           
                        </div>

                        </div>
               @endif
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable"
                               class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.categories.fields.name')</th>
                                <th>Minutes</th>
                                <th>Batch</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                                @foreach($teacher_attendance_list as $teacher)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$teacher->teacher->name}}</td>
                                    <td>{{$teacher->hours}} Mins.</td>
                                    <td>{{$teacher->batch->name}}</td>
                                    <td>{{$teacher->date}}</td>
                                    
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function () {

            $(document).on("change","#tid",function(){
                var tid = $(this).val();
                window.location.href = "?tid="+tid;
            })

            $(document).on("change","#bid",function(){
                var bid = $(this).val();
                var tid = $("#tid").val();
                window.location.href = "?tid="+tid+"&bid="+bid;
            })
         
            // $('#myTable').DataTable();
            $('#myTable').DataTable({
                 dom: 'Bfrtip',
                processing: false,
                serverSide: false,
                iDisplayLength: 10,
                retrieve: false,
                
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [1, 2, 3,4,]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3,4,]
                        }
                    },
                    'colvis'
                ],
                

                // createdRow: function (row, data, dataIndex) {
                //     $(row).attr('data-entry-id', data.id);
                // },
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

        $('input[name="dates"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {

                    var bid = $("#bid").val();
                var tid = $("#tid").val();
                window.location.href = "?tid="+tid+"&bid="+bid+"&from="+start.format('YYYY-MM-DD')+"&to="+end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });


    </script>
@endpush