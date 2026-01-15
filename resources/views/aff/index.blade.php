@extends('aff.layout')
@section('content')
<div class="row">
  <div class="col-xl-6 col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Affiliate Link</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-content collapse show">
        <div class="card-body pt-0">
          <div class="row mb-1">
            <div class="col-12 col-md-12">
              <input type="text" class="form-control" value="https://livetutorials.in?aff_id={{Session::get('aff')->code}}" name="">
              <br>
              <p>Affiliate Comission: <strong> {{$aff->commision}}%</strong></p>
            </div>
           
          </div>
         
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-12">
    
    <div class="row">
      <div class="col-lg-6 col-12">
        <div class="card pull-up">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h6 class="text-muted">Total Earning </h6>
                  <h3>₹{{$aff->total_earnings}}</h3>
                </div>
                <div class="align-self-center">
                  <i class="icon-trophy success font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-12">
        <div class="card pull-up">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h6 class="text-muted">Total Balance </h6>
                  <h3>₹{{$aff->total_earnings - $aff->total_withdrawl}}</h3>
                </div>
                <div class="align-self-center">
                  <i class="icon-trophy success font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<div class="col-lg-6 col-12">
        <div class="card pull-up">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h6 class="text-muted">Total Withdrawl </h6>
                  <h3>₹{{$aff->total_withdrawl}}</h3>
                </div>
                <div class="align-self-center">
                  <i class="icon-trophy success font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-12">
        <div class="card pull-up">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h6 class="text-muted">Total Users</h6>
                  <h3>{{$ocount}}</h3>
                </div>
                <div class="align-self-center">
                  <i class="icon-call-in danger font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop


   