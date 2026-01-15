@extends('backend.layouts.app')

@section('title', 'Demo Requests | '.app_name())




@section('content')
<style>
.loader {
     border: 7px solid #f3f3f3;
    border-radius: 50%;
    border-top: 7px solid #3498db;
    width: 21px;
    height: 20px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> 
    <div class="card">
        <div class="card-header">

                <h3 class="page-title d-inline">Demo Requests</h3>


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
                                <th>@lang('labels.backend.contacts.fields.name')</th>
                                @if(auth()->user()->hasRole('administrator'))
                                <th>@lang('labels.backend.contacts.fields.email')</th>
                                <th>@lang('labels.backend.contacts.fields.phone')</th>
                               @endif
                                <th>Course</th>
                                <th>
                                    @if(auth()->user()->hasRole('administrator'))
                                    Date
                                    @else
                                        Demo Date
                                    @endif
                                    </th>
                                @if(auth()->user()->hasRole('teacher'))
                                <th>Instructions</th>
                               @endif
                                @if(auth()->user()->hasRole('administrator'))
                                <th>Tutor Remarks</th>
                               @endif
                                <th>Status</th>
                               
                                <th>Action</th>
                            </tr>
                            </thead>
                           

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@if(auth()->user()->hasRole('administrator'))

<div class="modal fade" id="demoModal"  role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="demoModalLabel">Schedule Demo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post">
      <div class="modal-body">
        
            @csrf
        <div class="row ">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Select Tutor<span class="required">*</span></label>
                          
                            <select required class="form-control form-select select2" name="teacher">
                                <option value="">Select Tutor</option>
                                @foreach($users as $t)
                                <option value="{{$t->id}}">{{$t->first_name}} {{$t->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                        
                         <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Date & Time<span class="required">*</span></label>
                            <input class="form-control" required placeholder="Date & Time" min="{{date('Y-m-d')}}T00:00" name="datetime" type="datetime-local">

                        </div>
                        
                         <div class="col-12 col-lg-12 form-group">
                            <label for="title" class="control-label">Instructions for Tutor</label>
                            <input class="form-control" placeholder="Instructions" name="instruction" type="text">
                            <input type="hidden" name="demo_id" id="demo_id" />
    <br>
    <span class="required">*</span> Indicates required fields
                        </div>
                        <div  class="col-12 col-lg-12 form-group">
                            <a href="{{route('admin.teachers_course_availability')}}" target="_blank"  class="btn btn-info btn-sm">Check Tutor availability</a>
                        </div>
                        
                        </div>
    
      </div>
      <div class="modal-footer">
       <input type="submit" value="Schedule Now" class="btn btn-primary" />
      </div>
          </form>
    </div>
  </div>
</div>
@endif



<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="demoModalLabel">Change Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/user/demo-requests/status-update">
      <div class="modal-body">
        
            @csrf
        <div class="row ">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title" class="control-label">Select Status<span class="required">*</span></label>
                          
                            <select required class="form-control form-select select2" name="status">
                                <option value="completed">Completed</option>
                                 <option value="pending">Pending</option>
                                  <option value="studentnotjoined">Student Not Joined</option>
                                  
                               
                            </select>

                        </div>
                        
                       
                        
                         <div class="col-12 col-lg-12 form-group">
                            <label for="title" class="control-label">Remarks<span class="required">*</span></label>
                            <input class="form-control" placeholder="Remarks" name="teacher_remarks" type="text">
                            <input type="hidden" name="demo_id" id="demo_id_status" />
                            <input type="hidden" name="demo_type" id="demo_id_type" />
    <br>
    <span class="required">*</span> Indicates required fields
                        </div>
                        
                        </div>
    
      </div>
      <div class="modal-footer">
       <input type="submit" value="Update Now" class="btn btn-primary" />
      </div>
          </form>
    </div>
  </div>
</div>


<a id="anchorID" href="" target="_blank"></a>
@stop


@push('after-scripts')
<script>
    
    $('.select2x').select2({
    dropdownParent: $('#demoModal')
  });

    $(document).on("click",".demo-init",function(){
        $("#demo_id").val($(this).data("id"))
        $("#demoModal").modal('show')
    })
      $(document).on("click",".changeStatus",function(){
        $("#demo_id_status").val($(this).data("id"))
          $("#demo_id_type").val($(this).data("type"))
        $("#changeStatusModal").modal('show')
    })
    
    
    
      
    
     $(document).on("click",".demo-start",function(){
        $(this).attr("disabled",true);
        $(this).html('<div class="loader"></div>'); 
var demo_type = $(this).data("type");

var route='<?php echo route('admin.myclass.demoflaunch'); ?>'; 
var demo_id = $(this).data("id");
$.ajax({
  url:route,
  data:{demo_id:demo_id,'_token':$('meta[name="csrf-token"]').attr('content'),type:demo_type},
  type:'POST',
  success:function(data){
    console.log(data);
    if(data.success==true){
       
      //window.open(data.url,"_blank");
    //   $("#anchorID").attr("href","<?=URL::to('/')?>/runclass?sid="+btoa(data.url)); 
     window.location.href="<?=URL::to('/')?>/runclass?sid="+btoa(data.url);  
// document.getElementById("anchorID").click();
$(this).html('Start Demo');
    }else{
      $(this).html('Start Demo');
      alert(data.msg);
      $(this).attr("disabled",false); 
    }

  }
})

      });
      
      
</script>
@if(auth()->user()->hasRole('administrator'))
 

    <script>

        $(document).ready(function () {
            var route = '{{route('admin.demo_requests.get_data')}}';

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
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
                ajax: route,
                columns: [

                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "name", name: 'name'},
                    {data: "email", name: 'email'},
                    {data: "phone", name: 'phone'},
                    {data: "course", name: "course"},
                    {data: "created_at", name: "time"},
                     {data: "remarks", name: "remarks"},
                    {data: "demo_status", name: "demo_status"},
                    {data: "action", name: "action"},
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"width": "15%", "targets": 5},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
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
        
        
    $(document).on("click",".joinDemo",function(){
        $(this).attr("disabled",true);
        $(this).html('<div class="loader"></div>'); 
var demo_id = $(this).data("id");
var demo_type = $(this).data("type");

var mid = $(this).data("mid");
var flag = $(this).data("flag");
var route='';

console.log(demo_type);
// if(!flag)
// {
//     route='/user/getLaunch/'+demo_id+'/'+mid; 
// } else {
//     route='javascript:void(0)'; 
// }

// var route='/user/getLaunch/'+demo_id+'/'+mid; 
  window.location.href = route;
// $.ajax({
//   url:route,
//   data:{demo_id:demo_id,'_token':$('meta[name="csrf-token"]').attr('content')},
//   type:'GET',
//   success:function(data){
//     console.log(data);
//     if(data.success==true){
       
//       //window.open(data.url,"_blank");
//     //   $("#anchorID").attr("href","<?=URL::to('/')?>/runclass?sid="+btoa(data.url)); 
//      window.location.href="<?=URL::to('/')?>/runclass?sid="+btoa(data.url);  
// // document.getElementById("anchorID").click();
// $(this).html('Join Demo');
//     }else{
//       $(this).html('Join Demo');
//       alert(data.msg);
//       $(this).attr("disabled",false); 
//     }

//   }
// })

      });
      

    </script>

    @endif

     @if(auth()->user()->hasRole('teacher'))
 
    <script type="text/javascript">
        var route = '{{route('admin.demo_requests.get_data_teacher')}}';

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
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
                ajax: route,
                columns: [

                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "name", name: 'name'},
                    // {data: "email", name: 'email'},
                    // {data: "phone", name: 'phone'},
                    {data: "course", name: "course"},
                    {data: "created_at", name: "time"},
                     {data: "instructions", name: "instructions"},
                    {data: "demo_status", name: "demo_status"},
                    {data: "action", name: "action"},
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"width": "15%", "targets": 5},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });
    </script>

    @endif

@endpush
