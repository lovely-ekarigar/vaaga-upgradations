@extends('backend.layouts.app')

@section('title', __('Teacher fees').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">{{$teacher_name->first_name}} {{$teacher_name->last_name}} Fees</h3>
            <div class="float-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal">Add Fees</button>

            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>@lang('labels.general.sr_no')</th>
                                <th>Course</th>
                                <th>Course Fees</th>
                                <th>Action</th>
                              
                            </tr>
                            </thead>
                            @php
                            $sl = 1;
                            @endphp
                            @foreach($teacher_fees_list as $teacher_fees)
                            @if($teacher_fees->course)
                            <tr>
                               <td>{{$sl++}}</td>
                               <td>{{$teacher_fees->course->title}}</td>
                               <td>{{$teacher_fees->fee}}</td>
                               <td>
                                 <!-- <a href="" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a> -->
                                 <a href="{{route('admin.teacher_fees_delete',['id'=>$teacher_fees->id])}}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-xs btn-danger text-white mb-1"><i class="fa fa-trash"></i></a>
                               </td>
                            </tr>
                            @endif
                            @endforeach
                                
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{$teacher_name->first_name}} {{$teacher_name->last_name}} Fees Add</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="">
               @csrf
            <div class="modal-body">
               <div class="row ">
                  <div class="col-12 col-lg-6 form-group">
                     <label for="title" class="control-label">Select Course<span class="required">*</span></label>
                     <select required id="select2insidemodal" class="form-select form-select select2" name="course_id">
                        <option value=" " selected disabled>Select Course...</option>
                        @foreach($course_user_list as $c)
                        @if(!in_array($c->course_id,$course_list))
                        @if($c->course)
                        <option value="{{$c->course_id}}">{{$c->course->title}}</option>
                        @endif
                        @endif
                        @endforeach
                     </select>
                     
                  </div>
                  <div class="col-12 col-lg-6 form-group">
                     <label for="title" class="control-label">Fees<span class="required">*</span> <span style="font-size: 10px;color: #999;">(Course Fees)</span></label>
                     <input class="form-control" type="number" required name="fee">
                  </div>
                  <input type="hidden" name="teacher_id" value="{{$teacher_name->id}}">
               </div>
            </div>
            <div class="modal-footer" style="text-align: end;">
               <button type="submit" class="btn btn-info">Submit</button>
            </div>
            </form>
          </div>
        </div>
      </div>

@stop

@push('after-scripts')
   <script type="text/javascript">
      $('#myTable').DataTable();

      $("#select2insidemodal").select2({
    dropdownParent: $("#exampleModal")
  });
   </script>
@endpush