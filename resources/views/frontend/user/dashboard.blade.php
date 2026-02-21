@extends('frontend.layout.sub-master')

@section('title')
<title>Student Dashboard | {{env('APP_NAME')}}</title>
@stop

@section('content')
<style>
    /* Dashboard Stats Cards */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon.courses { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .stat-icon.tests { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .stat-icon.progress { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    .stat-icon.batches { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Section Cards */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .section-header {
        padding: 1.25rem;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-body {
        padding: 1.25rem;
    }
    
    /* Test Series Cards */
    .test-series-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    
    .test-series-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    
    .test-series-content {
        padding: 1.25rem;
    }
    
    /* Course Cards */
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    
    .course-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .course-content {
        padding: 1rem;
    }
    
    .progress-sm {
        height: 6px;
    }
    
    /* Mock Test Items */
    .mock-test-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }
    
    .mock-test-item:last-child {
        border-bottom: none;
    }
    
    .mock-test-item:hover {
        background: #f8f9fa;
    }
    
    /* Batch/Class Cards */
    .batch-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: white;
        transition: all 0.2s ease;
    }
    
    .batch-card:hover {
        border-color: #007bff;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
    
    /* Quick Actions */
    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
    }
    
    .quick-action-btn:hover {
        background: #f8f9fa;
        border-color: #007bff;
        color: #007bff;
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .section-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            <!-- Welcome Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h3 class="mb-1">Welcome back, {{ $logged_in_user->first_name ?? $logged_in_user->name }}!</h3>
                    <p class="text-muted mb-0">Here's what's happening with your learning journey</p>
                </div>
                <a href="{{ route('courses.all') }}" class="btn btn-primary mt-2 mt-md-0">
                    <i class="bi bi-plus-lg me-2"></i> Browse Courses
                </a>
            </div>
            
            @include('includes.partials.messages')

            <!-- RESTORED: Dashboard Statistics -->
            <div class="row mb-4">
                {{-- HIDDEN: My Courses stat - sub-modules have issues
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon courses">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div class="stat-value">{{ $stats['total_courses'] ?? 0 }}</div>
                        <div class="stat-label">My Courses</div>
                    </div>
                </div>
                --}}
                <div class="col-md-4 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon tests">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="stat-value">{{ $stats['total_test_series'] ?? 0 }}</div>
                        <div class="stat-label">Test Series</div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon progress">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $stats['completed_lessons'] ?? 0 }}</div>
                        <div class="stat-label">Lessons Done</div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon batches">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-value">{{ $stats['total_batches'] ?? 0 }}</div>
                        <div class="stat-label">My Classes</div>
                    </div>
                </div>
            </div>

            {{-- HIDDEN: My Courses Section - sub-modules have issues
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-journal-bookmark text-primary"></i>
                        My Courses
                    </h5>
                    <a href="{{ route('student.courses') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="section-body">
                    @if(count($courses) > 0)
                        <div class="row">
                            @foreach(array_slice($courses, 0, 2) as $course)
                                <div class="col-md-6 mb-3">
                                    <div class="course-card">
                                        <div class="course-content">
                                            <h6 class="mb-2">{{ $course->title }}</h6>
                                            <div class="d-flex justify-content-between text-muted small mb-2">
                                                <span>{{ $course->category->name ?? 'Uncategorized' }}</span>
                                                @if($course->is_expired)
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </div>
                                            <div class="progress progress-sm mb-2">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $course->progress }}%">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ $course->progress }}% Complete</small>
                                                <a href="{{ url('/user/classes/' . $course->slug) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    Continue
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(count($courses) > 2)
                            <div class="text-center mt-3">
                                <a href="{{ route('student.courses') }}" class="text-primary">
                                    View {{ count($courses) - 2 }} more courses <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bi bi-journal-x"></i>
                            <p>You haven't enrolled in any courses yet.</p>
                            <a href="{{ route('courses.all') }}" class="btn btn-primary btn-sm">
                                Browse Courses
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            --}}

            <!-- RESTORED: My Test Series Section -->
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-file-earmark-text text-success"></i>
                        My Test Series
                    </h5>
                    <a href="{{ route('myTestSeries.index') }}" class="btn btn-sm btn-outline-success">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="section-body">
                    @if($testSeries->count() > 0)
                        <div class="row">
                            @foreach($testSeries->take(2) as $ts)
                                <div class="col-md-6 mb-3">
                                    <div class="test-series-card">
                                        <div class="test-series-content">
                                            <h6 class="mb-2">{{ $ts->course->title ?? 'Course' }}</h6>
                                            <p class="text-muted small mb-2">{{ $ts->testSeries->name ?? 'Test Series' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small">
                                                    <strong>{{ $ts->testSeries->total_test ?? 0 }}</strong> Tests
                                                </span>
                                                @php
                                                    $validity = $ts->testSeries->validity ?? '3 months';
                                                    $number = preg_replace('/\D/', '', $validity);
                                                    $validTill = \Carbon\Carbon::parse($ts->created_at)->addMonths($number);
                                                    $isActive = $validTill->isFuture();
                                                @endphp
                                                @if($isActive)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Expired</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('myTestSeries.list', ['id' => $ts->id]) }}" 
                                               class="btn btn-sm btn-outline-success w-100 mt-2">
                                                View Tests
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($testSeries->count() > 2)
                            <div class="text-center mt-3">
                                <a href="{{ route('myTestSeries.index') }}" class="text-success">
                                    View {{ $testSeries->count() - 2 }} more test series <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bi bi-file-earmark-x"></i>
                            <p>You don't have any active test series.</p>
                            <a href="{{ route('courses.all') }}" class="btn btn-success btn-sm">
                                Browse Test Series
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- RESTORED: Mock Tests Section -->
                <div class="col-md-6 mb-4">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="bi bi-clock-history text-warning"></i>
                                Upcoming Mock Tests
                            </h5>
                            <a href="{{ route('student.mocktests.dashboard') }}" class="btn btn-sm btn-outline-warning">
                                All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="section-body p-0">
                            @if($mockSchedules->count() > 0)
                                @foreach($mockSchedules as $schedule)
                                    @php
                                        $now = \Carbon\Carbon::now($schedule->timezone);
                                        $scheduledDate = \Carbon\Carbon::parse($schedule->scheduled_date, $schedule->timezone);
                                        $isAvailable = $now->isSameDay($scheduledDate);
                                    @endphp
                                    <div class="mock-test-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $schedule->mockTest->title ?? 'Mock Test' }}</h6>
                                                <p class="text-muted small mb-1">
                                                    <i class="bi bi-book me-1"></i>
                                                    {{ $schedule->batch->course->title ?? 'Course' }}
                                                </p>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $scheduledDate->format('d M Y') }}
                                                </p>
                                            </div>
                                            @if($isAvailable)
                                                <a href="{{ route('student.mocktests.attempt', $schedule->id) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    Start
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Scheduled</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state py-4">
                                    <i class="bi bi-calendar-x"></i>
                                    <p class="mb-0">No upcoming mock tests</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RESTORED: My Classes/Batches Section -->
                <div class="col-md-6 mb-4">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="bi bi-people text-info"></i>
                                My Classes
                            </h5>
                        </div>
                        <div class="section-body">
                            @if(count($studentBatches) > 0)
                                @foreach(array_slice($studentBatches, 0, 3) as $batch)
                                    <div class="batch-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $batch->name }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-book me-1"></i>{{ $batch->course_name }}
                                                </p>
                                            </div>
                                            <a href="{{ url('/user/downloads/' . $batch->id) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                Materials
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @if(count($studentBatches) > 3)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            +{{ count($studentBatches) - 3 }} more classes
                                        </small>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state py-4">
                                    <i class="bi bi-people"></i>
                                    <p class="mb-0">You are not assigned to any classes yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESTORED: Quick Actions Section -->
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="bi bi-lightning-charge text-danger"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="section-body">
                    <div class="row">
                        {{-- HIDDEN: My Courses quick action - sub-modules have issues
                        <div class="col-md-4">
                            <a href="{{ route('student.courses') }}" class="quick-action-btn">
                                <div class="quick-action-icon">
                                    <i class="bi bi-journal-bookmark"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">My Courses</div>
                                    <div class="small text-muted">View all enrolled courses</div>
                                </div>
                            </a>
                        </div>
                        --}}
                        <div class="col-md-4">
                            <a href="{{ route('courses.all') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Browse Courses</div>
                                    <div class="small text-muted">Explore available courses</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('myTestSeries.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Test Series</div>
                                    <div class="small text-muted">Access your test series</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('student.mocktests.dashboard') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Mock Tests</div>
                                    <div class="small text-muted">Take scheduled mock tests</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('certificates.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Certificates</div>
                                    <div class="small text-muted">Download your certificates</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('myNotifications.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Notifications</div>
                                    <div class="small text-muted">View your notifications</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('frontend.user.account') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                    <i class="bi bi-person-gear"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">My Profile</div>
                                    <div class="small text-muted">Update your profile</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>
</div>
</section>
</main>
@stop

@section('page_js')
<script type="text/javascript">
    // Animate progress bars on page load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
</script>
@stop
