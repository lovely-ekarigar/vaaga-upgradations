  
<?php
use App\Models\UserNotification;
?>

<style type="text/css">
  span.badge.badge-danger-round {
    color: #000;
    font-weight: 900;
    background: #febd59;
  }
  .dashboard-banner, .min-h-350px {
    min-height: 350px !important;
    height: 350px !important;
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    width: 100% !important;
    display: block !important;
    visibility: visible !important;
  }
  .dashboard-banner img {
    width: 100%;
    height: 350px;
    object-fit: cover;
  }
  @media (max-width: 768px) {
    .dashboard-banner, .min-h-350px {
      min-height: 200px !important;
      height: 200px !important;
    }
  }
</style>

<main>
  <div class="dashboard-banner min-h-350px" style="background-image: url('{{asset('newassets/img/bg/bg-222.png')}}'); background-color: #4a5568;"></div>
  <!-- <div class="mask bg-0000_ opacity-8"></div> -->
  <!-- Section -->
  <section class="profile-container">
    <div class="container">
      <div class="row align-items-start">
        <div class="col-lg-4 col-xl-3">
          <div class="profile-aside mt-n12">
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
                @php
                  $seg2 = request()->segment(2);
                  $seg3 = request()->segment(3);
                @endphp
                <h6 class="my-2">My Account</h6>
              </div>
              <div class="list-group list-group-flush">
                <a href="/user/dashboard" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='dashboard') active @endif">
                  <div>
                    <i class="bi-person-circle me-2"></i>
                    <span>Dashboard </span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                <a href="/user/account" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='account') active @endif">
                  <div>
                    <i class="bi-gear me-2"></i>
                    <span> My Profile</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                {{-- HIDDEN: My Courses link has issues in sub-modules --}}
                {{--
                <a href="/user/my-courses" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='my-courses') active @endif">
                  <div>
                    <i class="bi-journal-bookmark me-2"></i>
                    <span>My Courses</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                --}}
                <a href="/user/invoices" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='invoices') active @endif">
                  <div>
                    <i class="bi-credit-card me-2"></i>
                    <span>Invoices</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                
                 <a href="/user/certificates" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='certificates') active @endif">
                  <div>
                    <i class="bi-file me-2"></i>
                    <span>Certificates</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>

                {{-- RESTORED: My Test Series link - was missing during upgrade --}}
                <a href="/user/my-test" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='my-test') active @endif">
                  <div>
                    <i class="bi-file-earmark-text me-2"></i>
                    <span>My Test Series</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>

                <?php
                $unf = UserNotification::where('user_id',Auth::user()->id)->where("status",'0')->count();
                ?>
                <a href="/user/my-notifications" class="list-group-item list-group-item-action d-flex justify-content-between py-3 @if($seg2=='my-notifications') active @endif">
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
                <a href="{{route('frontend.userTraining')}}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
                  <div>
                    <i class="bi bi-cast me-2"></i>
                    <span>Training</span>
                  </div>
                  <div>
                    <i class="bi-chevron-right"></i>
                  </div>
                </a>
                <a href="{{ route('frontend.auth.logout') }}" class="list-group-item list-group-item-action d-flex justify-content-between py-3">
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