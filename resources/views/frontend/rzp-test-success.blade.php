@extends('frontend.layout.sub-master')
@section('title')
<title>Payment Success | {{ env('APP_NAME') }}</title>
@stop

@section('page_css')
<style>
    .success-card {
        max-width: 500px;
        margin: 120px auto;
        border-radius: 15px;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
    }
    .success-icon {
        font-size: 70px;
        color: #28a745;
    }
</style>
@stop

@section('content')
<main class="container text-center">
    <div class="card success-card p-4">
        <div class="card-body">
            <div class="mb-3">
                <i class="bi bi-check-circle-fill success-icon"></i>
            </div>
            <h2 class="text-success fw-bold">Payment Successful!</h2>
            <p class="mt-3">
                Thank you for your payment. Your transaction has been completed successfully.  
                You will receive an email confirmation shortly.
            </p>
            
            <div class="mt-4">
                <a href="{{ url('/user/dashboard') }}" class="btn btn-primary me-2">
                    <i class="bi bi-house-door-fill"></i> Go to Home
                </a>
               
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
@stop
