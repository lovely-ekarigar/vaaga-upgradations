<?php 
use App\Helpers\Helper;

?>
<ul class="metismenu" id="menu">
    <!-- Dashboard -->
    <li class="">
        <a href="{{ route('admin.dashboard')}}" class="{{request()->segment(1)=='dashboard' ? 'active' :''}}">
            <div class="parent-icon"><i class="bx bx-home-alt font-30"></i></div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>
    
    @if(Auth::user()->type!='teacher')

    <!-- Student Management -->
@if(Helper::can('view admission') || Helper::can('view student') || Helper::can('manage id card'))
<li class="">
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class="bx bx-user-plus font-30"></i></div>
        <div class="menu-title">Student</div>
    </a>
    <ul>
        @if(Helper::can('view admission'))
        <li>
            <a href="{{route('admin.students.index')}}" class="{{request()->segment(1)=='admission-list' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>New Admissions
            </a>
        </li>
        @endif

        @if(Helper::can('view student'))
        <li>
            <a href="{{route('admin.students.index')}}" class="{{request()->segment(1)=='user-list' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>All Students
            </a>
        </li>
        @endif

        @if(Helper::can('manage id card'))
        <li>
            <a href="{{'#'}}" class="{{request()->segment(1)=='cards' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>ID Cards
            </a>
        </li>
        @endif
    </ul>
</li>
@endif



 <!-- Academics -->
@if(Helper::can('view course') || Helper::can('view batch'))
<li class="{{ in_array(request()->segment(1), ['course','batches']) ? 'mm-active' : '' }}">
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class="bx bxs-book font-30"></i></div>
        <div class="menu-title">Academics</div>
    </a>
    <ul>
        @if(Helper::can('view course'))
        <li>
            <a href="{{route('admin.courses.index')}}" class="{{request()->segment(1)=='course' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Courses
            </a>
        </li>
        @endif

        @if(Helper::can('view batch'))
        <li>
            <a href="{{route('admin.batch')}}" class="{{request()->segment(1)=='batches' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Batches
            </a>
        </li>
        @endif
    </ul>
</li>
@endif
  @if(Helper::can('manage exam'))

 <li class="">
        <a href="/user/exams" class="{{request()->segment(1)=='exams' ? 'active' :''}}">
            <div class="parent-icon"> <i class='bx bx-exclude font-30'></i></div>
            <div class="menu-title">Examination</div>
        </a>
    </li>
    
     <li class="">
        <a href="{{route('admin.exams.questions.index')}}" class="{{request()->segment(1)=='questions' ? 'active' :''}}">
            <div class="parent-icon"> <i class='bx bx-question-mark font-30'></i></div>
            <div class="menu-title">Question Bank</div>
        </a>
    </li>
    @endif
    
    <!-- Enquiries -->
    @if(Helper::can('view enquiry'))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bxs-contact font-30"></i></div>
            <div class="menu-title">Enquiries</div>
        </a>
        <ul>
            <li>
                <a href="{{route('admin.demo_requests')}}" class="{{request()->segment(1)=='admission-enquiry' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Admission Enquiry
                </a>
            </li>
            <li>
                <a href="{{route('admin.contact-requests.index')}}" class="{{request()->segment(1)=='listContact' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Contact Enquiry
                </a>
            </li>
        </ul>
    </li>
    @endif

    <!-- User Management -->
    @if(Helper::can('view user'))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-user font-30"></i></div>
            <div class="menu-title">User Management</div>
        </a>
        <ul>
            <li>
                <a href="{{route('admin.teachers.index')}}" class="{{request()->segment(1)=='teacher' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Teachers
                </a>
            </li>
            <li>
                <a href="{{'#'}}" class="{{request()->segment(1)=='staff' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Staff
                </a>
            </li>
        </ul>
    </li>
    @endif

    <!-- Reports -->
    @if(Helper::can('view report'))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-category font-30"></i></div>
            <div class="menu-title">Reports</div>
        </a>
        <ul>
            <li>
                <a href="{{route('admin.reports.sales')}}">
                    <i class='bx bx-radio-circle'></i>Payment Report
                </a>
            </li>
            <li>
                <a href="{{route('admin.subscription.report')}}">
                    <i class='bx bx-radio-circle'></i>Due Report
                </a>
            </li>
            <li>
                <a href="{{route('admin.gst.report')}}">
                    <i class='bx bx-radio-circle'></i>EMI Overdue
                </a>
            </li>
            <li>
                <a href="{{route('admin.students.index')}}">
                    <i class='bx bx-radio-circle'></i>Admission Report
                </a>
            </li>
            <li>
                <a href="{{route('admin.demo_requests')}}">
                    <i class='bx bx-radio-circle'></i>Enquiry Report
                </a>
            </li>
            <li>
                <a href="{{route('admin.contact-requests.index')}}">
                    <i class='bx bx-radio-circle'></i>Visitor Report
                </a>
            </li>
        </ul>
    </li>
    @endif

