@extends('frontend.layouts.app'.config('theme_layout', ''))

@section('title', app_name() . ' | ' . __('labels.frontend.user.dashboard_title'))

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>@lang('labels.frontend.user.dashboard_title')</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>@lang('strings.frontend.welcome') {{ $logged_in_user->name ?? '' }}</h4>
                                <p>@lang('strings.frontend.user.dashboard_description')</p>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">@lang('labels.frontend.user.courses')</h5>
                                        <p class="card-text">@lang('strings.frontend.user.manage_courses')</p>
                                        <a href="{{ route('frontend.courses') }}" class="btn btn-primary">@lang('buttons.frontend.view_courses')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">@lang('labels.frontend.user.account')</h5>
                                        <p class="card-text">@lang('strings.frontend.user.manage_account')</p>
                                        <a href="{{ route('frontend.user.account') }}" class="btn btn-primary">@lang('buttons.frontend.manage_account')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">@lang('labels.frontend.user.assessments')</h5>
                                        <p class="card-text">@lang('strings.frontend.user.view_assessments')</p>
                                        <a href="{{ route('frontend.assessment.index') }}" class="btn btn-primary">@lang('buttons.frontend.view_assessments')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
