@extends('frontend.layout.sub-master')
@section('title')
<title>Class History for {{$batch->name}} y | {{env('APP_NAME')}}</title>
@stop
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css"  />
    @include("frontend.include.user-menu")
     </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Class History for {{$batch->name}} </h6>
                <div>
                    <table class="table table-nowrap mb-0">
                      <thead>
                        <tr>
                         
                          <th>Lesson</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
   <tbody>
            @foreach($list as $rec)

<tr>
    <td></td>
    <td>{{date("d M Y",strtotime($rec->updated_at))}}</td>
    <td>
          <a href="https://asia-eu-2.meeting-recordings.com/playback/presentation/2.3/{{$rec->internal_id}}" target="_blank" class="btn btn-info btn-primary btn-sm">
     Watch Recording 
    
            </a>
    </td>
           
        </tr>
            @endforeach
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

@section('page_js')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(".table").DataTable();
</script>

@stop