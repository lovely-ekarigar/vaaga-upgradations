@extends('backend.layouts.app')

@section('title', __('Create Notification').' | '.app_name())

@section('content')

<style type="text/css">
    .batch_list_div{
        display: none;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Create Notification</h3>
            <div class="float-right mb-2">
   <a href="{{route('admin.notifications')}}" class="btn btn-sm btn-primary">All Notifications</a>
</div>

        </div>
        <div class="card-body">
            <form method="post">
                @csrf
            <div class="row">
                <div class="col-12 mb-3">
                        <label for="title">Notification Title*</label>
                    <input type="text" name="title" required id="title" class="form-control">

                </div>
                <div class="col-6 mb-3">
                        <label for="batch_type">Batch Type*</label>
                   <select id="batch_type" name="batch_type" required class="form-control form-select select2">
                     <option value="">_________</option>
                    <option value="active">Active Batch</option>
                    <option value="completed">Completed Batch</option>
                    <option value="all">All Batch</option>
                    <option value="selected">Selected Batch</option>
                       
                   </select>

                </div>

                 <div class="col-6 mb-3">
                        <label for="user_type">User Type*</label>
                   <select id="user_type" required name="user_type" class="form-control form-select select2">
                    <option value="">_________</option>
                    <option value="student">Only Student</option>
                    <option value="tutor">Only Tutor</option>
                    <option value="both">Student & Tutor both</option>
                       
                   </select>

                </div>


                <div class="col-12 mb-3 batch_list_div">
                        <label for="batch_list">Select Batch*</label>
                    
                <select id="batch_list"  name="batch_list[]" class="form-control form-select select2" multiple>
                    @foreach($batches as $batch)
                    <option value="{{$batch->id}}">{{$batch->name}}</option>

                    @endforeach
                       
                   </select>
                </div>

                <div class="col-12 mb-3">
                        <label for="title">Notification Message*</label>
                    <textarea class="form-control" name="message" id="summernote"></textarea>

                </div>

                 <div class="col-12 mb-3 text-center">
                    <input type="submit" name="submit" value="Create Now" class="btn btn-primary">
                 </div>

            </div>

            </form>
        </div>
    </div>

@stop

@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>

        $(document).ready(function () {

     
             $(document).ready(function() {
  $('#summernote').summernote({height: 250});
});

             $(document).on('change','#batch_type',function(){

                var bt = $(this).val();
                if(bt=='selected'){
                    $('.batch_list_div').show();
                }else{
                     $('.batch_list_div').hide();
                }
             })

         

        });

    </script>
@endpush