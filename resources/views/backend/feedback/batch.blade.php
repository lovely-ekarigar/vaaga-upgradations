@extends('backend.layouts.app')

@section('title', __('Feedback').' | '.app_name())

@section('content')

<style type="text/css">
    .card.active {
    background: #1c1c1ca6;
    color: #fff;
}
</style>
    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">{{$batch->name}}  Feedbacks</h3>
            

<div class="float-right">
    <select class="form-control form-select" id="userType" name="type" style="width: 130px;
    display: inline-block;
    margin-left: 20px;">

    <option value="student" @if($userType == 'student') selected @endif>Student</option>
    <option value="teacher" @if($userType == 'teacher') selected @endif>Teacher</option>
    
</select>
                <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Feedback Email</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">

                @if($userType=='student')

                @foreach($sqlist as $q)
                <div class="col-md-4 col-12">
                            <a href="/user/batch/batch-feedback/{{$id}}?type={{$userType}}&qid={{$q->id}}" style="text-decoration: none;">
                            <div class="card text-center py-1 @if($q->id==request('qid')) active  @endif">
                                <div class="card-body">
                                    <h1 class="">{{number_format($q->avg_rating,1)}}</h1>
                                    <p>{{$q->question}}(Avg. Ratings) - #QRF{{$q->id}} </p>
                                </div>
                            </div>
                            </a>
                        </div>

                @endforeach

                


                @endif

                    @if($userType=='teacher')

                @foreach($tqlist as $q)
                <div class="col-md-4 col-12">
                            <a href="/user/batch/batch-feedback/{{$id}}?type={{$userType}}&qid={{$q->id}}" style="text-decoration: none;">
                            <div class="card text-center py-1 @if($q->id==request('qid')) active  @endif">
                                <div class="card-body">
                                    <h1 class="">{{number_format($q->avg_rating,1)}}</h1>
                                    <p>{{$q->question}}(Avg. Ratings) - #QRF{{$q->id}}</p>
                                </div>
                            </div>
                            </a>
                        </div>

                @endforeach




                @endif


                @if(count($feedbacks)>0)


<div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable"
                               class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Rating</th>
                               
                                <th>Message</th>
                            </tr>
                            </thead>
                           
                            <tbody>

                                @foreach($feedbacks as $k=>$fb)

                                <tr>
                                    
                                    <td>{{$k+1}}</td>
                                    <td>{{$fb->user->name}}</td>
                                    <td>{{date('d M Y', strtotime($fb->created_at))}}</td>
                                    <td>{{$fb->review}}</td>
                                    <td>{{$fb->message}}</td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>

                @endif


                
            </div>
        </div>
    </div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Feedback Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="post">
        @csrf
      <div class="modal-body">
        <p>When you click on the feedback button, a prompt will appear asking you to provide your feedback. Once you have submitted your feedback, an email will be automatically generated and sent. This email will contain the details of your feedback, such as your comments or suggestions, as well as any relevant information that you provided, such as your contact details. This process ensures that your feedback is received and properly recorded by the recipient, allowing them to review and address your concerns or suggestions effectively. Sending an email after clicking the feedback button helps to streamline the communication process and ensures that your feedback reaches the intended recipient in a timely manner.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Trigger Email</button>
      </div>
</form>

    </div>
  </div>
</div>

@stop

@push('after-scripts')
    <script>
        $(document).ready(function () {
         
            $('#myTable').DataTable({
               
                iDisplayLength: 10,
                                
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [1, 2, 3, 5]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 5]
                        }
                    },
                    'colvis'
                ],
                

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons: {
                        colvis: '{{trans("datatable.colvis")}}',
                        pdf: '{{trans("datatable.pdf")}}',
                        csv: '{{trans("datatable.csv")}}',
                    }
                }
            });
           

$(document).on("change","#userType",function(){

var type = $(this).val();

window.location.href = "?type="+type;


});
});

    </script>
@endpush