<!-- Content -->
@if(
    Helper::can('view notice') || 
    Helper::can('view festival') || 
    Helper::can('view gallery') || 
    Helper::can('view testimonial') || 
    Helper::can('view blog') || 
    Helper::can('view affiliation')
)
<li class="{{ in_array(request()->segment(1), ['notice','festival','gallery','testimonial','blog','affiliation']) ? 'mm-active' : '' }}">
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class="bx bx-news font-30"></i></div>
        <div class="menu-title">Content</div>
    </a>
    <ul>
        @if(Helper::can('view notice'))
        <li>
            <a href="{{route('admin.notifications')}}" class="{{request()->segment(1)=='notice' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Notices
            </a>
        </li>
        @endif

        @if(Helper::can('view festival'))
        <li>
            <a href="{{route('admin.trainings')}}" class="{{request()->segment(1)=='festival' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Festivals
            </a>
        </li>
        @endif

        @if(Helper::can('view gallery'))
        <li>
            <a href="{{route('admin.resource.index')}}" class="{{request()->segment(1)=='gallery' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Gallery
            </a>
        </li>
        @endif

        @if(Helper::can('view testimonial'))
        <li>
            <a href="{{route('admin.testimonials.index')}}" class="{{request()->segment(1)=='testimonial' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Testimonials
            </a>
        </li>
        @endif

        @if(Helper::can('view blog'))
        <li>
            <a href="{{route('admin.blogs.index')}}" class="{{request()->segment(1)=='blog' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Blogs
            </a>
        </li>
        @endif

        @if(Helper::can('view affiliation'))
        <li>
            <a href="{{route('admin.affiliate')}}" class="{{request()->segment(1)=='affiliation' ? 'active' :''}}">
                <i class='bx bx-radio-circle'></i>Affiliations
            </a>
        </li>
        @endif
    </ul>
</li>
@endif


    <!-- Settings -->
    @if(Helper::can('view slider') || Helper::can('view general') || Helper::can('view page'))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-cog font-30'></i></div>
            <div class="menu-title">Settings</div>
        </a>
        <ul>
            @if(Helper::can('view slider'))
            <li>
                <a href="{{route('admin.sliders.index')}}" class="{{request()->segment(1)=='setting' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Slider
                </a>
            </li>
            @endif
            @if(Helper::can('view general'))
            <li>
                <a href="{{route('admin.general-settings')}}" class="{{request()->segment(1)=='setting' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>General
                </a>
            </li>
            @endif
            @if(Helper::can('view page'))
            <li>
                <a href="{{route('admin.pages.index')}}" class="{{request()->segment(1)=='page' ? 'active' :''}}">
                    <i class='bx bx-radio-circle'></i>Pages
                </a>
            </li>
            @endif
            @if(Helper::can('manage role'))
            <li>
                <a href="{{route('admin.auth.role.index')}}">
                    <i class='bx bx-radio-circle'></i>Roles & Permissions
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    
    @endif

    <!-- Logout -->
    <li class="">
        <a href="{{route('logout')}}">
            <div class="parent-icon"><i class="bx bx-log-out-circle secondary"></i></div>
            <div class="menu-title">Logout</div>
        </a>
    </li>
</ul>
<!--end navigation-->
</div>


<!--start header -->
<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand gap-3">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>

			<div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal"
				data-bs-target="#SearchModal">
				<!-- <input class="form-control px-5" disabled type="search" placeholder="Search">
						<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span> -->
				@if (Session::has('login_admin_id'))
				<div class="py-2">
					<div class="d-flex align-items-center">
						<div class="ms-3">
							<h5 class="mb-0 text-white"><a href="{{ route('admin.auth.user.login-as') }}"
									class="btn btn-outline-primary px-5">Login as Admin</a></h5>
						</div>
					</div>
				</div>
				@endif
			</div>


			<div class="top-menu ms-auto" style="text-align: center;width: 100%;">
				<a style="font-size: 20px;
    font-weight: 600;
    color: #6a67c5;" href="/user/dashboard">{{Auth::user()->name}}</a>
				
			</div>
			<div class="user-box dropdown px-3">
				<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#"
					role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{ Auth::user()->image }}" class="user-img"
						onerror="this.src='/assets/images/avatars/avatar-2.png'" alt="">
					<div class="user-info">
						<p class="user-name mb-0">{{ Auth::user()->name }}</p>
						<p class="designattion mb-0">

						</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.account')}}"><i
								class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
				
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.account')}}"><i
								class="bx bx-cog fs-5"></i><span>Change Password</span></a>
					</li>
				

					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li>

						<a href="{{route('logout')}}" class="dropdown-item d-flex align-items-center"
							href="javascript:;"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>

					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!--end header -->