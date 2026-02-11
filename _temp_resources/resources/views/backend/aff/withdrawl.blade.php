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
                        
                        <div class="col-md-12 col-12 border-right">
                              <div class="card  bg-light  py-3">
                                <div class="card-body">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">Withdrawl Request  <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.affiliate')}}">Affiliates</a>
                                </h4>

                            </div>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <td>S.No.</td>
                                    <td>Name</td>
                                    <td>Bank Info</td>
                                    <td>Amount Requested</td>
                                    
                                    <td>Balance</td>
                                    <td>Action</td>
                                </tr>
                                </thead>
                                <tbody>
                                    @php $count=0; @endphp
                                    @foreach($requests as $a)
                                    @php $count++; @endphp
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td>{{$a->user->name}}</td>
                                        <td>Bank Name: {{$a->user->bank_name}}
                                            <br>
                                            A/C: {{$a->user->bank_account}}
                                            <br>
                                            IFSC: {{$a->user->bank_ifsc}}
                                            
                                        </td>
                                        <td>{{$appCurrency['symbol']}} {{number_format($a->amount,2)}}</td>
                                     
                                        <td>{{$appCurrency['symbol']}} {{number_format($a->user->total_earnings - $a->user->total_withdrawl,2)}}</td>
                                           <td>
                                            <form method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" name="wid" value="{{$a->id}}">
                                                <button class="btn btn-sm btn-primary" type="submit">Approve</button>
                                            </form>

                                            

                                        </td>

                                    </tr>

                                    @endforeach
                             
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </div>


                     

                   
                    @endif
                </div>
            </div><!--card-body-->
        </div><!--card-->
    </div><!--col-->
@endsection
