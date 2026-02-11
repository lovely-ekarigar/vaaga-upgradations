@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')

@section('title', __('labels.backend.blogs.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Blog Categories</h3>
            @can('blog_create')
                <div class="float-right">
                    <a href="{{ route('admin.blogs-cat.add') }}" class="btn btn-success">Add Category</a>
                </div>
            @endcan
        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table id="myTable"
                       class="table table-bordered table-striped">
                    <thead>
                    <tr>
                       <th>S.No.</th>
                       <th>Name</th>
                       <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $k=>$cat)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$cat->name}}</td>
                            <td>
                                <a href="{{route('admin.blogs-cat.edit',['id'=>$cat->id])}}" class="btn btn-sm btn-primary">Edit</a>
                                <a href="?del={{$cat->id}}" onclick="return confirm('Do you want to delete?')" class="btn btn-sm btn-danger">Delete</a>
                                
                            </td>
                        </tr>
                        @endforeach
                        
                        
                    </tbody>
                </table>

            </div>

        </div>
    </div>

@endsection

@push('after-scripts')
    <script>

        $(document).ready(function () {
          

            $('#myTable').DataTable();
});
    </script>
@endpush