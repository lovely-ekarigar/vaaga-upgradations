  <header class="main-header main-header-01 bg-white headroom navbar-light fixed-top">
        <nav class="navbar navbar-expand-lg navbar-default">
          <div class="container flex-wrap">
            <!-- Logo: single image with fallback when newassets missing -->
            <a class="navbar-brand header-navbar-brand order-1 me-lg-4" href="{{ url('/') }}">
              <img class="logo-mob-sub-header" src="{{ file_exists(public_path('newassets/img/logo.webp')) ? '/newassets/img/logo.webp' : '/ltlogo.png' }}" width="188" height="61" title="VaaGa Academy" alt="VaaGa Academy" onerror="this.onerror=null; this.src='/ltlogo.png';">
            </a>
            @include('frontend.include.menu')
          </div>
        </nav>
      </header>