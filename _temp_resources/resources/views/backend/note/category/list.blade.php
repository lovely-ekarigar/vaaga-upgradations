@extends('backend.layouts.app')
@section('title')
Categories - {{ env('APP_NAME') }}
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title d-inline">Categories</h3>
      <div class="float-right">
         <a href="{{ route('admin.note.category.create') }}" class="btn btn-success">Add new</a>
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
                  <th>Slug</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Action </th>
               </tr>
            </thead>
            <tbody>
                 @php $count=0; @endphp
                @foreach($categories as $category)
                @php $count++ @endphp
                 <tr>
                    <td>{{$count}}</td>
                    <td>{{$category->name}} </td>
                    <td>{{$category->slug}} </td>
                    <td><img src="/{{$category->image}}" style="height:50px"></td>
                    <td>
                        @if($category->status == '0')
                        Pending
                        @else
                        Active
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.note.category.edit',['id'=>$category->id]) }}" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                        
                        <a href="?del={{$category->id}}" onclick="return confirm('Do you want to delete?')" title="Delete " class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;" "> <i class="fa fa-trash"></i> </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
         </table>
      </div>
      
      
          <div class="d-flex justify-content-end">
    {{ $categories->links() }}
</div>
   </div>
</div>
@endsection