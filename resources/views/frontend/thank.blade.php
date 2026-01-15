@extends('frontend.layout.sub-master')
@section('title')
<title>  Thank You | {{env('APP_NAME')}}</title>
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

  <section class="section  border-bottom mt-8"  style=" background: #f6f9fc;;">

    <div class="container">

      <div class="row justify-content-center pb-8">

        <div class="col-lg-8 col-xl-7 text-center">
          <img src="/verified.png" >

          <h2 class="h1 mb-3">Course Purchase Successful</h2>

          <p class="lead">Thank you for choosing VaaGa Academy. Kindly visit dashboard to view your all purchased courses.</p>

          <a class="btn btn-primary flex-shrink-0" href="/user/dashboard"> Visit Dashboard</a>


        </div>
      </div>
    </div>
  </section>



</main>

@stop