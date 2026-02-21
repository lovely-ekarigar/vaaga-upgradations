<?php
use App\Models\UserNotification;
?>

@inject('request', 'Illuminate\Http\Request')

<style type="text/css">
    .sidebar .nav-dropdown.open>.nav-dropdown-items {

        background: #262a2e;
    }
</style>

<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                @lang('menus.backend.sidebar.general')
            </li>
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('admin/dashboard')) }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon icon-speedometer"></i> @lang('menus.backend.sidebar.dashboard')
                </a>
            </li>


            <!--=======================Custom menus===============================-->

            <!--  @if ($logged_in_user->isAdmin())
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'students' ? 'active' : '' }}"
                       href="/user/students">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Students</span>
                    </a>
                </li>
@endif -->

            @if ($logged_in_user->isAdmin())
                <!--  <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'teachers' ? 'active' : '' }}"
                       href="{{ route('admin.teachers.index') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Tutors</span>
                    </a>
                </li>
                 <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'teachers-availability' ? 'active' : '' }}"
                       href="{{ route('admin.teachers_course_availability') }}">
                        <i class="nav-icon icon-calendar"></i>
                        <span class="title">Tutor availability</span>
                    </a>
                </li> -->
            @endif

            <!--  @if ($logged_in_user->isAdmin())
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'teacher-payments' ? 'active' : '' }}"
                       href="{{ route('admin.teacher_payments') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Teacher Payment</span>
                    </a>
                </li>
