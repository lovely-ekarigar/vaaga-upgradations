<?php
use App\Models\Batch;
use App\Models\Course;
use App\Models\Earning;
use App\Models\TeacherBatch;

?>
@extends('backend.layouts.app')

@section('title', __('Tutor Payments').' | '.app_name())

@section('content')

    
            <div class="card">
                <div class="card-header">
                    <strong>Welcome {{$teacher_name->name}}</strong>
                    <div class="float-right">
                        <select class="form-control form-select" name="bid" id="selectbatch">
                            <option value="0">All Batch</option>
                            @foreach($batches as $bt)
                            <option value="{{$bt->id}}" @if(request('bid') == $bt->id) selected @endif >{{$bt->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="card text-white bg-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$total_earnings}}</h1>
                                    <h3 style="font-size: 1rem;">Total Amount</h3>
                                </div>
                            </div>
                        </div>
  @if(request('bid')=='0' || request('bid')==null)
                        <div class="col-md-3 col-12">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$total_withdrawal}}</h1>
                                    <h3 style="font-size: 1rem;">Total Withdrawal </h3>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3 col-12">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$total_withdrawal_pending}}</h1>
                                    <h3 style="font-size: 1rem;">Pending Withdrawal </h3>
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-md-3 col-12">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$total_balance}}</h1>
                                    <h3 style="font-size: 1rem;">Balance</h3>
                                </div>
                            </div>
                        </div>
                        @endif
                      
                       

                    </div>
                </div>
        </div><!--card-->
    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Bank Details</h3>

            <div class="float-right">
                <!--  <a href="{{ route('admin.teacher_payment_create',['teacher_id'=>$teacher_name->id]) }}"
                   class="btn btn-success">@lang('strings.backend.general.app_add_new')</a> -->

            </div>

        </div>

      
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @if($bank)

                   <table class="table table-bordered table-striped">
                    <tr>
                        <th> Bank Name:</th>
                        <td> <strong>{{$bank['bank_name']}}</strong></td>
                       
                    </tr>
                      <tr>
                        <th> IFSC Code:</th>
                        <td> <strong>{{$bank['ifsc_code']}}</strong></td>
                       
                    </tr>
                      <tr>
                        <th>  A/c:</th>
                        <td> <strong>{{$bank['account_number']}}</strong></td>
                       
                    </tr>
                      <tr>
                        <th> Beneficiary Name:</th>
                        <td> <strong>{{$bank['account_name']}}</strong></td>
                       
                    </tr>
                       
                        </table>
                    

                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Payments</h3>

            <div class="float-right">
                 <a href="javascript:void(0)"
                   class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Make Payment</a> 

            </div>

        </div>

        <?php 

$status = [__('labels.backend.payments.status.pending'), __('labels.backend.payments.status.approved'), __('labels.backend.payments.status.rejected')];
$patype = ["Bank", "Paypal", "Offline"];
        ?>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table class="table table-bordered table-striped dtable">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>Amount</th>
                                <th>Payment Type</th>
                                <th>Request Type</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Date</th>
                                <th>Action</th>
                              
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                                @foreach($withdrawls as $w)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$w->amount}}</td>
                                    <td>{{$patype[$w->payment_type]}}</td>
                                     <td>{{ucwords($w->request_from)}}</td>
                                    <td>{{$status[$w->status]}}</td>
                                    <td>{{$w->remarks}}</td>
                                    <td>{{date('d M Y',strtotime($w->created_at))}}</td>
                                    <td>
                                        <?php 
  $html = '';
    if ($w->status == 0) {
                    $html .= '<a href="javascript:void(0)" data-status="1" data-id="'.$w->id.'" data-url="'.route('admin.payments.payments_request_update').'"class="btn btn-xs btn-info mb-1 update-status-request">'.__('labels.backend.payments.approve').'</a>';
                    $html .= '   <a href="javascript:void(0)" data-status="2" data-id="'.$w->id.'" data-url="'.route('admin.payments.payments_request_update').'"class="btn btn-xs btn-danger mb-1 update-status-request">'.__('labels.backend.payments.reject').'</a>';
                }
                echo $html;
                                        ?>

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

  <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Earnings</h3>

            <div class="float-right">
                <!--  <a href="{{ route('admin.teacher_payment_create',['teacher_id'=>$teacher_name->id]) }}"
                   class="btn btn-success">@lang('strings.backend.general.app_add_new')</a> -->

            </div>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                         <table id="earningTable" class="table table-bordered table-striped dtable">
                                <thead>
                                <tr>
                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>Batch</th>
                                    <th>Course</th>
                                    <th>Batch Completion</th>
                                    <th>Per Hour Fees</th>
                                    <th>Max. Batch Hours</th>
                                    <th>Mins. Taught</th>
                                    <th>Hrs. Taught</th>
                                    <th>Subtotal</th>
                                   
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($tas as $k=>$t)
                                    <tr>

                                        <td>{{$k+1}}</td>
                                        <td>
                                          <?php $batch = Batch::find($t->bid); ?>
                                            @if($batch)
                                            {{$batch->name}}
                                            @endif
                                        </td>
                                        <td>
                                             <?php 

                     $crs = new Course();
                     echo $crs->getCouseNameWithCat($batch->cid);
                     ?>
                                        </td>
                                        <td> <?php 
                                       if($batch->is_completed=='0'){
                                            $bo = new Batch;
                                            $br = $bo->bacthCompletion($t->bid,$t->tid);

                                             if($br['total']!=0){
$completion = $br['completed']/$br['total'];
            }else{
            $completion = 0;
        }
    }else{
$completion=1;
    }

        $er = new Earning;

        $tb=TeacherBatch::where("tid",$t->tid)->where("bid",$t->bid)->first();




                                            ?>
                                            {{floor($completion*100)}}%</td>
                                        <td>₹{{$t->fees}}</td>
                                        <td>{{$tb->fees_schedule}} Hrs.</td>
                                        <td>{{$er->getBatchHours($t->tid,$t->bid)}} Mins.</td> 
                                        <td>{{floor($er->getBatchHours($t->tid,$t->bid)/60)}} Hrs.</td> 
                                        <td>₹{{$er->totalEarnings($t->tid,$t->bid)}}</td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Make Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="requestForm" novalidate>
        @csrf
      <div class="modal-body">
 @if($total_balance>0)
        <div class="row">
            
            <div class="col-12 mb-3">
                <label>Enter Amount</label>
                <input type="number" required class="form-control" id="paid_amount" min="1" max="{{$total_balance}}" name="amount">
            </div>

