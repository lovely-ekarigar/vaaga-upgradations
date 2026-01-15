@extends('aff.layout')
@section('content')
<div class="row">
  <div class="col-xl-6 col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Bank Details</h4>
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
                                <label class="col-md-3 label-control" for="projectinput1">Bank Name</label>
                                <div class="col-md-9 mx-auto">
                                  <input type="text" value="{{$aff->bank_name}}" id="projectinput1" class="form-control" placeholder="Bank Name" name="bank_name" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control" for="projectinput2">Account Number</label>
                  <div class="col-md-9 mx-auto">
                                  <input type="number" value="{{$aff->bank_account}}"  id="projectinput2" class="form-control" placeholder="Account Number" name="bank_account">
                                </div>
                            </div>
  <div class="form-group row">
                                <label class="col-md-3 label-control" for="projectinput2">Bank IFSC</label>
                  <div class="col-md-9 mx-auto">
                                  <input type="text" value="{{$aff->bank_ifsc}}"   id="projectinput2" class="form-control" placeholder="Bank IFSC" name="bank_ifsc">
                                </div>
                            </div>

                             <div class="form-group row">
                                <label class="col-md-3 label-control" for="projectinput2">Account Holder Name</label>
                  <div class="col-md-9 mx-auto">
                                  <input type="text" value="{{$aff->bank_bef_name}}"   id="projectinput2" class="form-control" placeholder="Account Holder Name" name="bank_bef_name">
                                </div>
                            </div>
                           
              </div>

                          <div class="form-actions" style="text-align: right;">
                              
                              <button type="submit" class="btn btn-primary">
                                  <i class="la la-check-square-o"></i> Update
                              </button>
                          </div>
                        
                      </form>
         
        </div>
      </div>
    </div>
  </div>
  
</div>

@stop


   