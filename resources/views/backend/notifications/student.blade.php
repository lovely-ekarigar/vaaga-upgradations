
@extends('frontend.layout.sub-master')
@section('title')
<title>Notifications| {{env('APP_NAME')}}</title>
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
            <div class=" mb-6 pb-6">
                 @include('includes.partials.messages')

              <h3 class="mb-2">Notifications</h3>
               
       @if(count($notifications)>0)      
              
  <div>
                    <table class="table table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $count=0 @endphp
                        @foreach($notifications as $item)
                            @php $count++ @endphp
                            <tr>
                                <td>{{$count}}</td>
                                @if($item->notification)
                                <td>{{$item->notification->title}}</td>
                                <td>{{date("d M Y",strtotime($item->created_at))}}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm viewm" data-title="{{$item->notification->title}}" data-msg="{{$item->notification->message}}" href="javascript:void(0)">View</a>
                                   
                                    
                                </td>
                                @else
<td>{{$item->title}}</td>
                                <td>{{date("d M Y",strtotime($item->created_at))}}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm viewm" data-title="{{$item->title}}" data-msg="{{$item->message}}" href="javascript:void(0)">View</a>
                                   
                                    
                                </td>
                                @endif
                                
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                @else

<div>
    <center>No notifications found.</center>
</div>

                @endif
                
              

              
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      
      </div>
    </div>
  </div>
</div>
@stop


@section('page_js')

<script type="text/javascript">
     $(document).on("click",".viewm",function(){
            $(".modal-title").html($(this).data("title"));
            $(".modal-body").html($(this).data("msg"));
            $("#exampleModalCenter").modal('show');

          })

$(document).on("click","button[data-dismiss]",function(){
$("#exampleModalCenter").modal('hide');

})

</script>
@stop