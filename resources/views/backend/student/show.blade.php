@extends('backend.layouts.app')

@section('title', __('Student').' | '.app_name())
@push('after-styles')
<style>
    table th {
        width: 20%;
    }
</style>
@endpush
@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="page-title d-inline mb-0">Student</h3>
            <div class="float-right">
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-success">View</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                            <!-- <td><img src="/{{ $student->avatar_location }}" class="user-profile-image" style="height:80px;" /></td> -->
                            <td><img src= "{{$student->picture}}"   onerror="this.src='/profile-user.png'" style="height:35px;width:35px;border-radius: 50%;" /></td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                            <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        </tr>
                        
                        <!-- <tr>
                            <th>Title</th>
                            <td>{{ $student->title }}</td>
                        </tr> -->

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                            <td>{{ $student->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                            <td>{!! $student->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.general_settings.user_registration_settings.fields.gender')</th>
                            <td>{!! $student->gender !!}</td>
                        </tr>
                        <tr>
                            <th>Phone </th>
                            <td>{{ $student->phone }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $student->dob }}</td>
                        </tr>
                        <tr>
                            <th>Country </th>
                            <td>{{ $student->country }}</td>
                        </tr>
                        <tr>
                            <th>State </th>
                            <td>{{ $student->state }}</td>
                        </tr>
                        <tr>
                            <th>City </th>
                            <td>{{ $student->city }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $student->address }}</td>
                        </tr>
                        <tr>
                            <th>Pincode</th>
                            <td>{{ $student->pincode }}</td>
                        </tr>
                       
                    </table>
                </div>
            </div><!-- Nav tabs -->
        </div>
    </div>
@stop
