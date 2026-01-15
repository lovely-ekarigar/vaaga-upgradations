@extends('aff.layout')
@section('content')
<div class="row">
  <div class="col-xl-12 col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Total Earnings: <strong>₹{{$aff->total_earnings}}</strong></h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-content collapse show">

        <div class="card-body pt-0">
           
          <table class="table table-stripped table-bordered">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Purchased Amount</th>
                <th>Comission</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @php $count=0; @endphp
              @foreach($earnings as $er)
               @php $count++; @endphp
              <tr>
                <td>{{$count}}</td>
                <td>₹{{$er->amount}}</td>
                <td>₹{{$er->amount*$aff->commision/100}}</td>
                <td>{{$er->created_at}}</td>
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


   