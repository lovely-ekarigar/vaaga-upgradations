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
                              <h4 class="h4 pb-4 ">Waiting for payment......</h2>
                          @endif

                          <!--<button id="buttonPay" name="button" data-placement="left" title="Continue Payment" class="btn btn-success btn-card btn-sm ">@if(request('failed')) Retry Now @else  Continue Payment @endif</button>-->


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
		<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script type="text/javascript">
 


var options = {
    "key": "{{env('RZP_KEY')}}", // Enter the Key ID generated from the Dashboard
    
    "name": "VAAGA ACADMEY",
    "description": "Course Payment",
    "image": "https://vaagaacademy.com/newassets/img/logo.webp",
    "order_id": "{{$order->order_id}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "callback_url": "https://vaagaacademy.com/pay-confirm/{{$order->reference_no}}/{{strtoupper($payfor)}}",
    "prefill": {
        "name": "{{Auth::user()->first_name}} {{Auth::user()->last_name}}",
        "email": "{{Auth::user()->email}}",
        "contact": "{{Auth::user()->phone}}"
    },
    
    "theme": {
        "color": "#febc5a"
    }
};
var rzp1 = new Razorpay(options);

    rzp1.open();
    e.preventDefault();

</script>

@endsection