@extends('backend.layouts.app')
@section('title')
    Edit BtoB Client - {{ env('APP_NAME') }}
@stop
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Add New BtoB Client</h3>
            <div class="float-right">
                <a href="{{ route('admin.btob.list') }}" class="btn btn-success">Lists</a>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12 col-lg-6 form-group">
                        <label for="org_name" class="form-label">Organization Name<span class="text-danger">*</span></label>
                        <input type="text" required value="{{ $team->org_name }}" class="form-control" id="org_name"
                            name="org_name" placeholder="Organization Name">
                    </div>

                    <div class="col-12 col-lg-6 form-group">
                        <label for="compname" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" required value="{{ $team->first_name }}" class="form-control" id="compname"
                            name="name" placeholder="Name">
                    </div>
                    <div class="col-12 col-lg-6 form-group">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" required value="{{ $team->email }}" class="form-control" id="email"
                            name="email" placeholder="Email">
                    </div>

                    <div class="col-12 col-lg-6 form-group">
                        <label for="code" class="form-label">Referal Code<span class="text-danger">*</span></label>
                        <input type="text" required value="{{ $team->coupon_code }}" class="form-control" id="code"
                            name="code" placeholder="Referal Code">
                    </div>



                </div>
                <div class="row pt-3">

                    <div class="col-md-12 text-center form-group">
                        <button type="submit" class="btn btn-info waves-effect waves-light ">
                            Update
                        </button>
                    </div>

                </div>
            </form>
        </div>


    @endsection
