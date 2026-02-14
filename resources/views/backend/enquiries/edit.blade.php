@extends('backend.layouts.app')

@section('title', 'View Enquiry' . ' | ' . app_name())

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">View Enquiry #{{$enquiry->id}}</h3>
            <div class="float-right">
                <a href="{{route('admin.endquiryIndex')}}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{$enquiry->name}}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{$enquiry->mobile}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$enquiry->email}}</td>
                        </tr>
                        <tr>
                            <th>Grade</th>
                            <td>{{$enquiry->grade}}</td>
                        </tr>
                        <tr>
                            <th>Interested In</th>
                            <td>{{$enquiry->insterested ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{$enquiry->gender ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{$enquiry->created_at->format('d M Y H:i')}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
