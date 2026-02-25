<?php
use App\Models\UserNotification;
?>

<style type="text/css">
  span.badge.badge-danger-round {
    color: #000;
    font-weight: 900;
    background: #febd59;
  }
  .bg-coverx {
    background-size: cover;
  }
  
  /* Mobile dropdown styles */
  .mobile-nav-dropdown {
    display: none;
  }
  
  .mobile-nav-selected {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .mobile-nav-selected i {
    font-size: 1.2rem;
  }
  
  .profile-aside {
    transition: all 0.3s ease;
  }
  
  @media (max-width: 991.98px) {
    .mobile-nav-dropdown {
      display: block;
      margin-bottom: 1rem;
    }
    
    .profile-aside {
      display: none;
    }
    
    .profile-aside.mobile-open {
      display: block;
      position: fixed;
      top: 150px;
      left: 0;
      width: 100%;
      height: 100vh;
      background: white;
      z-index: 1050;
      overflow-y: auto;
      padding: 1rem;
    }
    
    .mobile-nav-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid #dee2e6;
      margin-bottom: 1rem;
    }
    
    .close-mobile-nav {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #6c757d;
    }
  }
  
  @media (max-width: 575.98px) {
    .profile-container {
      margin-top: -50px;
    }
    
    .min-h-350px {
      min-height: 150px !important;
    }
    
    .mobile-nav-selected span {
      font-size: 0.9rem;
    }
  }
</style>

