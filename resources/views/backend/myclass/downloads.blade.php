@extends('frontend.layout.sub-master')
@section('title')
<title>Downloads for {{$batch->name}} | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class="border-bottom mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Downloads for {{$batch->name}}</h6>
                <div>
                    <table class="table table-nowrap mb-0">

                    <thead>
                    <tr>

                   

                        <th>@lang('labels.general.sr_no')</th>

                        <th>Uploaded By</th>
                        <th>File Name</th>
                        <th>Upload Date</th>
                        <th>Action</th>
                        
                       
                    </tr>
                    </thead>

                   <tbody>
                       <?php $count=0; ?>
                       @foreach($downloads as $st)

                       <tr>
                         <td>
                           <?php $count++;echo $count; ?>
                         </td>
                         <td>
                          @if($st['teacher'])
                          {{$st['teacher']->first_name}} {{$st['teacher']->last_name}}
                          @endif
                        </td>
                         <td>{{$st->file_name}}</td>
                         <td><?php echo date("Y-m-d",strtotime($st['created_at']));?></td>
                         <td><a href="/{{$st->file_url}}" download="" class="btn btn-primary"><i class="bi bi-cloud-arrow-down"></i></a></td>
                         
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