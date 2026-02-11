@extends('frontend.layout.sub-master')
@section('title')
<title>Invoices | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2">Training Content</h3>
              <h6 class="text-body fw-500 mb-3"></h6>
                <div>
                    <table class="table table-nowrap mb-0">
<thead>
<tr>
 <th>Sr_No.</th>
 <th>Title</th>
 <th>Action</th>


  </tr>
</thead>

@foreach ($student_list as $list)
<tr>
<td>{{$loop->index +1}}</td>
<td>{{$list->title}}</td>

<td>
  <a class="btn btn-success btn-sm" href="{{asset($list->file_path)}}" target="_blank">View</a>
</td>
</tr>
@endforeach
  




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