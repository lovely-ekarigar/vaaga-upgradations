@extends('backend.layouts.app')
@section('title')
Notes - {{ env('APP_NAME') }}
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="page-title d-inline">Notes</h3>
      <div class="float-right">
         <a href="{{ route('admin.note.create') }}" class="btn btn-success">Add Notes</a>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="myTable"
            class="table table-bordered table-striped">
            <thead>
               <tr>
                  <th>Sr No</th>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Image</th>
                  <th>File</th>
                  <th>Action </th>
               </tr>
            </thead>
            <tbody>
               @php $count=0; @endphp
                @foreach($notes as $note)
                @php $count++ @endphp
                 <tr>
                    <td>{{$count}}</td>
                    <td> {{$note->name}}</td>
                    <td>
                        @if($note->category)
                        {{$note->category->name}}
                        @endif
                    </td>
                    <td><img src="/{{$note->image}}" style="height:50px"></td>
                    <td>
                          <a href="/{{$note->file}}" target="_blank" class="btn btn-xs btn-info mb-1">Download</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.note.edit', $note->id) }}" class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>
                        
                        <a href="?del={{$note->id}}" onclick="return confirm('Do you want to delete?')" title="Delete " class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;" "> <i class="fa fa-trash"></i> </a>
                    </td>
                </tr>
        @endforeach
            </tbody>
         </table>
      </div>
      
      
          <div class="d-flex justify-content-end">
 {{ $notes->links() }}
</div>
   </div>
</div>
@endsection