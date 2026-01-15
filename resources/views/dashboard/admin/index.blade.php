@extends('backend.layouts.app')

@section('title', 'Admin Dashboard | '.app_name())

@push('after-styles')
    <style>
        .stat-card {
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Admin Dashboard</strong>
                </div>
                <div class="card-body">
                    <!-- User Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-primary">
                                <div class="stat-label">Total Users</div>
                                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-success">
                                <div class="stat-label">Active Users</div>
                                <div class="stat-value">{{ $totalActiveUsers ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning">
                                <div class="stat-label">Inactive Users</div>
                                <div class="stat-value">{{ $totalInActiveUsers ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-info">
                                <div class="stat-label">Paid Users</div>
                                <div class="stat-value">{{ $totalPaidUsers ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-dark">
                                <div class="stat-label">Total Subscriptions</div>
                                <div class="stat-value">{{ $totalSubscriptions ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-success">
                                <div class="stat-label">Active Subscriptions</div>
                                <div class="stat-value">{{ $totalActiveSubscriptions ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning">
                                <div class="stat-label">Pending Subscriptions</div>
                                <div class="stat-value">{{ $totalPendingSubscriptions ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-danger">
                                <div class="stat-label">Expired Subscriptions</div>
                                <div class="stat-value">{{ $totalExpiredSubscriptions ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="stat-card bg-secondary">
                                <div class="stat-label">New Subscriptions This Month</div>
                                <div class="stat-value">{{ $newSubscriptionsThisMonth ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card bg-warning">
                                <div class="stat-label">Expiring Subscriptions This Month</div>
                                <div class="stat-value">{{ $expiringSubscriptionsThisMonth ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Affiliate Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-primary">
                                <div class="stat-label">Total Affiliates</div>
                                <div class="stat-value">{{ $totalAffiliates ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-success">
                                <div class="stat-label">Active Affiliates</div>
                                <div class="stat-value">{{ $totalActiveAffiliates ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning">
                                <div class="stat-label">Inactive Affiliates</div>
                                <div class="stat-value">{{ $totalInActiveAffiliates ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-info">
                                <div class="stat-label">New Affiliates This Month</div>
                                <div class="stat-value">{{ $newAffiliatesThisMonth ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Subscription Growth Chart -->
                    @if(isset($dailySubscriptionGrowth) && $dailySubscriptionGrowth && $dailySubscriptionGrowth->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Daily Subscription Growth (Last 30 Days)</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="subscriptionGrowthChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Course Completions Chart -->
                    @if(isset($courseCompletions) && $courseCompletions && $courseCompletions->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Course Completions</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="courseCompletionsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quiz Completions Chart -->
                    @if(isset($quizCompletions) && $quizCompletions && $quizCompletions->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quiz Completions</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="quizCompletionsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Subscription Renewal Information -->
                    @if(isset($subscriptions) && $subscriptions)
                        @if(is_object($subscriptions) && method_exists($subscriptions, 'count') && $subscriptions->count() > 0)
                            @php
                                $firstSubscription = $subscriptions->first();
                            @endphp
                            @if(isset($firstSubscription) && isset($firstSubscription->next_renewal_date) && !empty($firstSubscription->next_renewal_date))
                                <div class="alert alert-info">
                                    <strong>Next Renewal Date:</strong> {{ $firstSubscription->next_renewal_date }}
                                </div>
                            @endif
                        @elseif(is_object($subscriptions) && isset($subscriptions->next_renewal_date) && !empty($subscriptions->next_renewal_date))
                            <div class="alert alert-info">
                                <strong>Next Renewal Date:</strong> {{ $subscriptions->next_renewal_date }}
                            </div>
                        @endif
                    @endif

                    <!-- Affiliate Information -->
                    @if(isset($affiliates) && $affiliates)
                        @if(isset($affiliates->affiliate_start_date) && !empty($affiliates->affiliate_start_date))
                            <div class="alert alert-success">
                                <strong>Affiliate Start Date:</strong> {{ $affiliates->affiliate_start_date }}
                            </div>
                        @elseif(isset($affiliates->created_at))
                            <div class="alert alert-success">
                                <strong>Affiliate Start Date:</strong> {{ $affiliates->created_at->format('Y-m-d') }}
                            </div>
                        @endif
                    @endif

                    <!-- Latest Subscription Status -->
                    @if(isset($latest_subscription) && $latest_subscription)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Latest Subscription Status</h5>
                                    </div>
                                    <div class="card-body">
                                        @if(method_exists($latest_subscription, 'isExpired') && $latest_subscription->isExpired())
                                            <div class="alert alert-danger">
                                                <strong>Status:</strong> Expired
                                            </div>
                                        @elseif(method_exists($latest_subscription, 'isPending') && $latest_subscription->isPending())
                                            <div class="alert alert-warning">
                                                <strong>Status:</strong> Pending
                                            </div>
                                        @else
                                            <div class="alert alert-success">
                                                <strong>Status:</strong> Active
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(isset($dailySubscriptionGrowth) && $dailySubscriptionGrowth && $dailySubscriptionGrowth->count() > 0)
        // Daily Subscription Growth Chart
        var subscriptionCtx = document.getElementById('subscriptionGrowthChart');
        if (subscriptionCtx) {
            var subscriptionData = {
                labels: @json($dailySubscriptionGrowth->pluck('date')),
                datasets: [{
                    label: 'Subscriptions',
                    data: @json($dailySubscriptionGrowth->pluck('count')),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };
            new Chart(subscriptionCtx, {
                type: 'line',
                data: subscriptionData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        @endif

        @if(isset($courseCompletions) && $courseCompletions && $courseCompletions->count() > 0)
        // Course Completions Chart
        var courseCtx = document.getElementById('courseCompletionsChart');
        if (courseCtx) {
            var courseData = {
                labels: @json($courseCompletions->pluck('course_name')),
                datasets: [{
                    label: 'Completions',
                    data: @json($courseCompletions->pluck('completions_count')),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };
            new Chart(courseCtx, {
                type: 'bar',
                data: courseData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        @endif

        @if(isset($quizCompletions) && $quizCompletions && $quizCompletions->count() > 0)
        // Quiz Completions Chart
        var quizCtx = document.getElementById('quizCompletionsChart');
        if (quizCtx) {
            var quizData = {
                labels: @json($quizCompletions->pluck('quiz_title')),
                datasets: [{
                    label: 'Completions',
                    data: @json($quizCompletions->pluck('completions_count')),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };
            new Chart(quizCtx, {
                type: 'bar',
                data: quizData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        @endif
    </script>
@endpush
