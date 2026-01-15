<?php
use App\Models\Batch;
?>
@extends('backend.layouts.app')

@section('title', __('Notifications').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Notifications</h3>
            <div class="float-right mb-2">
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