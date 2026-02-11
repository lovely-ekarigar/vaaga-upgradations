@extends('aff.layout')
@section('content')
<div class="row">
  <div class="col-xl-8 col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Total Withdrawl: <strong>₹{{$aff->total_withdrawl}}</strong></h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-content collapse show">

        <div class="card-body pt-0">
           
          <table class="table table-stripped table-bordered">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Request Amount</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @php $count=0; @endphp
              @foreach($requests as $er)
               @php $count++; @endphp
              <tr>
                <td>{{$count}}</td>
                <td>₹{{$er->amount}}</td>
                <td>
                  @if($er->status=='0')
                    <label class="badge badge-danger">Pending</label>
                  @else
<label class="badge badge-success">Successful</label>
                  @endif
                </td>
                <td>{{$er->created_at}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
         
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-4 col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Request for Withdrawl</strong></h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-content collapse show">

        <div class="card-body pt-0">
        @if(Session::has('flash_message'))    
              <div class="alert {{ Session::get('alert-class', 'alert-info') }} mb-2" role="alert">
              {!! Session::get('flash_message') !!}
            </div>   
                @endif
          <form class="form form-horizontal" method="post">
            {{csrf_field()}}
                        <div class="form-body">
                          
                          <div class="form-group row">
                               
                                <div class="col-md-9 mx-auto">
                                  <input type="number" value="" id="projectinput1" class="form-control" placeholder="Withdrawl Amount" name="amount" >
                                </div>
                            </div>
                          

                           
              </div>

                          <div class="form-actions" style="text-align: right;">
                              
                              <button type="submit" class="btn btn-primary">
                                  Make Request
                              </button>
                          </div>
                        
                      </form>
         
        </div>
      </div>
    </div>
  </div>

</div>

@stop


   