<div class="col-12 mb-3">
                <label>Remarks</label>
                <input type="hidden" name="user_id" value="{{$id}}">
                <textarea class="form-control" name="remarks" required></textarea>
            </div>

<div class="col-12 mb-3">
<p>
    Max Payment can be Done: <strong>{{$total_balance}}</strong>
</p>
</div>
 </div>
@else
    <center>    <h4 class="text-danger">Not enough balance</h4></center>

        @endif
      </div>
      <div class="modal-footer">
      @if($total_balance>0)
        <button type="submit" id="payNow" class="btn btn-primary">Pay Now</button>

        @endif
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" id="updateRequestModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateRequestForm" class="form-material" method="post" accept-charset="UTF-8" data-url=''>
                <div class="modal-header">
                    <h4 class="modal-title">@lang('labels.general.buttons.update')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div id="update-notification-container"></div>
                    @csrf
                    <input type="hidden" name="status" value="" id="request_status">
                    <input type="hidden" name="id" value="" id="update_id">
                    {!! Form::label('remarks', trans('labels.backend.payments.fields.remarks'), ['class' =>
                    'control-label']) !!}
                    {!! Form::textarea('remarks', old('remarks'), ['class' => 'form-control ', 'placeholder' =>
                    trans('labels.backend.payments.fields.remarks')]) !!}

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal">@lang('labels.general.buttons.cancel')</button>
                    <button type="submit"
                        class="btn btn-primary waves-effect waves-light ">@lang('labels.general.buttons.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('after-scripts')
<script type="text/javascript">
    $(".dtable").DataTable();
    var max_amount={{$total_balance}};

    $("form#requestForm").on("submit",function(e){
        var amount = $("#paid_amount").val();
        $("#amountError").remove();

        if(amount>=1 && amount<=max_amount){

        }else{
            // alert("Amount errors");
            $("#paid_amount").parent().append('<div id="amountError" style="display:block;" class="invalid-feedback">Amount must be greater than 0 and less than '+max_amount+'</div>')
            
            e.preventDefault();
 
        }

    }); 

    $(document).on("change",function(){
         $("#payNow").removeAttr("disabled");
    })

     $(document).on('click', '.update-status-request', function () {
        $('#updateRequestForm').attr('data-url', $(this).data('url'));
        $('#request_status').val($(this).data('status'));
        $('#update_id').val($(this).data('id'));
        $('#updateRequestModal').modal('show');
    });

    $(document).on('submit', 'form#updateRequestForm', function (event) {
        event.preventDefault();
        var url = $(this).data('url');
        var form = this;
        var data = new FormData(this);

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
        }).done(function (response) {
            $(form)[0].reset();
            location.reload();
        }).fail(function (jqXhr) {
            var data = jqXhr.responseJSON;
            var errorsHtml = '<div class="alert alert-danger"><ul>';

            $.each(data.errors, function (key, value) {
                errorsHtml += '<li>' + value + '</li>';
            });
            errorsHtml += '</ul></div>';
            $('#update-notification-container').html(errorsHtml);
        });

    });


    $("#selectbatch").on('change',function(){
        var bid = $(this).val();
        window.location.href = "?bid="+bid;
    })
</script>
   
@endpush