@extends('backend.layouts.app')
@section('title')
    BtoB Clients - {{ env('APP_NAME') }}
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">BtoB Clients</h3>
            <div class="float-right">
                <a href="{{ route('admin.btob.create') }}" class="btn btn-success">Add new</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Organization Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Code</th>
                            <th>Action &nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count=0; @endphp
                        @foreach ($btob_lists as $btob)
                            @php $count++ @endphp
                            <tr>
                                <td>{{ $count }}</td>
                                <td>
                                    {{ $btob->org_name }}
                                </td>
                                <td>
                                    {{ $btob->first_name }}
                                </td>
                                <td>{{ $btob->email }}</td>
                                <td>{{ $btob->coupon_code }}</td>
                                <td>
                                    <a href="{{ route('admin.btob.edit', ['btob' => $btob->id]) }}"
                                        class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>

                                    <a href="?del={{ $btob->id }}" onclick="return confirm('DO you want to delete?')"
                                        title="Delete {{ $btob->first_name }}" class="btn btn-xs btn-danger text-white mb-1"
                                        style="cursor:pointer;"> <i class="fa fa-trash"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="d-flex justify-content-end">
                {{ $btob_lists->links() }}
            </div>
        </div>
    </div>
@endsection