@endif -->

            @if (Auth::user()->is_type == 'btob')
                <li class="nav-item">
                    <a class="nav-link {{ $request->segment(2) == 'btob-users' ? 'active' : '' }}"
                        href="{{ route('admin.btob.user.lists') }}">
                        <i class="nav-icon icon-user"></i>
                        <span class="title">Users</span>
                    </a>
                </li>
            @endif




            @if ($logged_in_user->isAdmin())
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/orders*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-puzzle"></i> Orders Menu


                    </a>

                    <ul class="nav-dropdown-items">

                        @can('order_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'orders' ? 'active' : '' }}"
                                    href="{{ route('admin.orders.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.orders.title')</span>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'subscriptions' ? 'active' : '' }}"
                                    href="{{ route('admin.subscription.index') }}">
                                    <span class="title">Subscriptions</span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'subscription-reports' ? 'active' : '' }}"
                                    href="{{ route('admin.subscription.report') }}">
                                    <span class="title">Subscription Dues Report</span>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'gst-reports' ? 'active' : '' }}"
                                    href="{{ route('admin.gst.report') }}">
                                    <span class="title">GST Report</span>
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endif


            @if ($logged_in_user->isAdmin())
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/teachers*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-puzzle"></i> Tutors Menu


                    </a>

                    <ul class="nav-dropdown-items">

                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'teacher-attendance' ? 'active' : '' }}"
                                href="{{ route('admin.teacher_attendance') }}">
                                <span class="title">Tutors Attendance</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'teachers' ? 'active' : '' }}"
                                href="{{ route('admin.teachers.index') }}">
                                <span class="title">Tutors</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'teachers-availability' ? 'active' : '' }}"
                                href="{{ route('admin.teachers_course_availability') }}">
                                <span class="title">Tutor availability</span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
            @if ($logged_in_user->isAdmin())
                {{-- Note: Exam module is visible only for Admin --}}
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('user/tests*', 'user/questions*', 'user/questions_options*', 'user/questions-bank*', 'user/test-series*', 'user/purchase*', 'user/question/report*', 'user/marketing*', 'user/mocktests*', 'user/mock*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/log-viewer*')) }}"
                        href="#">
                        <i class="nav-icon icon-list"></i> Exam
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ $request->segment(2) == 'questions-bank' || $request->segment(2) == 'questions' ? 'active' : '' }}"
                                href="{{ route('admin.exams.questions.index') }}">
                                <span class="title">Question Bank</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $request->segment(2) == 'test-series' || $request->segment(2) == 'tests' ? 'active' : '' }}"
                                href="{{ route('admin.testseries.index') }}">
                                <span class="title">Test Series</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $request->segment(2) == 'purchase' || $request->segment(2) == 'purchase-list' ? 'active' : '' }}"
                                href="{{ route('admin.testseries.purchaseList') }}">
                                <span class="title">Purchase List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $request->segment(2) == 'question' && $request->segment(3) == 'report' ? 'active' : '' }}"
                                href="/user/question/report">
                                <span class="title">Question Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $request->segment(2) == 'marketing' ? 'active' : '' }}"
                                href="/user/marketing">
                                <span class="title">Marketing</span>
                            </a>
                        </li>
                        @can('mocktest_access')
                            <li class="nav-item">
                                <a class="nav-link {{ $request->segment(2) == 'mocktests' ? 'active' : '' }}"
                                    href="{{ route('admin.mocktests.index') }}">
                                    <span class="title">Mock Tests</span>
                                </a>
                            </li>
                        @endcan
                        @can('lesson_create')
                            <li class="nav-item">
                                <a class="nav-link {{ $request->segment(2) == 'mock' ? 'active' : '' }}"
                                    href="{{ route('mockseries.index') }}">
                                    <span class="title">Mock Series</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if ($logged_in_user->isAdmin())
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'track' && $request->segment(3) == 'batch' ? 'active' : '' }}"
                        href="/user/track/batch">
                        <i class="nav-icon icon-vector"></i>
                        <span class="title">Live Class Tracking</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'track' && $request->segment(3) == 'exam' ? 'active' : '' }}"
                        href="/user/track/exam">
                        <i class="nav-icon icon-vector"></i>
                        <span class="title">Live Exam Tracking</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'enquiry-list' ? 'active' : '' }}"
                        href="/user/enquiry-list">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Enquiries</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'b-to-b-lists' ? 'active' : '' }}"
                        href="/user/b-to-b-lists">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">B to B Client</span>
                    </a>
                </li>
            @endif

            @if ($logged_in_user->isAdmin())
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/student*', 'user/demo*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-puzzle"></i> Students Menu


                    </a>

                    <ul class="nav-dropdown-items">

                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'student' || $request->segment(2) == 'students' ? 'active' : '' }}"
                                href="{{ route('admin.students.index') }}">
                                <span class="title">Students</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'demo-batch' ? 'active' : '' }}"
                                href="/user/demo-batch">
                                <span class="title">Demo Batch</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(2) == 'demo-requests' ? 'active' : '' }}"
                                href="{{ route('admin.demo_requests') }}">
                                <span class="title">Demo Requests</span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif

            @if ($logged_in_user->hasRole('teacher'))
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'teacher-attendance' ? 'active' : '' }}"
                        href="{{ route('admin.teacher_attendance') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Tutors Attendance</span>
                    </a>
                </li>
            @endif

            @if ($logged_in_user->isAdmin())
            @endif

            @if ($logged_in_user->isAdmin())
                <!--   <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'student' ? 'active' : '' }}"
                       href="{{ route('admin.students.index') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Students</span>
                    </a>
                </li> -->
            @endif

            @if ($logged_in_user->hasRole('teacher'))
              <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'demo-requests-teacher' ? 'active' : '' }}"
                       href="{{ route('admin.demo_requests_teacher') }}">
                        <i class="nav-icon icon-user"></i>
                        <span class="title">Demo Requests</span>
                    </a>
                </li>  
            @endif
            @if ($logged_in_user->isAdmin())
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'contact-requests' ? 'active' : '' }}"
                        href="/user/contact-requests">
                        <i class="nav-icon icon-envelope"></i>
                        <span class="title">Contact Requests</span>
                    </a>
                </li>
            @endif

            <!--   @if ($logged_in_user->isAdmin())
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'resource' ? 'active' : '' }}"
                       href="{{ route('admin.resource.index') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Resources</span>
                    </a>
                </li>
@endif -->
            @if ($logged_in_user->isAdmin())
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'notifications-list' || $request->segment(2) == 'notifications' ? 'active' : '' }}"
                        href="{{ route('admin.notifications') }}">
                        <i class="nav-icon icon-envelope"></i>
                        <span class="title">Notifications</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'video-link' ? 'active' : '' }}"
                        href="{{ route('admin.homeVideo') }}">
                        <i class="nav-icon icon-envelope"></i>
                        <span class="title">Home Video</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'trainings-list' || $request->segment(2) == 'trainings' ? 'active' : '' }}"
                        href="{{ route('admin.trainings') }}">
                        <i class="nav-icon icon-envelope"></i>
                        <span class="title">Training</span>
                    </a>
                </li>
            @endif
            <!--  @if ($logged_in_user->isAdmin())
<li class="nav-item ">
                    <a class="nav-link"
                       href="/user/assessment">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">Assessment</span>
                    </a>
                </li>
