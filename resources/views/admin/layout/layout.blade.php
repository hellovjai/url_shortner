<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Short Url</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Short Url Admin & Dashboard" name="description" />
    <meta content="Short Url" name="author" />
    <meta name="base-url" content="{{ url('/') }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <meta name="userRole" content="{{ Auth::user()->role }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/admin/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Datatables Css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <link href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .invalid-feedback {
            display: none;
        }

        .noted img {
            border-radius: 15px;
            width: 150px;
            box-shadow: 10px 10px 10px solid #000 !important;
            box-shadow: 5px 5px #2054a8
        }

        #ajax-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #4b38b3;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <div id="ajax-loader" style="display: none;">
            <div class="loader"></div>
        </div>
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="javascript:void(0)" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="" alt=""
                                        width="30%">
                                </span>
                                <span class="logo-lg">
                                    <img src="" alt=""
                                        width="30%">
                                </span>
                            </a>

                            <a href="javascript:void(0)" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="" alt=""
                                        width="30%">
                                </span>
                                <span class="logo-lg">
                                    <img src="" alt=""
                                        width="30%">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <!-- App Search-->
                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>

                        </form>
                    </div>

                    <div class="d-flex align-items-center">

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset(Auth::user()->profile_image ?: 'assets/admin/images/users/avatar-1.jpg') }}"
                                        alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name }}</span>
                                        <span
                                            class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ Auth::user()->role }}</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                                <a class="dropdown-item" href="{{ route('admin.mail.setting') }}"><i
                                        class="mdi mdi-email-open-outline text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Mail Settings</span></a>
                                <a class="dropdown-item" href="{{ route('admin.profile.setting') }}"><i
                                        class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Settings</span></a>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                        class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="javascript:void(0)" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/front/img/logo/logo2.png') }}" alt="" width="30%">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/front/img/logo/logo2.png') }}" alt="" width="30%">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="javascript:void(0)" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/front/img/logo/logo2.png') }}" alt="" width="30%">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/front/img/logo/logo2.png') }}" alt="" width="30%">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>
            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="mdi mdi-speedometer"></i> <span data-key="t-dashboard">Dashboard</span>
                            </a>
                        </li> <!-- end Dashboard Menu -->

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('mian-content')

            <!--Toaster Start-->
            <div style="z-index:9999" class="toast-container position-fixed top-0 end-0 p-3">
                <div id="dynamicToast" class="toast overflow-hidden mt-3" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2" id="toastIcon">
                                <i class="align-middle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0" id="toastMessage">Your message here</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Toaster End-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Short Url.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Developed by Vjai
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/admin/js/plugins.js') }}"></script>
        <!-- Dashboard init -->
        <script src="{{ asset('assets/admin/js/pages/dashboard-ecommerce.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/admin/js/pages/form-validation.init.js') }}"></script>

        <!-- Datatables Js-->
        <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/app.js') }}"></script>
        <script src="{{ asset('assets/admin/js/pages/customJs.init.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('input[name="slug"]').on('input', function() {
                    let value = $(this).val();
                    let slug = value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                    $(this).val(slug);
                });
            });
        </script>
        @stack('scripts')


        @if (session('success'))
            <script>
                successToast("{{ session('success') }}");
            </script>
        @endif

</body>

</html>
