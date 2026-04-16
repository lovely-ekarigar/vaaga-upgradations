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

// Debug: Log the order details
console.log("Payment Type:", "{{ $payfor }}");
console.log("Order ID:", "{{ $payfor == 'SUBSCRIPTION' ? $order->payment_ref : $order->order_id }}");
console.log("Reference No:", "{{ $order->reference_no }}");
console.log("Amount:", "{{ $order->amount }}");

var razorpayOrderId = "{{ $payfor == 'SUBSCRIPTION' ? $order->payment_ref : $order->order_id }}";

if(!razorpayOrderId || razorpayOrderId === "" || razorpayOrderId === "null") {
    alert("Payment Error: Razorpay order ID not found. Please try again or contact support.");
    console.error("Razorpay order_id is empty or null");
} else {
    var options = {
        "key": "{{env('RZP_KEY')}}", // Enter the Key ID generated from the Dashboard
        
        "name": "VAAGA ACADEMY",
        "description": "{{ $payfor == 'SUBSCRIPTION' ? 'Subscription Renewal' : 'Course Payment' }}",
        "image": "https://vaagaacademy.com/newassets/img/logo.webp",
        "order_id": razorpayOrderId, //Razorpay Order ID
        "callback_url": "{{ url('/pay-confirm/'.$order->reference_no.'/'.strtoupper($payfor)) }}",
        "prefill": {
            "name": "{{Auth::user()->first_name}} {{Auth::user()->last_name}}",
            "email": "{{Auth::user()->email}}",
            "contact": "{{Auth::user()->phone}}"
        },
        
        "theme": {
            "color": "#febc5a"
        },
        "modal": {
            "ondismiss": function(){
                console.log("Razorpay modal dismissed by user");
            }
        }
    };
    
    console.log("Razorpay options:", options);
    
    try {
        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response){
            console.error("Payment failed:", response.error);
            alert("Payment failed: " + response.error.description);
        });
        
        // Open Razorpay checkout on page load
        rzp1.open();
    } catch(e) {
        console.error("Razorpay initialization error:", e);
        alert("Payment initialization failed. Please refresh and try again.");
    }
}

</script>

@endsection