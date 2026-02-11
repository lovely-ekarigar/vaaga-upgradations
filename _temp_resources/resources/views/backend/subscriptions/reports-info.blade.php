@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Subscription Due Reports | '.app_name())


@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline mb-0">{{$order->user->name}} Subscription  Reports</h3>
            <div class="float-right">
                
                 <a class="btn btn-sm btn-success trigger" data-toggle="modal" data-target="#exampleModalCenter" href="javascript:void(0)">Trigger Email & SMS</a>
                 <a class="btn btn-sm btn-primary" href="{{route('admin.subscription.report')}}">All Subscriptions</a>
            </div>

        </div>
        <div class="card-body">
            <div class="d-block">
                <ul class="list-inline">
                  
                </ul>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      
                        <th>@lang('labels.general.sr_no')</th>
                        <th>Amount</th>
                        <th>Cycle No.</th>
                        <th>Date</th>
                       
                        <th>&nbsp; Invoice</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $count=0 @endphp

                        @foreach($subscriptions as $s)
                         @php $count++ @endphp
                        <tr>
                            <td>{{$count}}</td>
                            <td>₹{{$s->amount}}</td>
                            <td>{{$count+1}}</td>
                            <td>{{date("d M Y",strtotime($s->created_at))}}</td>
                            <td>
                                <a href="https://vaagaacademy.com/user/student-view-invoice/{{$s->order_id}}/show/{{$s->id}}" class="btn btn-xs btn-primary mb-1"><i class="icon-eye"></i></a>
                                <a href="https://vaagaacademy.com/user/student-view-invoice/{{$s->order_id}}/download/{{$s->id}}" class="btn btn-xs btn-danger mb-1"><i class="fa fa-file-pdf"></i></a>
                            </td>
                        </tr>

                        @endforeach
                        @if($order)
  @php $count++ @endphp
                          <tr>
                            <td>{{$count}}</td>
                            <td>₹{{$order->amount}}</td>
                            <td>1</td>
                            <td>{{date("d M Y",strtotime($order->created_at))}}</td>
                            <td>
                                 <a href="https://vaagaacademy.com/user/student-view-invoice/{{$order->id}}/show/" class="btn btn-xs btn-primary mb-1"><i class="icon-eye"></i></a>
                                <a href="https://vaagaacademy.com/user/student-view-invoice/{{$order->id}}/download/" class="btn btn-xs btn-danger mb-1"><i class="fa fa-file-pdf"></i></a>
                            </td>
                        </tr>

                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Trigger Email & SMS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST">

                    @csrf
                   
                    
               
      <div class="modal-body">
    <p>
        After clicking <strong>Trigger Now</strong> an email and SMS will be sent to student immediatly.
    </p>
      </div>
      <div class="modal-footer">
       <input type="submit" name="submit" class="btn btn-sm btn-success" value="Trigger Now">
      </div>

       </form>
    </div>
  </div>
</div>


@stop

@push('after-scripts')
    <script>

        $("#myTable").DataTable();
      
    </script>
@endpush