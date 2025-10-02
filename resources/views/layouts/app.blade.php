<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Rekrutmen') | Boron - PHP Responsive Bootstrap Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="index.html" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="index.html" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="ri-menu-2-fill"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">

                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode">
                            <i class="ri-moon-line fs-22"></i>
                        </div>
                    </li>

                    <li class="nav-link"><span class="badge bg-success fs-16">v1.0.1</span></li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="index.html" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="index.html" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!-- Leftbar User -->
                @if(Auth::check())
                <div class="leftbar-user">
                    <a href="#">
                        <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name mt-2">{{ Auth::user()->name }}</span>
                    </a>
                </div>
                @endif

                <!--- Sidemenu -->
                <ul class="side-nav">
                    <li class="side-nav-title">Navigasi</li>

                    @if(Auth::check())
                        @if(Auth::user()->role === 'hrd')
                            <li class="side-nav-item">
                                <a href="{{ route('dashboard.hrd') }}" class="side-nav-link">
                                    <i class="ri-dashboard-line"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('loker.index') }}" class="side-nav-link">
                                    <i class="ri-briefcase-line"></i>
                                    <span> Manajemen Loker </span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('cv_agent.index') }}" class="side-nav-link">
                                    <i class="ri-robot-line"></i>
                                    <span> CV AGENTYC AI </span>
                                </a>
                            </li>
                        @elseif(Auth::user()->role === 'pelamar')
                            <li class="side-nav-item">
                                <a href="{{ route('dashboard.pelamar') }}" class="side-nav-link">
                                    <i class="ri-user-line"></i>
                                    <span> Profil Saya </span>
                                </a>
                            </li>
                        @endif

                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-line"></i>
                                <span> Logout </span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endif
                </ul>
                <!--- End Sidemenu -->

                <div class="help-box text-white text-center d-none">
                    <a href="javascript: void(0);" class="float-end close-btn text-white">
                        <i class="mdi mdi-close"></i>
                    </a>
                    <img src="{{ asset('assets/images/svg/help-icon.svg') }}" height="90" alt="Helper Icon Image">
                    <h5 class="mt-3">Unlimited Access</h5>
                    <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>
                    <a href="javascript: void(0);" class="btn btn-secondary btn-sm">Buy Now</a>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © Boron - <a href="https://coderthemes.com/" target="_blank">Coderthemes.com</a>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="https://coderthemes.com/" target="_blank">About</a>
                                <a href="https://coderthemes.com/" target="_blank">Support</a>
                                <a href="https://coderthemes.com/" target="_blank">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Sweet Alert Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
    </script>

    @stack('scripts')

</body>

</html>
