<!-- Topbar Start -->
<header class="app-topbar">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">

            <!-- Brand Logo -->
            <a href="{{ route('dashboard.pelamar') }}" class="logo">
                <span class="logo-light">
                    <span class="logo-lg"><img src="{{ asset('template_assets/images/logo.png') }}" alt="logo"></span>
                    <span class="logo-sm"><img src="{{ asset('template_assets/images/logo-sm.png') }}" alt="small logo"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="{{ asset('template_assets/images/logo-dark.png') }}" alt="dark logo"></span>
                    <span class="logo-sm"><img src="{{ asset('template_assets/images/logo-sm.png') }}" alt="small logo"></span>
                </span>
            </a>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-secondary btn-icon">
                <i class="ti ti-menu-deep fs-24"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="topnav-toggle-button" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ti ti-menu-deep fs-22"></i>
            </button>

            <!-- Button Trigger Search Modal -->
            <div class="topbar-search text-muted d-none d-xl-flex gap-2 align-items-center" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                <i class="ti ti-search fs-18"></i>
                <span class="me-2">Search something..</span>
                <button type="submit" class="ms-auto btn btn-sm btn-primary shadow-none">⌘K</span>
            </div>

        </div>

        <div class="d-flex align-items-center gap-2">

            <!-- User Dropdown -->
            <div class="topbar-item">
                <div class="dropdown">
                    <a class="topbar-link btn btn-outline-primary dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,22" type="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" width="24" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                        <span class="d-lg-flex flex-column gap-1 d-none">
                            {{ Auth::user()->name }}
                        </span>
                        <i class="ti ti-chevron-down d-none d-lg-block align-middle ms-2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-user-hexagon me-1 fs-17 align-middle"></i>
                            <span class="align-middle">My Account</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item active fw-semibold text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                            <span class="align-middle">Sign Out</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Topbar End -->

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-transparent">
            <div class="card mb-0 shadow-none">
                <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                    <i class="ti ti-search fs-22"></i>
                    <input type="search" class="form-control border-0" id="search-modal-input" placeholder="Search for actions, people,">
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">[esc]</button>
                </div>
            </div>
        </div>
    </div>
</div>