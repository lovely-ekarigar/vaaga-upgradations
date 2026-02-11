@extends('backend.layouts.app')
@section('title')
Teams - {{ env('APP_NAME') }}
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title d-inline">Teams</h3>
      <div class="float-right">
         <a href="{{ route('admin.team.create') }}" class="btn btn-success">Add new</a>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable"
            class="table table-bordered table-striped">
            <thead>
               <tr>
                  <th>Sr No</th>
                  <th>Name</th>
                  <th>Designation</th>
                  <th>Image</th>
                  <th>Action &nbsp;</th>
               </tr>
            </thead>
            <tbody>
                @php $count=0; @endphp
                @foreach($teams as $team)
                @php $count++ @endphp
                 <tr>
                    <td>{{$count}}</td>
                    <td>
                        {{$team->name}}
                    </td>
                    <td>{{$team->designation}}</td>
                    <td><img src="/{{$team->image}}" style="height:50px"></td>
                    <td>
                        <a href="{{ route('admin.team.edit',['id'=>$team->id]) }}" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                        
                        <a href="?del={{$team->id}}" onclick="return confirm('DO you want to delete?')" title="Delete {{$team->name}}" class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;" "> <i class="fa fa-trash"></i> </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
         </table>
      </div>
      
      
          <div class="d-flex justify-content-end">
    {{ $teams->links() }}
</div>
   </div>
</div>
@endsection