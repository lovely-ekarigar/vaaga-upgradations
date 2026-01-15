@extends('frontend.layout.sub-master')
@section('title')
<title> Payment | {{ env('APP_NAME') }}</title>
@stop

@section('page_css')
<style type="text/css">
  .card-body {
    padding: 8px 0px 6px 0px !important;
  }
  .retry-box {
    margin-top: 20px;
  }
</style>
@stop

@section('content')
<main>
   <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-2 my-3"></div>
          <div class="col-lg-8 my-3">
            <div class="row">
              <div class="col-sm-12 col-lg-12">
                  <div class="card hover-scale overflow-hidden hover-top">
                    <div class="card-body text-center">
                      <div class="py-4">
                        <img src="{{asset('newassets/img/no-data-found.png')}}">
                      </div>
                      <h4 class="h4 pb-4 text-danger" id="payment-status">
                          @if(request('failed'))
                              Payment Failed....
                          @else
                              Waiting for payment......
                          @endif
                      </h4>
                      <div id="retry-section" class="retry-box d-none">
                          <button onclick="location.reload()" class="btn btn-warning">Retry Payment</button>
                      </div>
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
    "key": "{{ env('RZP_KEY') }}",
    "name": "VAAGA ACADEMY",
    "description": "Test Series Payment",
    "image": "https://vaagaacademy.com/newassets/img/logo.webp",
    "order_id": "{{ $rzp_id }}",
    "callback_url": "https://vaagaacademy.com/pay-confirm-test/{{ $tp->id }}",
    "prefill": {
        "name": "{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}",
        "email": "{{ Auth::user()->email }}",
        "contact": "{{ Auth::user()->phone }}"
    },
    "theme": {
        "color": "#febc5a"
    },
    "modal": {
        // If user closes Razorpay popup
        "ondismiss": function() {
            document.getElementById("payment-status").innerText = "Payment Closed!";
            document.getElementById("retry-section").classList.remove("d-none");
        }
    }
};

var rzp1 = new Razorpay(options);

// Open Razorpay on load
rzp1.open();

// Payment Failed Handling
rzp1.on('payment.failed', function (response){
    document.getElementById("payment-status").innerText = "Payment Failed!";
    document.getElementById("retry-section").classList.remove("d-none");
});

// Retry Button
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("retryPayment").addEventListener("click", function() {
        var rzp2 = new Razorpay(options);
        rzp2.open();
    });
});

</script>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
@endsection
