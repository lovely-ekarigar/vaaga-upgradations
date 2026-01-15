@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','User lists'.' | '.app_name())

@section('content')

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">User lists </h3>
            @can('course_create')
                <!--<div class="float-right">-->
                <!--    <a href="" class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>-->

                <!--</div>-->
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
        
                <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>

                     

                        <th>@lang('labels.general.sr_no')</th>

                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      
                            <!--<th>&nbsp; @lang('strings.backend.general.actions')</th>-->
                       
                    </tr>
                    </thead>

                   <tbody>
                        <?php $count=0; ?>
			@foreach($assusers as $au)
			
			@if($au->user)
			<?php $count++ ?> 
                      
   <tr data-entry-id="1" role="row" class="odd">
      	 								
      <td>{{$count}}</td>
      <td>
      @if($au->user_id)
	{{$au->user->first_name}}
	@else
	@endif    
      </td>
      <td>
          @if(($au->is_taken)==1)
   Taken
          @else
          <p>Not taken</p>
          @endif
          
      </td>
      
      <td>
          @if(($au->is_taken)==1)
                <a href="user/assessment/users/list/{{$au->id}}/responses" class="btn btn-xs btn-primary mb-1">Check Responses</a>
                @endif
          
      </td>
      <!--<td>-->

      <!--   <a href="" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>-->

      <!--   <a href="" onclick="return confirm('Do you really want to delete this question')" class="btn btn-xs btn-danger mb-1"><i class="fa fa-trash"></i></a>-->
      <!--</td>-->
     
   </tr>
   @endif
@endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>
     <div class="modal fade" id="lessionModal" tabindex="-1" role="dialog" aria-labelledby="lessionTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lessionTitle">Notify Batch Students</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>Enter Title</label>
                    <input type="text" id="ntitle" name="title" class="form-control" />
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label>Enter Message</label>
                    <input type="text" id="nmsg" name="msg" class="form-control" />
                </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary notify-now">Notify</button>
      </div>
    </div>
  </div>
</div>

@stop

