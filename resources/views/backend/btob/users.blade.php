@extends('backend.layouts.app')
@section('title')
    Users - {{ env('APP_NAME') }}
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Users</h3>
            {{-- <div class="float-right">
                <a href="{{ route('admin.btob.create') }}" class="btn btn-success">Add new</a>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count=0; @endphp
                        @foreach ($users as $user)
                            @php $count++ @endphp
                            <tr>
                                <td>{{ $count }}</td>

                                <td>
                                    {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="d-flex justify-content-end">

            </div>
        </div>
    </div>
@endsection
