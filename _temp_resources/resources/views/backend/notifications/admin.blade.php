<?php
use App\Models\Batch;
?>
@extends('backend.layouts.app')

@section('title', __('Notification History').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Notification History</h3>
            <div class="float-right mb-2">
   <a href="{{route('admin.create_notification')}}" class="btn btn-sm btn-primary">Create Notification</a>
</div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">




                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                              
                                <th>Title</th>
                                <th>Batch Type</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                         

                            <tbody>

                                @foreach($notifications as $k=>$t)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$t->title}}</td>
                                    <td>{{ucwords($t->batch_type) }}</td>
                                    <td>
                                        @if($t->batch_type=='selected')
                                            @if($t->batch_list)
                                        <?php 

                                        $tbl = json_decode($t->batch_list,true);
                                        foreach($tbl as $b){
                                            $batch = Batch::find($b);
                                            if($batch){
                                                echo $batch->name.", ";
                                            }

                                        }
                                        ?>

                                        @endif
                                        @endif
                                    </td>
                                    <td>{{date('d M Y',strtotime($t->created_at))}}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-title="{{$t->title}}" data-msg="{{$t->message}}"  class="btn btn-sm btn-primary viewm mb-2">View</a>
                                  
                                        <form method="post" action="{{route('admin.delete_notification')}}" onsubmit="return confirm('Do you want to delete this notification? You will not able to recover after delete.')">
                                            @csrf
                                            <input type="hidden" name="nid" value="{{$t->id}}">
                                                    <input type="submit" name="submit" value="Delete"  class="btn btn-sm btn-danger mb-2">

                                        </form>
                                       
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      
      </div>
    </div>
  </div>
</div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {

          $(document).on("change","#studentType",function(){

var type = $(this).val();

window.location.href = "?type="+type;


});

          $(document).on("click",".viewm",function(){
            $(".modal-title").html($(this).data("title"));
            $(".modal-body").html($(this).data("msg"));
            $("#exampleModalCenter").modal('show');

          })
           

            $('#myTable').DataTable({
                processing: false,
                serverSide: false,
                iDisplayLength: 10,
                retrieve: false,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    'colvis'
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });

        });

    </script>
@endpush