@extends('frontend.layout.sub-master')
@section('title')
<title> Payment | {{env('APP_NAME')}}</title>
@
@section('page_css')
<style type="text/css">
  .card-body {
    padding: 8px 0px 6px 0px !important;
}
</style>
@stop
@section('content')

<main>
  

   <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-2 my-3">
          </div>
          <div class="col-lg-8 my-3">
            <div class="row">
              
              <div class="col-sm-12 col-lg-12">
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="card-body text-center">
                      <div class="py-4">
                        <img src="{{asset('newassets/img/no-data-found.png')}}">
                      </div>
                      @if(request('failed'))
                        <h4 class="h4 pb-4 text-danger">Payment Failed....</h2>
                          @else
                              <h4 class="h4 pb-4 ">Redirecting to payment gateway......</h2>
                          @endif

                          <button id="buttonPay" name="button" data-placement="left" title="Continue Payment" class="btn btn-success btn-card btn-sm ">@if(request('failed')) Retry Now @else  Continue Payment @endif</button>
<form action="https://payments.airpay.co.in/pay/index.php" method="post" id="payNow" class="main-transaction-form">
    
<input type="hidden"  name="buyerEmail" value="{{Auth::user()->email}}" />
<input type="hidden"  name="buyerPhone" value="{{Auth::user()->phone}}" />
<input type="hidden"  name="buyerFirstName" value="{{Auth::user()->first_name}}" />
<input type="hidden"  name="buyerLastName" value="{{Auth::user()->last_name}}" />
<input type="hidden"  name="buyerAddress" value="" />
<input type="hidden"  name="buyerCity" value="" />
<input type="hidden"  name="buyerState" value="" />
<input type="hidden"  name="buyerCountry" value="" />
<input type="hidden"  name="buyerPinCode" value="" />
<input type="hidden"  name="orderid" value="{{strtoupper($payfor)}}-{{$order->id}}-{{rand()}}" />
<input type="hidden"  name="amount" value="{{$order->amount}}" />

<input type="hidden"  name="customvar" value="" />
<input type="hidden"  name="txnsubtype" value="" />
<input type="hidden"  name="token" value="" />
<input type="hidden"  name="wallet" value="" />
<input type="hidden"  name="currency" value="356" />
<input type="hidden"  name="isocurrency" value="INR" />

</form>

                    </div>
                  </div>
                </div>

         
              

            </div>
          </div>
         
        </div>
      </div>
    </section>
</main>

@stop


@section('page_js')
<script type="text/javascript" src='/sha256.jquery.debug.js'></script>
<script type="text/javascript" src='/main.js'></script>
<script type="text/javascript">
  var chmod;
var merchantDetails;
merchantDetails = {
      'username': 'K3dYCrQvmz', // Username
      'password': 'cAyd2KuT', // Password
      'secret': 'AQyZGnfVm6gdFqzf', // API key
      'mercid': '287162' //Merchant ID 
    };
      @if(!request('failed'))
      redirectPaymentUrl();
     @endif
$(document).on("click","#buttonPay",function(){
    
    redirectPaymentUrl();
    
})


</script>

@endsection