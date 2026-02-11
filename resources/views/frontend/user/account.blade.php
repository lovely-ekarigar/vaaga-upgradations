@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', app_name() . ' | ' . __('labels.frontend.user.account_title'))

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>@lang('labels.frontend.user.account_title')</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>@lang('labels.frontend.user.profile_information')</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>@lang('labels.frontend.user.name')</th>
                                        <td>{{ $logged_in_user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('labels.frontend.user.email')</th>
                                        <td>{{ $logged_in_user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('labels.frontend.user.joined_at')</th>
                                        <td>{{ $logged_in_user->created_at->format('d M Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>@lang('labels.frontend.user.actions')</h5>
                                <div class="list-group">
                                    <a href="{{ route('frontend.user.dashboard') }}" class="list-group-item list-group-item-action">
                                        @lang('navs.frontend.dashboard')
                                    </a>
                                    <a href="{{ route('frontend.auth.logout') }}" class="list-group-item list-group-item-action text-danger">
                                        @lang('navs.general.logout')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
