
@extends('frontend.layout.sub-master')
@section('title')
<title>Certificates| {{env('APP_NAME')}}</title>
@stop
@section('content')
<style>
    .demo_box {
    background: #ffeb3b24;
    padding: 0px 8px;
    border-radius: 8px;
}
button.close {
    background: transparent;
    border: 0px;
}
</style>

          @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
                 @include('includes.partials.messages')

              <h3 class="mb-4">My Certificates</h3>
                
                     
                <div class="demo_box"> 
                <h6 class="text-body fw-500 mb-3 pt-3 text-primary"></h6>
                    <table class="table table-nowrap mb-0">
                        <thead>
                            <tr>
                                 <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.certificates.fields.course_name')</th>
                                <th>@lang('labels.backend.certificates.fields.progress')</th>
                                <th>@lang('labels.backend.certificates.fields.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                             @if(count($certificates) > 0)
                            @foreach($certificates as $key=>$certificate)
                                    @php $key++; @endphp
<tr>
<td>{{$key}}</td>
                                        <td>{{$certificate->name}}</td>
                                        <td>100%</td>

<td>
	
	<a class="btn btn-success btn-sm" href="{{asset('storage/certificates/'.$certificate->url)}}" target="_blank">@lang('labels.backend.certificates.view')</a>
	<a class="btn btn-primary btn-sm" href="{{route('admin.certificates.download',['certificate_id'=>$certificate->id])}}" target="_blank">@lang('labels.backend.certificates.download')</a>

</td>
  
</tr>
  @endforeach
   @else

<tbody>
	<tr>
		<td>No data found</td>
	</tr>
</tbody>
  @endif
                        </tbody>
                    </table>
                </div>
            
             
             

                
              

              
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop