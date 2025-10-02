<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard.pelamar') }}" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="{{ asset('template_assets/images/logo.png') }}" alt="logo"></span>
            <span class="logo-sm text-center"><img src="{{ asset('template_assets/images/logo-sm.png') }}" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="{{ asset('template_assets/images/logo-dark.png') }}" alt="dark logo"></span>
            <span class="logo-sm text-center"><img src="{{ asset('template_assets/images/logo-sm.png') }}" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ti ti-circle align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!--- Sidenav Menu -->
        <ul class="side-nav">

            <li class="side-nav-item">
                <a href="{{ route('dashboard.pelamar') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            @if(Auth::user()->role === 'hrd')
            <li class="side-nav-title mt-2">HRD Menu</li>
            <li class="side-nav-item">
                <a href="{{ route('dashboard.hrd') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-users"></i></span>
                    <span class="menu-text"> Kelola Pelamar </span>
                </a>
            </li>
            @endif

            @if(Auth::user()->role === 'pelamar')
            <li class="side-nav-title mt-2">Pelamar Menu</li>
            <li class="side-nav-item">
                <a href="{{ route('dashboard.pelamar') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-plus"></i></span>
                    <span class="menu-text"> Input Data & CV </span>
                </a>
            </li>
            @endif

            <li class="side-nav-item">
                <a href="{{ route('logout') }}" class="side-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="menu-icon"><i class="ti ti-logout"></i></span>
                    <span class="menu-text"> Logout </span>
                </a>
            </li>

        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->