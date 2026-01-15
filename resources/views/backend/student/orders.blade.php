<?php 
use App\Models\Course;
?>
@extends('backend.layouts.app')

@section('title', __('Student Orders').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">{{$user->name}}  Orders</h3>
            
<div class="float-right mb-2">
   <a href="/user/students" class="btn btn-sm btn-success">Students</a>
</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">


                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>Reference No.</th>
                                <th>Invoice No.</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Order Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                        

                            <tbody>

                                @foreach($orders as $k=>$o)
                                <tr>
                                    
                                    <td>{{$k+1}}</td>
                                    <td>{{$o->reference_no}}</td>
                                    <td>{{$o->id}}</td>
                                    <td>
                                        
<?php 

 $items = "";
                foreach ($o->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $key . '. ' . $crs->getCouseNameWithCat($item->item->id) . "<br>";
                    }

                }

echo $items;
?>


                                    </td>
                                    <td>{{$o->amount}}</td>
                                    <td>
                                        <?php 
$payment_status='';
 if ($o->status == 0) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.pending');
                } elseif ($o->status == 1) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.completed');
                } else {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.failed');
                }
                echo $payment_status;
                                        ?>
                                    </td>
                                    <td>{{$o->updated_at->format('d M, Y | h:i A')}}</td>
                                    <td>
                                        
<?php 

 $view = "";

                $view = view('backend.datatable.action-view')
                    ->with(['route' => route('admin.orders.show', ['order' => $o->id])])->render();

                if ($o->status == 0) {
                    $complete_order = view('backend.datatable.action-complete-order')
                        ->with(['route' => route('admin.orders.complete', ['order' => $o->id])])
                        ->render();
                    $view .= $complete_order;
                }

                if ($o->status == 0) {
                    $delete = view('backend.datatable.action-delete')
                    ->with(['route' => route('admin.orders.destroy', ['order' => $o->id])])
                    ->render();

                    $view .= $delete;
                }

                echo $view;
?>


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