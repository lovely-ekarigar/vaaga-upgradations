@extends('frontend.layout.sub-master')
@section('title')
<title>Subjective Exam for {{$batch->name}} } | {{env('APP_NAME')}}</title>
@stop
@section('content')
<style type="text/css">
  .badge-success {
    color: #fff;
    background-color: #28a745;
}
</style>
    @include("frontend.include.user-menu")
        </div> 
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class=" mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Subjective Exam for {{$batch->name}} </h6>
                <div>
                    <table class="table table-nowrap mb-0">
                      <thead>
                        <tr>
                         
                          <th>S.No.</th>
                          <th>Exam Name</th>
                          <th>Exam Date</th>
                          <th>Marks</th>
                          <th>Questions</th>
                          <th>Upload</th>
                        </tr>
                      </thead>
                      <tbody>
                         @foreach($list as $k=>$b)
                          <tr>
                           
                            <td>{{$k+1}}</td>
                            <td>{{$b->title}}</td>
                            <td>{{date('d M Y',strtotime($b->exam_date))}}</td>

                            <td>

  @if($b->uploaded)
{{$b->data->marks}}
  @endif
                            </td>

                            <td>
                               @if(date("Y-m-d")==date("Y-m-d",strtotime($b->exam_date)))
                              <a href="/{{$b->file}}" download="">Download</a>
                              @elseif(date("Y-m-d") < date("Y-m-d",strtotime($b->exam_date)))

                              Expired
                              @else

                             Download will be start on {{date("d M Y",strtotime($b->exam_date))}}
                              @endif
                            </td>
                            <td>
                              @if($b->uploaded)
                              <span class="badge badge-success">Uploaded</span>
                              <a href="/{{$b->data->file}}" download="">
                                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z" fill="#1C274C"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14V13.5629C22 12.6901 22 12.0344 21.9574 11.5001H18L17.9051 11.5001C16.808 11.5002 15.8385 11.5003 15.0569 11.3952C14.2098 11.2813 13.3628 11.0198 12.6716 10.3285C11.9803 9.63726 11.7188 8.79028 11.6049 7.94316C11.4998 7.16164 11.4999 6.19207 11.5 5.09497L11.5092 2.26057C11.5095 2.17813 11.5166 2.09659 11.53 2.01666C11.1214 2 10.6358 2 10.0298 2C6.23869 2 4.34315 2 3.17157 3.17157C2 4.34315 2 6.22876 2 10V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22ZM7.98704 19.0472C8.27554 19.3176 8.72446 19.3176 9.01296 19.0472L11.013 17.1722C11.3151 16.8889 11.3305 16.4142 11.0472 16.112C10.7639 15.8099 10.2892 15.7945 9.98704 16.0778L9.25 16.7688L9.25 13.5C9.25 13.0858 8.91421 12.75 8.5 12.75C8.08579 12.75 7.75 13.0858 7.75 13.5V16.7688L7.01296 16.0778C6.71077 15.7945 6.23615 15.8099 5.95285 16.112C5.66955 16.4142 5.68486 16.8889 5.98704 17.1722L7.98704 19.0472Z" fill="#1C274C"/>
</svg>
                              </a>

                              @else

                             @if(date("Y-m-d")==date("Y-m-d",strtotime($b->exam_date)))
                              <a class="btn btn-sm btn-success" href="{{route('myclass.examuploads',['id'=>$b->id,'bid'=>$batch->id])}}">Upload Now</a>
                              

                              @elseif(date("Y-m-d") < date("Y-m-d",strtotime($b->exam_date)))
 
                              Expired
                              
                              @else
                            Upload will be start on {{date("d M Y",strtotime($b->exam_date))}}

                              @endif
                              @endif
                              
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