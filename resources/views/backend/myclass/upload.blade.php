@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Upload files to batch'.' | '.app_name())

@section('content')
<style type="text/css">
  form {
    display: inline-block;
  } 
</style>

<div class="card">
  <div class="card-header">
    <h3 class="page-title float-left mb-0">{{$batch->name}} hello Upload file(s)</h3>

  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <?php
         echo Form::open(array('route' => 'admin.myclass.uploadfile','files'=>'true'));
         echo 'Select the file to upload.';
         echo Form::file('file',array('class' => 'form-control' , 'accept' => 'application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
text/plain, application/pdf, image/*'));
        
      ?>
      </div>

      <div class="col-md-4">

        <?php
       echo Form::hidden('bid', $batch->id);
         echo Form::submit('Upload File',array('class' => 'btn btn-primary','style'=>'margin-top: 18px;'));
         echo Form::close();
      ?>
      </div>

    </div>

    <br>

    <hr>
    <div class="table-responsive">

      <table id="myTable" class="table table-bordered table-striped dt-select ">
        <thead>
          <tr>

            <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all" />
            </th>

            <th>@lang('labels.general.sr_no')</th>

            <th>File Name</th>
            <th>File type</th>
            <th>Uploaded at</th>

            <th>&nbsp; @lang('strings.backend.general.actions')</th>

          </tr>
        </thead>

        <tbody>
          <?php $count=0; ?>
          @foreach($files as $l)

          <?php $count++; ?>
          <tr data-entry-id="1" role="row" class="odd">
            <td class="text-center">
              <input type="checkbox" class="single" name="id[]" value="1">
            </td>

            <td>
              <?=$count?>
            </td>
            <td>{{$l->file_name}}</td>
            <td>{{$l->mime}}</td>
            <td>{{$l->created_at}}</td>

            <td>

              <a href="/{{$l->file_url}}" download class="btn btn-xs btn-primary mb-1"><i class="fa fa-download"
                  aria-hidden="true"></i>
              </a>

              <a href="/{{$l->file_url}}" target="_blank" class="btn btn-xs btn-primary mb-1">View</a>
              <?php 
              echo Form::open(array('route' => 'admin.myclass.rmfile','files'=>'false')); ?>


              <?php
       echo Form::hidden('bid', $l->bid);
       echo Form::hidden('fid', $l->id);
        ?>
              <button type="submit" class="btn btn-xs btn-danger mb-1"> <i class="icon-trash"></i></button>
              <?php
         echo Form::close();
      ?>






            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>



  </div>
</div>
@stop

@push('after-scripts')
<script>
  $('#myTable').DataTable();

</script>

@endpush