<main>
  <div class="min-h-350px bg-no-repeat bg-coverx" style="background-image: url({{asset('newassets/img/bg/bg-222.png')}});"></div>
  
  <!-- Mobile Navigation Dropdown -->
  <div class="mobile-nav-dropdown d-lg-none">
    <div class="card">
      <div class="card-body p-3">
        <button class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center" type="button" id="mobileNavToggle">
          <div class="mobile-nav-selected" id="mobileNavSelected">
            @php 
              $seg = request()->segment(2);
              $menuItems = [
                'dashboard' => ['icon' => 'bi-person-circle', 'text' => 'Dashboard'],
                'my-test' => ['icon' => 'bi-journal', 'text' => 'Test Series'],
                'my-mock-series' => ['icon' => 'bi-clipboard-check', 'text' => 'Mock Test Series'],
                'my-notifications' => ['icon' => 'bi-envelope', 'text' => 'Notifications'],
                'invoices' => ['icon' => 'bi-credit-card', 'text' => 'Invoices'],
                'certificates' => ['icon' => 'bi-file', 'text' => 'Certificates'],
                'account' => ['icon' => 'bi-gear', 'text' => 'My Profile']
              ];
              
              $currentMenuItem = $menuItems[$seg] ?? $menuItems['dashboard'];
            @endphp
            <i class="bi {{ $currentMenuItem['icon'] }}"></i>
            <span>{{ $currentMenuItem['text'] }}</span>
          </div>
          <i class="bi bi-chevron-down"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Section -->
  <section class="profile-container">
    <div class="container">
      <div class="row align-items-start">
        <div class="col-lg-4 col-xl-3">
          <!-- Mobile Navigation Header -->
          <div class="mobile-nav-header d-lg-none" style="display: none;">
            <h5 class="m-0">Navigation Menu</h5>
            <button class="close-mobile-nav" type="button">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <div class="profile-aside mt-n12" id="profileAside">
            <div class="card mb-4">
              <div class="p-5 text-center">
                <div class="avatar-xl rounded-circle d-inline-block overflow-hidden">
                  <img src="{{ $logged_in_user->picture }}" onerror="this.src='/profile-user.png'" alt="{{ $logged_in_user->name }}" >
                </div>
                <h6 class="fw-500 mt-3 m-0">
                  <span class="fw-700">{{ $logged_in_user->full_name }}</span>
                </h6>
                <span class="small">Student</span>
              </div>
            </div>
            
            <div class="card mb-4">
              <div class="card-header">
                @php $seg = request()->segment(2); @endphp
                <h6 class="my-2">My Account</h6>
              </div>
              <div class="list-group list-group-flush">
                <a href="/user/dashboard" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='dashboard') active @endif" data-menu-item="dashboard">
                  <div>
                    <i class="bi-person-circle me-2"></i>
                    <span>Dashboard</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="/user/my-test" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='my-test') active @endif" data-menu-item="my-test">
                  <div>
                    <i class="bi-journal me-2"></i>
                    <span>Test Series</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                {{-- <a href="/user/my-mock-series" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='my-mock-series') active @endif" data-menu-item="my-mock-series">
                  <div>
                    <i class="bi-clipboard-check me-2"></i>
                    <span>Mock Tests</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a> --}}


                
                <?php
                $unf = UserNotification::where('user_id',Auth::user()->id)->where("status",'0')->count();
                ?>

                <a href="/user/my-notifications" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='my-notifications') active @endif" data-menu-item="my-notifications">
                  <div>
                    <i class="bi-envelope me-2"></i>
                    <span>Notifications
                      @if($unf>0)
                      <span class="badge badge-danger-round"> {{$unf}}</span>
                      @endif
                    </span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="/user/invoices" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='invoices') active @endif" data-menu-item="invoices">
                  <div>
                    <i class="bi-credit-card me-2"></i>
                    <span>Invoices</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="/user/certificates" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='certificates') active @endif" data-menu-item="certificates">
                  <div>
                    <i class="bi-file me-2"></i>
                    <span>Certificates</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="{{route('frontend.userTraining')}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3" data-menu-item="training">
                  <div>
                    <i class="bi bi-cast me-2"></i>
                    <span>Training</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="/user/account" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg=='account') active @endif" data-menu-item="account">
                  <div>
                    <i class="bi-gear me-2"></i>
                    <span>My Profile</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                <a href="{{ route('frontend.auth.logout') }}" class="list-group-item list-group-item-action d-flex justify-content-between py-3" data-menu-item="logout">
                  <div>
                    <i class="bi-bell me-2"></i>
                    <span>Sign Out</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
              </div>
            </div>
          </div>
        

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileNavToggle = document.getElementById('mobileNavToggle');
  const profileAside = document.getElementById('profileAside');
  const closeMobileNav = document.querySelector('.close-mobile-nav');
  const mobileNavHeader = document.querySelector('.mobile-nav-header');
  const mobileNavSelected = document.getElementById('mobileNavSelected');
  const navLinks = document.querySelectorAll('#profileAside .list-group-item');
  
  // Menu items configuration
  const menuItems = {
    'dashboard': { icon: 'bi-person-circle', text: 'Dashboard' },
    'my-test': { icon: 'bi-journal', text: 'Test Series' },
    'my-mock-series': { icon: 'bi-clipboard-check', text: 'Mock Test Series' },
    'my-notifications': { icon: 'bi-envelope', text: 'Notifications' },
    'invoices': { icon: 'bi-credit-card', text: 'Invoices' },
    'certificates': { icon: 'bi-file', text: 'Certificates' },
    'training': { icon: 'bi-cast', text: 'Training' },
    'account': { icon: 'bi-gear', text: 'My Profile' },
    'logout': { icon: 'bi-bell', text: 'Sign Out' }
  };
  
  // Function to update mobile nav selected item
  function updateMobileNavSelected(menuKey) {
    const menuItem = menuItems[menuKey];
    if (menuItem) {
      mobileNavSelected.innerHTML = `
        <i class="bi ${menuItem.icon}"></i>
        <span>${menuItem.text}</span>
      `;
    }
  }
  
  // Set initial selected menu based on current page
  const currentActiveLink = document.querySelector('#profileAside .list-group-item.active');
  if (currentActiveLink) {
    const menuKey = currentActiveLink.getAttribute('data-menu-item');
    updateMobileNavSelected(menuKey);
  }
  
  if (mobileNavToggle && profileAside) {
    mobileNavToggle.addEventListener('click', function() {
      profileAside.classList.toggle('mobile-open');
      mobileNavHeader.style.display = profileAside.classList.contains('mobile-open') ? 'flex' : 'none';
      
      // Toggle chevron icon
      const icon = this.querySelector('.bi-chevron-down, .bi-chevron-up');
      if (profileAside.classList.contains('mobile-open')) {
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
      } else {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
      }
    });
  }
  
  if (closeMobileNav && profileAside) {
    closeMobileNav.addEventListener('click', function() {
      profileAside.classList.remove('mobile-open');
      mobileNavHeader.style.display = 'none';
      
      // Reset chevron icon
      const icon = mobileNavToggle.querySelector('.bi-chevron-up, .bi-chevron-down');
      icon.classList.remove('bi-chevron-up');
      icon.classList.add('bi-chevron-down');
    });
  }
  
  // Close mobile nav and update selected item when clicking on a link
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      const menuKey = this.getAttribute('data-menu-item');
      updateMobileNavSelected(menuKey);
      
      if (window.innerWidth < 992) {
        profileAside.classList.remove('mobile-open');
        mobileNavHeader.style.display = 'none';
        
        // Reset chevron icon
        const icon = mobileNavToggle.querySelector('.bi-chevron-up, .bi-chevron-down');
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
      }
    });
  });
});
</script>