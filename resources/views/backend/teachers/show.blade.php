@extends('backend.layouts.app')

@section('title', __('labels.backend.teachers.title').' | '.app_name())
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
            <h3 class="page-title d-inline mb-0">Tutor</h3>
            <div class="float-right">
                <a href="{{ route('admin.teachers.index') }}"
                   class="btn btn-success">View Tutor</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                            <!-- <td><img src="/{{ $teacher->avatar_location }}" class="user-profile-image" style="height:80px;" /></td> -->
                             <td><img src= "{{$teacher->picture}}"   onerror="this.src='/profile-user.png'" style="height:35px;width:35px;border-radius: 50%;" /></td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                            <td>{{ $teacher->first_name }} {{ $teacher->middle_name }} {{ $teacher->last_name }}</td>
                        </tr>
                        
                        <!-- <tr>
                            <th>Title</th>
                            <td>{{ $teacher->title }}</td>
                        </tr> -->

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                            <td>{{ $teacher->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                            <td>{!! $teacher->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.general_settings.user_registration_settings.fields.gender')</th>
                            <td>{!! $teacher->gender !!}</td>
                        </tr>
                        <tr>
                            <th>Phone </th>
                            <td>{{ $teacher->phone }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $teacher->dob }}</td>
                        </tr>
                        <tr>
                            <th>Country </th>
                            <td>{{ $teacher->country }}</td>
                        </tr>
                        <tr>
                            <th>City </th>
                            <td>{{ $teacher->city }}</td>
                        </tr> 
                        <tr>
                            <th>State </th>
                            <td>{{ $teacher->state }}</td>
                        </tr>

                        @php
                            $teacherProfile = $teacher->teacherProfile?:'';
                            $payment_details = $teacher->teacherProfile?json_decode($teacher->teacherProfile->payment_details):new stdClass();
                        @endphp
                        <tr>
                            <th>Highest Qualification</th>
                            <td>{!! $teacherProfile->hig_qualification !!}</td>
                        </tr>
                        <tr>
                            <th>Total Experience</th>
                            <td>{!! $teacherProfile->total_exp !!} Years</td>
                        </tr>
                        <tr>
                            <th>Relevant Experience</th>
                            <td>{!! $teacherProfile->relevant_exp !!} Years</td>
                        </tr>
                        <tr>
                            <th>Subjects you would like to teach</th>
                            <td>{!! $teacherProfile->subject_teach !!}</td>
                        </tr>
                        <tr>
                            <th>Grades you would like to teach</th>
                            <td>{!! $teacherProfile->grade_teach !!}</td>
                        </tr>
                        <tr>
                            <th>Language Spoken</th>
                            <td>{!! $teacherProfile->lang_proficiency !!}</td>
                        </tr>
                        <tr>
                            <th>Base Salary</th>
                            <td>{{ $teacherProfile->base_salary }}</td>
                        </tr>
                        <tr>
                            <th>Upload latest CV</th>
                            <td> <a href="{{asset($teacherProfile->upload_cv)}}" target="_blank">Download</a></td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.facebook_link')</th>
                            <td>{!! $teacherProfile->facebook_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.twitter_link')</th>
                            <td>{!! $teacherProfile->twitter_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.linkedin_link')</th>
                            <td>{!! $teacherProfile->linkedin_link !!}</td>
                        </tr>
                         <tr>
                            <th>Instagram Link</th>
                            <td>{!! $teacherProfile->instagram_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.payment_details')</th>
                            <td>{!! $teacherProfile->payment_method !!}</td>
                        </tr>
                        @if($teacherProfile->payment_method == 'bank')
                        <tr>
                            <th>@lang('labels.teacher.bank_details.name')</th>
                            <td>{!! $payment_details->bank_name !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.bank_details.ifsc_code')</th>
                            <td>{!! $payment_details->ifsc_code !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.bank_details.account')</th>
                            <td>{!! $payment_details->account_number !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.bank_details.holder_name')</th>
                            <td>{!! $payment_details->account_name !!}</td>
                        </tr>
                        
                        @endif
                        <tr>
                            <th>Aadhar Card</th>
                            <td>
                                <img src="/{{ $teacherProfile->aadhar_card }}" class="user-profile-image" style="height:80px;" />
                                <a href="{{asset($teacherProfile->aadhar_card)}}" class="badge badge-info" download="">Download</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Pan Card</th>
                            <td>
                                <img src="/{{ $teacherProfile->pan_card }}" class="user-profile-image" style="height:80px;" />
                            <a href="{{asset($teacherProfile->pan_card)}}" class="badge badge-info" download="">Download</a></td>
                        </tr>
                        <tr>
                            <th>Photo ID proof</th>
                            <td>
                                <img src="/{{ $teacherProfile->photo_id_proof }}" class="user-profile-image" style="height:80px;" />
                                <a href="{{asset($teacherProfile->photo_id_proof)}}" class="badge badge-info" download="">Download</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
        </div>
    </div>
@stop
