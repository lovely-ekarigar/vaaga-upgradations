@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')

@section('title', __('labels.backend.blogs.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Add Blog Categories</h3>
            @can('blog_create')
                <div class="float-right">
                    <a href="{{ route('admin.blogs-cat.index') }}" class="btn btn-success">View Categories</a>
                </div>
            @endcan
        </div>
        <div class="card-body">

            <form method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Enter Category Name</label>
                        <input type="text" name="name" value="{{$cat->name}}" class="form-control" required />
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <input type="submit" class="btn btn-primary" value="Save Now" />
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@push('after-scripts')
    <script>

    
    </script>
@endpush