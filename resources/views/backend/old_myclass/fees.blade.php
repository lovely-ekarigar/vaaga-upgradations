
<?php
use App\Models\Batch;
?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Batch Fees'.' | '.app_name())

@section('content')

 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Tutor Fees for {{$batch->name}}</h3>

            <div class="float-right">
                <a href="/user/batches" class="btn btn-sm btn-primary">Batch List</a>
            </div>
          
        </div>
        <div class="card-body">
            @if($tb)
            <form method="POST">
                @csrf
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="fees">Per Hour Fees*</label>
                  <input type="number" required name="fees" value="@if($tb){{$tb->fees}}@endif" id="fees" class="form-control" min="0">
                </div>
               <div class="col-md-3 mb-3">
                     <label for="fees_schedule">Total Hours*</label>
                  <input type="number" required name="fees_schedule" value="@if($tb){{$tb->fees_schedule}}@endif" id="fees_schedule" class="form-control" min="1">

                </div> 
                <div class="col-md-3 mb-3">
                    <input type="hidden" name="tb_id" value="{{$tb->id}}">
                    <br>
                    <input type="submit" name="submit" class="btn  btn-primary" value="Update Fees">
                </div>

             <!--    <div class="col-12">
                    <p>
                        Min Completion(%) for Payment must be Factor of 100.
                    </p>
                </div> -->
                
            </div>
</form>

@else


<center>
    <h4>Kindly Assign Tutor to update fees</h4>
</center>

@endif




        </div>
    </div>
@stop

@push('after-scripts')
    <script>


    </script>

@endpush