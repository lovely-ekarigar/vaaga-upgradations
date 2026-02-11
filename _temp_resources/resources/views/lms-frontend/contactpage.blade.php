@extends('frontend.layout.sub-master')
@section('content')

<div class="breadcrumb-bar">
<div class="container">
<div class="row">
<div class="col-md-12 col-12">
<div class="breadcrumb-list">
<nav aria-label="breadcrumb" class="page-breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="https://mentortle.com/">Home</a></li>
<li class="breadcrumb-item">Pages</li>
<li class="breadcrumb-item">Contact Us</li>
</ol>
</nav>
</div>
</div>
</div>
</div>
</div>


<div class="page-banner">
<div class="container">
<div class="row">
<div class="col-md-12 col-12">
<h1 class="mb-0">Contact Us</h1>
</div>
</div>
</div>
</div>

<div class="page-content">
<div class="container">
<div class="row">
<div class="col-lg-6 col-md-8 mx-auto">
<div class="support-wrap">
<h5>Submit a Request</h5>
<form action="#">
<div class="form-group">
<label>First Name</label>
<input type="text" class="form-control" placeholder="Enter your first Name">
</div>
<div class="form-group">
<label>Email</label>
<input type="text" class="form-control" placeholder="Enter your email address">
</div>
<div class="form-group">
<label>Subject</label>
<input type="text" class="form-control" placeholder="Enter your Subject">
</div>
<div class="form-group">
<label>Description</label>
<textarea class="form-control" placeholder="Write down here" rows="4"></textarea>
</div>
<button class="btn btn-submit">Submit</button>
</form>
</div>
</div>
</div>
</div>
</div>

@stop