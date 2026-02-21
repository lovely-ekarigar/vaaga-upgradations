@extends('frontend.layout.sub-master')

@section('title')
<title>My Courses | {{env('APP_NAME')}}</title>
@stop

@section('content')
<style>
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .course-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-3px);
    }
    
    .course-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .course-content {
        padding: 1.5rem;
    }
    
    .course-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        font-size: 1.15rem;
        line-height: 1.4;
    }
    
    .course-category {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .course-category i {
        margin-right: 0.5rem;
        color: #007bff;
    }
    
    .progress-container {
        margin-bottom: 1rem;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 4px;
        background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        transition: width 0.6s ease;
    }
    
    .course-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    .course-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .course-actions .btn {
        flex: 1;
        min-width: 120px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .expired-badge {
        background: #dc3545;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    
    .expired-badge i {
        margin-right: 0.35rem;
    }
    
    .active-badge {
        background: #28a745;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    
    .active-badge i {
        margin-right: 0.35rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin: 2rem 0;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .empty-state h4 {
        color: #495057;
        margin-bottom: 0.75rem;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .course-card {
            margin-bottom: 1.25rem;
        }
        
        .course-content {
            padding: 1rem;
        }
        
        .course-title {
            font-size: 1rem;
        }
        
        .course-stats {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .course-actions {
            flex-direction: column;
        }
        
        .course-actions .btn {
            width: 100%;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="mb-6 pb-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">My Courses</h3>
                <a href="{{ route('courses.all') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Browse More Courses
                </a>
            </div>
            
            @include('includes.partials.messages')

            @if(count($courses) > 0)
                <div class="row">
                    @foreach($courses as $course)
                        <div class="col-md-6 mb-4">
                            <div class="course-card h-100">
                                <!-- Course Image -->
                                @if($course->course_image)
                                    <img src="{{ asset('storage/uploads/' . $course->course_image) }}" 
                                         alt="{{ $course->title }}" 
                                         class="course-image"
                                         onerror="this.src='{{ asset('newassets/img/course/course-default.jpg') }}'">
                                @else
                                    <div class="course-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book" style="font-size: 3rem; color: white;"></i>
                                    </div>
                                @endif
                                
                                <div class="course-content">
                                    <!-- Status Badge -->
                                    <div class="mb-2">
                                        @if($course->is_expired)
                                            <span class="expired-badge">
                                                <i class="bi bi-exclamation-circle"></i> Expired
                                            </span>
                                        @else
                                            <span class="active-badge">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Course Title -->
                                    <h5 class="course-title">{{ $course->title }}</h5>
                                    
                                    <!-- Category -->
                                    <div class="course-category">
                                        <i class="bi bi-folder"></i>
                                        {{ $course->category->name ?? 'Uncategorized' }}
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="progress-container">
                                        <div class="progress-label">
                                            <span>Course Progress</span>
                                            <span>{{ $course->progress }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $course->progress }}%"
                                                 aria-valuenow="{{ $course->progress }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Stats -->
                                    <div class="course-stats">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $course->completed_lessons }}</div>
                                            <div class="stat-label">Completed</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $course->total_lessons }}</div>
                                            <div class="stat-label">Total Lessons</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $course->tests()->count() ?? 0 }}</div>
                                            <div class="stat-label">Tests</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="course-actions">
                                        @if($course->is_expired)
                                            <a href="{{ route('courses.show', $course->slug) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="bi bi-arrow-repeat me-1"></i> Renew
                                            </a>
                                        @else
                                            <a href="{{ url('/user/classes/' . $course->slug) }}" 
                                               class="btn btn-primary">
                                                <i class="bi bi-play-circle me-1"></i> Continue
                                            </a>
                                            <a href="{{ route('courses.show', $course->slug) }}" 
                                               class="btn btn-outline-secondary">
                                                <i class="bi bi-info-circle me-1"></i> Details
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="bi bi-journal-x"></i>
                    <h4>No Courses Enrolled</h4>
                    <p>You haven't enrolled in any courses yet. Browse our courses and start learning today!</p>
                    <a href="{{ route('courses.all') }}" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i> Browse Courses
                    </a>
                </div>
            @endif
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
            const width = bar.getAttribute('aria-valuenow');
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 100);
        });
    });
</script>
@stop
