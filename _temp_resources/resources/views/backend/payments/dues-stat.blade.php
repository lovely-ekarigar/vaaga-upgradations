@extends('backend.layouts.app')

@section('title', __('Due Payments').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Payment Summary</h3>
            <div class="float-right mb-2">
    <select class="form-control form-select" id="studentType" name="type" style="width: 130px;display: inline-block;margin-left: 20px;">

    <option value="all" @if(request('type') == 'all') selected @endif>All</option>
    <option value="dues" @if(request('type') == 'dues') selected @endif>Dues Report</option>
    
</select>
</div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
<div class="d-block">
                           
                        </div>



<div class="row">
    <div class="col-md-4 col-12">
                            <a href="#" style="text-decoration: none;">
                            <div class="card text-white bg-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$ter}}</h1>
                                    <h3>Total Earnings</h3>
                                </div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-4 col-12">
                            <a href="#" style="text-decoration: none;">
                            <div class="card text-white bg-success  text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$ter-$bal}}</h1>
                                    <h3>Total Paid</h3>
                                </div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-4 col-12">
                            <a href="#" style="text-decoration: none;">
                            <div class="card text-white bg-danger text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$bal}}</h1>
                                    <h3>Total Balance</h3>
                                </div>
                            </div>
                            </a>
                        </div>
</div>

                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                              
                                <th>Tutor Name</th>
                                <th>Total Earning</th>
                                <th>Total Paid</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                         

                            <tbody>

                                @foreach($tlist as $k=>$t)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$t->first_name}} {{$t->last_name}}</td>
                                    <td>{{$t->earning }}</td>
                                    <td>{{$t->earning - $t->balance}}</td>
                                    <td>{{$t->balance}}</td>
                                    <td>
                                        <a href="/user/teacher-payment-list/{{$t->id}}" target="_blank" class="btn btn-sm btn-primary">View Summary</a>
                                    </td>
                                    
                                </tr>

                                @endforeach


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