@endif -->


            <!--  @if ($logged_in_user->isAdmin())
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'feedback-list' ? 'active' : '' }}"
                       href="{{ route('admin.feedback-list') }}">
                        <i class="nav-icon icon-directions"></i>
                        <span class="title">FeedBack</span>
                    </a>
                </li>
@endif -->

            @if ($logged_in_user->hasRole('teacher'))
                <?php
                
                $unf = UserNotification::where('user_id', Auth::user()->id)
                    ->where('status', '0')
                    ->count();
                ?>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'notifications-info' ? 'active' : '' }}"
                        href="{{ route('admin.teacher_notifications') }}">
                        <i class="nav-icon icon-envelope"></i>
                        <span class="title">Notifications @if ($unf > 0)
                                <span class="badge badge-danger"> {{ $unf }}</span>
                            @endif
                        </span>
                    </a>
                </li>


                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/reports*']), 'open') }}">
                    <a class="nav-link  {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="{{ route('admin.myclass') }}">
                        <i class="nav-icon icon-screen-desktop"></i>My Classes

                    </a>

                </li>
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/calendar*']), 'open') }}">
                    <a class="nav-link  {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="{{ route('admin.myclass.calendar') }}">
                        <i class="nav-icon icon-calendar"></i>My Calendar

                    </a>

                </li>

            @endif

            @if ($logged_in_user->hasRole('teacher'))
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/reports*']), 'open') }}">
                    <a class="nav-link  {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="{{ route('admin.availability') }}">
                        <i class="nav-icon icon-clock"></i>My Availability

                    </a>

                </li>
            @endif

            @if ($logged_in_user->isAdmin())
                {{-- @if ($logged_in_user->hasRole('teacher') || $logged_in_user->isAdmin() || $logged_in_user->hasAnyPermission(['course_access', 'lesson_access', 'test_access', 'question_access', 'bundle_access'])) --}}

                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/courses*', 'user/lessons*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-puzzle"></i> @lang('menus.backend.sidebar.courses.management')


                    </a>

                    <ul class="nav-dropdown-items">
                        @can('category_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'categories' ? 'active' : '' }}"
                                    href="{{ route('admin.categories.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.categories.title')</span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'batch' ? 'active' : '' }}"
                                    href="{{ route('admin.batch') }}">
                                    <span class="title">Batches</span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'boards' ? 'active' : '' }}"
                                    href="{{ route('admin.boards.index') }}">

                                    <span class="title">Boards</span>
                                </a>
                            </li>
                        @endcan
                        @can('course_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'courses' ? 'active' : '' }}"
                                    href="{{ route('admin.courses.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.courses.title')</span>
                                </a>
                            </li>
                        @endcan

                        @can('lesson_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'content' ? 'active' : '' }}"
                                    href="{{ route('admin.content.index') }}">
                                    <span class="title">Course Content</span>
                                </a>
                            </li>
                        @endcan
                        @can('lesson_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'lessons' ? 'active' : '' }}"
                                    href="{{ route('admin.lessons.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.lessons.title')</span>
                                </a>
                            </li>
                        @endcan

                        <!--  @can('test_access')
    <li class="nav-item ">
                                                                                                            <a class="nav-link {{ $request->segment(2) == 'tests' ? 'active' : '' }}"
                                                                                                               href="{{ route('admin.tests.index') }}">
                                                                                                                <span class="title">@lang('menus.backend.sidebar.tests.title')</span>
                                                                                                            </a>
                                                                                                        </li>
@endcan


                        @can('question_access')
    <li class="nav-item">
                                                                                                            <a class="nav-link {{ $request->segment(2) == 'questions' ? 'active' : '' }}"
                                                                                                               href="{{ route('admin.questions.index') }}">
                                                                                                                <span class="title">@lang('menus.backend.sidebar.questions.title')</span>
                                                                                                            </a>
                                                                                                        </li>
@endcan -->

                    </ul>
                </li>

                @if ($logged_in_user->isAdmin())
                    <li class="nav-item nav-dropdown ">
                        <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                            href="#">
                            <i class="nav-icon icon-puzzle"></i> Notes


                        </a>

                        <ul class="nav-dropdown-items">


                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'note-categories' ? 'active' : '' }}"
                                    href="{{ route('admin.note.category.list') }}">
                                    <span class="title">Categories</span>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'notes' ? 'active' : '' }}"
                                    href="{{ route('admin.note.list') }}">
                                    <span class="title">Study Materials</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif


                <!--  @can('bundle_access')
    <li class="nav-item ">
                                                                                                    <a class="nav-link {{ $request->segment(2) == 'bundles' ? 'active' : '' }}"
                                                                                                       href="{{ route('admin.bundles.index') }}">
                                                                                                        <i class="nav-icon icon-layers"></i>
                                                                                                        <span class="title">@lang('menus.backend.sidebar.bundles.title')</span>
                                                                                                    </a>
                                                                                                </li>
