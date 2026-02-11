<?php
use App\Models\Course;

?>
@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title','Course list'.' | '.app_name())
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title float-left mb-0">Assigned Subject list</h3>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable" class="table table-bordered table-striped dt-select ">
            <thead>
               <tr>
                  <th>@lang('labels.general.sr_no')</th>
                  <th>Subject Name</th>
                  <!-- <th>Category</th> -->
               </tr>
            </thead>
            <tbody>
               <?php $count=0; ?>
               @foreach($courses as $c)

               <?php $count++; ?>
               <tr data-entry-id="1" role="row" class="odd">
                  <td><?=$count?></td>
                  <td>
                     
                     <?php 

                     $cro = new Course();
                     echo $cro->getCouseNameWithCat($c->id);
                     ?>
                  </td>
                  <!-- <td>{{$c->category->name}}</td> -->
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