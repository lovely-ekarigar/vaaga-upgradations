@extends('backend.layouts.app')

@section('title', __('strings.backend.dashboard.title').' | '.app_name())

@push('after-styles')
    <style>
        .trend-badge-2 {
            top: -10px;
            left: -52px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            padding: 40px 40px 12px;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background-color: #ff5a00;
        }

        .progress {
            background-color: #b6b9bb;
            height: 2em;
            font-weight: bold;
            font-size: 0.8rem;
            text-align: center;
        }

        .best-course-pic {
            background-color: #333333;
            background-position: center;
            background-size: cover;
            height: 150px;
            width: 100%;
            background-repeat: no-repeat;
        }


    </style>
@endpush

@section('content')
    <div class="row">
      


                   

                    @if(auth()->user()->hasRole('administrator'))
                        
                        <div class="col-md-9 col-12 border-right">
                              <div class="card  bg-light  py-3">
                                <div class="card-body">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">Affiliates  <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.withdrawl')}}">Withdrawl Requests</a>
                                </h4>

                            </div>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <td>S.No.</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Code</td>
                                    <td>Total Earnings</td>
                                    <td>Balance</td>
                                    <td>Commision</td>
                                </tr>
                                </thead>
                                <tbody>
                                    @php $count=0; @endphp
                                    @foreach($affs as $a)
                                    @php $count++; @endphp
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td>{{$a->name}}</td>
                                        <td>{{$a->email}}</td>
                                        <td>{{$a->code}}</td>
                                        <td>₹{{$a->total_earnings}}</td>
                                        <td>₹{{$a->total_earnings - $a->total_withdrawl}}</td>
                                        <td>
                                            <form method="post" action="/user/save-affiliate">
                                                {{csrf_field()}}
                                                <input type="hidden" name="afid" value="{{$a->id}}" />
                                                <input type="number" class="form-control" required name="amount" value="{{$a->commision}}" />
                                                <br>
                                                <input type="submit" class="btn btn-sm btn-primary" value="Save" />
                                            </form>
                                        </td>

                                    </tr>

                                    @endforeach
                             
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </div>


                        <div class="col-md-3 col-12 border-right">
                              <div class="card  bg-light  py-3">
                                <div class="card-body">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">Commision(%)
                                   
                                </h4>

                            </div>
                            <form method="post" >
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label>User Discount</label>
                                    <input type="number" name="percent1" value="{{$config1->value}}" class="form-control">
                                </div>
                                <div class="form-group">
                                     <label>Affiliate Comission</label>
                                    <input type="number" name="percent" value="{{$config->value}}" class="form-control">
                                </div>
<div class="form-group text-center">
                                    <input type="submit" name="submit" value="Save Now" class="btn btn-primary btn-sm">
                                </div>
                                
                            </form>
                        </div>
                        </div>
                        </div>

                   
                    @endif
                </div>
            </div><!--card-body-->
        </div><!--card-->
    </div><!--col-->
@endsection