@endcan -->
            @endif

            @if ($logged_in_user->hasRole('teacher') || $logged_in_user->isAdmin())
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/reports*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-pie-chart"></i>@lang('menus.backend.sidebar.reports.title')

                    </a>
                    <ul class="nav-dropdown-items">
                        @if ($logged_in_user->isAdmin())
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(1) == 'sales' ? 'active' : '' }}"
                                    href="{{ route('admin.reports.sales') }}">
                                    @lang('menus.backend.sidebar.reports.sales')
                                </a>
                            </li>
                        @endif
                        <li class="nav-item ">
                            <a class="nav-link {{ $request->segment(1) == 'students' ? 'active' : '' }}"
                                href="{{ route('admin.reports.students') }}">Coursewise Report
                            </a>
                        </li>
                    </ul>
                </li>
            @endif





            @if ($logged_in_user->isAdmin() || $logged_in_user->hasAnyPermission(['blog_access', 'page_access', 'reason_access']))
                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern(['user/contact', 'user/sponsors*', 'user/testimonials*', 'user/faqs*', 'user/footer*', 'user/blogs', 'user/sitemap*']), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/*')) }}"
                        href="#">
                        <i class="nav-icon icon-note"></i> @lang('menus.backend.sidebar.site-management.title')
                    </a>

                    <ul class="nav-dropdown-items">
                        @can('page_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'pages' ? 'active' : '' }}"
                                    href="{{ route('admin.pages.index') }}">
                                    <span class="title">Pages Manager</span>
                                </a>
                            </li>
                        @endcan
                        @can('blog_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'blogs' ? 'active' : '' }}"
                                    href="{{ route('admin.blogs.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.blogs.title')</span>
                                </a>
                            </li>
                        @endcan
                        @can('blog_access')
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'blog-categories' ? 'active' : '' }}"
                                    href="{{ route('admin.blogs-cat.index') }}">
                                    <span class="title">Blog Categories</span>
                                </a>
                            </li>
                        @endcan
                        <!--  @can('reason_access')
    <li class="nav-item">
                                                                                                            <a class="nav-link {{ $request->segment(2) == 'reasons' ? 'active' : '' }}"
                                                                                                               href="{{ route('admin.reasons.index') }}">
                                                                                                                <span class="title">@lang('menus.backend.sidebar.reasons.title')</span>
                                                                                                            </a>
                                                                                                        </li>
@endcan -->
                        @if ($logged_in_user->isAdmin())
                            <!-- <li class="nav-item">
                                <a class="nav-link {{ active_class(Active::checkUriPattern('admin/menu-manager')) }}"
                                   href="{{ route('admin.menu-manager') }}"> {{ __('menus.backend.sidebar.menu-manager.title') }}</a>
                            </li> -->


                            <li class="nav-item ">
                                <a class="nav-link {{ active_class(Active::checkUriPattern('admin/sliders*')) }}"
                                    href="{{ route('admin.sliders.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.hero-slider.title')</span>
                                </a>
                            </li>
                            <!--   <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'sponsors' ? 'active' : '' }}"
                                   href="{{ route('admin.sponsors.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.sponsors.title')</span>
                                </a>
                            </li> -->
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'testimonials' ? 'active' : '' }}"
                                    href="{{ route('admin.testimonials.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.testimonials.title')</span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'achievement' ? 'active' : '' }}"
                                    href="{{ route('admin.achievement.achievement') }}">
                                    <span class="title">Achievement</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'forums-category' ? 'active' : '' }}"
                                   href="{{ route('admin.forums-category.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.forums-category.title')</span>
                                </a>
                            </li> -->
                            <!--  <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'faqs' ? 'active' : '' }}"
                                   href="{{ route('admin.faqs.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.faqs.title')</span>
                                </a>
                            </li>  -->
                            <!--  <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'contact' ? 'active' : '' }}"
                                   href="{{ route('admin.contact-settings') }}">
                                    <span class="title">@lang('menus.backend.sidebar.contact.title')</span>
                                </a>
                            </li> -->
                            <!--  <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'newsletter' ? 'active' : '' }}"
                                   href="{{ route('admin.newsletter-settings') }}">
                                    <span class="title">@lang('menus.backend.sidebar.newsletter-configuration.title')</span>
                                </a>
                            </li> -->
                            <!-- <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'footer' ? 'active' : '' }}"
                                   href="{{ route('admin.footer-settings') }}">
                                    <span class="title">@lang('menus.backend.sidebar.footer.title')</span>
                                </a>
                            </li> -->
                            <!-- <li class="nav-item ">
                                <a class="nav-link {{ $request->segment(2) == 'sitemap' ? 'active' : '' }}"
                                   href="{{ route('admin.sitemap.index') }}">
                                    <span class="title">@lang('menus.backend.sidebar.sitemap.title')</span>
                                </a>
                            </li> -->
                        @endif

                    </ul>


                </li>
            @else
                <!--  @can('blog_access')
    <li class="nav-item ">
                                                                                                    <a class="nav-link {{ $request->segment(2) == 'blogs' ? 'active' : '' }}"
                                                                                                       href="{{ route('admin.blogs.index') }}">
                                                                                                        <i class="nav-icon icon-note"></i>
                                                                                                        <span class="title">@lang('menus.backend.sidebar.blogs.title')</span>
                                                                                                    </a>
                                                                                                </li>
@endcan -->
                <!--  @can('reason_access')
    <li class="nav-item">
                                                                                                    <a class="nav-link {{ $request->segment(2) == 'reasons' ? 'active' : '' }}"
                                                                                                       href="{{ route('admin.reasons.index') }}">
                                                                                                        <i class="nav-icon icon-layers"></i>
                                                                                                        <span class="title">@lang('menus.backend.sidebar.reasons.title')</span>
                                                                                                    </a>
                                                                                                </li>
@endcan -->
            @endif

            <!-- <li class="nav-item ">
                <a class="nav-link {{ $request->segment(1) == 'messages' ? 'active' : '' }}"
                   href="{{ route('admin.messages') }}">
                    <i class="nav-icon icon-envelope-open"></i> <span
                            class="title">@lang('menus.backend.sidebar.messages.title')</span>
                </a>
            </li> -->
            <!-- @if ($logged_in_user->hasRole('student'))
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'invoices' ? 'active' : '' }}"
                       href="{{ route('admin.invoices.index') }}">
                        <i class="nav-icon icon-notebook"></i> <span
                                class="title">@lang('menus.backend.sidebar.invoices.title')</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'certificates' ? 'active' : '' }}"
                       href="{{ route('admin.certificates.index') }}">
                        <i class="nav-icon icon-badge"></i> <span
                                class="title">@lang('menus.backend.sidebar.certificates.title')</span>
                    </a>
                </li>
@endif -->
            <!--  @if ($logged_in_user->hasRole('teacher'))
<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'reviews' ? 'active' : '' }}"
                       href="{{ route('admin.reviews.index') }}">
                        <i class="nav-icon icon-speech"></i> <span
                                class="title">@lang('menus.backend.sidebar.reviews.title')</span>
                    </a>
                </li>
@endif -->

            @if ($logged_in_user->isAdmin())
                <!--  <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'contact-requests' ? 'active' : '' }}"
                       href="{{ route('admin.contact-requests.index') }}">
                        <i class="nav-icon icon-envelope-letter"></i>
                        <span class="title">@lang('menus.backend.sidebar.contacts.title')</span>
                    </a>
                </li> -->
                <!--   <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'affiliate' ? 'active' : '' }}"
                       href="{{ route('admin.affiliate') }}">
                        <i class="nav-icon icon-envelope-letter"></i>
                        <span class="title">Affiliate</span>
                    </a>
                </li> -->
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'contact-requests' ? 'active' : '' }}"
                        href="{{ route('admin.coupons.index') }}">
                        <i class="nav-icon icon-star"></i>
                        <span class="title">@lang('menus.backend.sidebar.coupons.title')</span>
                    </a>
                </li>
                <!--  <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'contact-requests' ? 'active' : '' }}"
                       href="{{ route('admin.tax.index') }}">
                        <i class="nav-icon icon-credit-card"></i>
                        <span class="title">@lang('menus.backend.sidebar.tax.title')</span>
                    </a>
                </li> -->
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'contact-requests' ? 'active' : '' }}"
                        href="{{ route('admin.payments.requests') }}">
                        <i class="nav-icon icon-people"></i>
                        <span class="title">@lang('menus.backend.sidebar.payments_requests.title')</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'teams' ? 'active' : '' }}"
                        href="{{ route('admin.team.list') }}">
                        <i class="nav-icon icon-people"></i>
                        <span class="title">Teams</span>
                    </a>
                </li>
            @endif
            <li class="nav-item ">
                <a class="nav-link {{ $request->segment(1) == 'account' ? 'active' : '' }}"
                    href="{{ route('admin.account') }}">
                    <i class="nav-icon icon-key"></i>
                    <span class="title">@lang('menus.backend.sidebar.account.title')</span>
                </a>
            </li>




            @if ($logged_in_user->isAdmin())


                <!--  <li class="nav-title">
                    @lang('menus.backend.sidebar.system')
                </li> -->

                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('admin/auth*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/auth*')) }}"
                        href="#">
                        <i class="nav-icon icon-user"></i> @lang('menus.backend.access.title')

                        @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                        @endif
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/auth/user*')) }}"
                                href="{{ route('admin.auth.user.index') }}">
                                @lang('labels.backend.access.users.management')

                                @if ($pending_approval > 0)
                                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                                @endif
                            </a> 
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/auth/user*')) }}"
                                href="{{ route('admin.batch.course') }}">
                                User Course Managment

                            </a>
                        </li>


                        <!-- <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/auth/role*')) }}"
                               href="{{ route('admin.auth.role.index') }}">
                                @lang('labels.backend.access.roles.management')
                            </a>
                        </li> -->
                    </ul>
                </li>


                <!--==================================================================-->
                <li class="divider"></li>

                <!-- <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('admin/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/settings*')) }}"
                       href="#">
                        <i class="nav-icon icon-settings"></i> @lang('menus.backend.sidebar.settings.title')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/settings')) }}"
                               href="{{ route('admin.general-settings') }}">
                                @lang('menus.backend.sidebar.settings.general')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/log-viewer/logs*')) }}"
                               href="{{ route('admin.social-settings') }}">
                                @lang('menus.backend.sidebar.settings.social-login')
                            </a>
                        </li>
                    </ul>
                </li> -->

                <li
                    class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'open') }}">
                    <!-- <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('admin/log-viewer*')) }}"
                       href="#">
                        <i class="nav-icon icon-list"></i> @lang('menus.backend.sidebar.debug-site.title')
                    </a> -->

                    <ul class="nav-dropdown-items">
                        {{-- Log Viewer links removed: route log-viewer::dashboard not defined (package not in use)
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/log-viewer')) }}"
                               href="{{ route('log-viewer::dashboard') }}">
                                @lang('menus.backend.log-viewer.dashboard')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('admin/log-viewer/logs*')) }}"
                               href="{{ route('log-viewer::logs.list') }}">
                                @lang('menus.backend.log-viewer.logs')
                            </a>
                        </li>
                        --}}
                    </ul>
                </li>

                <!--<li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'translation-manager' ? 'active' : '' }}"
                       href="{{ asset('user/translations') }}">
                        <i class="nav-icon icon-docs"></i>
                        <span class="title">@lang('menus.backend.sidebar.translations.title')</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'backup' ? 'active' : '' }}"
                       href="{{ route('admin.backup') }}">
                        <i class="nav-icon icon-shield"></i>
                        <span class="title">@lang('menus.backend.sidebar.backup.title')</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(1) == 'update-theme' ? 'active' : '' }}"
                       href="{{ route('admin.update-theme') }}">
                        <i class="nav-icon icon-refresh"></i>
                        <span class="title">@lang('menus.backend.sidebar.update.title')</span>
                    </a>
                </li>-->
            @endif

            @if ($logged_in_user->hasRole('teacher'))
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'payments' ? 'active' : '' }}"
                        href="{{ route('admin.payments') }}">
                        <i class="nav-icon icon-wallet"></i>
                        <span class="title">@lang('menus.backend.sidebar.payments.title')</span>
                    </a>
                </li>
            @endif

            @if ($logged_in_user->hasRole('teacher'))
                <li class="nav-item ">
                    <a class="nav-link {{ $request->segment(2) == 'training' ? 'active' : '' }}"
                        href="{{ route('admin.teacherTraining') }}">
                        <i class="nav-icon icon-user"></i>
                        <span class="title">Training</span>
                    </a>
                </li>
            @endif



            @if ($logged_in_user->hasRole('teacher'))
                <li class="nav-item ">
                    <a class="nav-link" href="/tutor-terms-and-conditions" target="_blank">
                        <i class="nav-icon icon-list"></i>
                        <span class="title">Terms & Conditions</span>
                    </a>
                </li>
            @endif





        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div><!--sidebar-->
