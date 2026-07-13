<!doctype html>
<html lang="uz" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin') | Doctor A Med Clinic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('admin-assets/assets/images/favicon.ico') }}">
    <script src="{{ asset('admin-assets/assets/js/layout.js') }}"></script>
    <link href="{{ asset('admin-assets/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .admin-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            line-height: 1.05;
            transform: translateY(5px);
        }

        .navbar-brand-box .logo {
            text-align: left;
            padding-left: 16px;
        }

        .navbar-brand-box {
            padding: 0 !important;
            text-align: left !important;
        }

        .admin-brand-logo {
            position: relative;
            flex: 0 0 auto;
            width: 42px;
            aspect-ratio: 1 / 1;
        }

        .admin-brand-logo span {
            position: absolute;
            display: block;
            border-radius: 50%;
            background: rgba(255,255,255,.96);
        }

        .admin-brand-logo span:nth-child(1) { width: 20%; height: 20%; left: 40%; top: 40%; }
        .admin-brand-logo span:nth-child(2),
        .admin-brand-logo span:nth-child(3),
        .admin-brand-logo span:nth-child(4),
        .admin-brand-logo span:nth-child(5) { width: 14%; height: 14%; }
        .admin-brand-logo span:nth-child(2) { left: 43%; top: 17%; }
        .admin-brand-logo span:nth-child(3) { right: 17%; top: 43%; }
        .admin-brand-logo span:nth-child(4) { left: 43%; bottom: 17%; }
        .admin-brand-logo span:nth-child(5) { left: 17%; top: 43%; }
        .admin-brand-logo span:nth-child(6),
        .admin-brand-logo span:nth-child(7),
        .admin-brand-logo span:nth-child(8),
        .admin-brand-logo span:nth-child(9) { width: 9%; height: 9%; }
        .admin-brand-logo span:nth-child(6) { left: 24%; top: 24%; }
        .admin-brand-logo span:nth-child(7) { right: 24%; top: 24%; }
        .admin-brand-logo span:nth-child(8) { right: 24%; bottom: 24%; }
        .admin-brand-logo span:nth-child(9) { left: 24%; bottom: 24%; }
        .admin-brand-logo span:nth-child(10),
        .admin-brand-logo span:nth-child(11),
        .admin-brand-logo span:nth-child(12),
        .admin-brand-logo span:nth-child(13),
        .admin-brand-logo span:nth-child(14),
        .admin-brand-logo span:nth-child(15),
        .admin-brand-logo span:nth-child(16),
        .admin-brand-logo span:nth-child(17) { width: 5%; height: 5%; }
        .admin-brand-logo span:nth-child(10) { left: 47.5%; top: 0; }
        .admin-brand-logo span:nth-child(11) { right: 13%; top: 13%; }
        .admin-brand-logo span:nth-child(12) { right: 0; top: 47.5%; }
        .admin-brand-logo span:nth-child(13) { right: 13%; bottom: 13%; }
        .admin-brand-logo span:nth-child(14) { left: 47.5%; bottom: 0; }
        .admin-brand-logo span:nth-child(15) { left: 13%; bottom: 13%; }
        .admin-brand-logo span:nth-child(16) { left: 0; top: 47.5%; }
        .admin-brand-logo span:nth-child(17) { left: 13%; top: 13%; }

        .admin-brand-text {
            display: grid;
            gap: 1px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .admin-brand-text small {
            color: rgba(255,255,255,.72);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.8px;
        }

        .logo-sm .admin-brand-logo {
            width: 34px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon"><span></span><span></span><span></span></span>
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" data-bs-toggle="dropdown">
                                <span class="d-flex align-items-center">
                                    <span class="text-start">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name }}</span>
                                        <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Administrator</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('front.home') }}"><i class="ri-global-line text-muted fs-16 align-middle me-1"></i> Saytga o‘tish</a>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="ri-logout-box-r-line text-muted fs-16 align-middle me-1"></i> Chiqish</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm"><span class="admin-brand-logo">@for ($i = 1; $i <= 17; $i++)<span></span>@endfor</span></span>
                    <span class="logo-lg admin-brand"><span class="admin-brand-logo">@for ($i = 1; $i <= 17; $i++)<span></span>@endfor</span><span class="admin-brand-text">Doctor A<small>MED CLINIC</small></span></span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm"><span class="admin-brand-logo">@for ($i = 1; $i <= 17; $i++)<span></span>@endfor</span></span>
                    <span class="logo-lg admin-brand"><span class="admin-brand-logo">@for ($i = 1; $i <= 17; $i++)<span></span>@endfor</span><span class="admin-brand-text">Doctor A<small>MED CLINIC</small></span></span>
                </a>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span>Menu</span></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.dashboard') }}"><i class="ri-dashboard-2-line"></i> <span>Dashboard</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'services') }}"><i class="ri-service-line"></i> <span>Xizmatlar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'about-slides') }}"><i class="ri-slideshow-line"></i> <span>Klinika slaydlari</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'doctors') }}"><i class="ri-user-heart-line"></i> <span>Shifokorlar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'testimonials') }}"><i class="ri-chat-quote-line"></i> <span>Fikrlar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'articles') }}"><i class="ri-newspaper-line"></i> <span>Yangiliklar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'partners') }}"><i class="ri-building-line"></i> <span>Hamkorlar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'hero-videos') }}"><i class="ri-youtube-line"></i> <span>Hero videolar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'vacancies') }}"><i class="ri-briefcase-line"></i> <span>Vakant lavozimlar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'branches') }}"><i class="ri-map-pin-line"></i> <span>Filiallar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.content.index', 'appointment-types') }}"><i class="ri-stethoscope-line"></i> <span>Qabul yo‘nalishlari</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.appointments.index') }}"><i class="ri-calendar-check-line"></i> <span>Qabul so‘rovlari</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.resumes.index') }}"><i class="ri-file-user-line"></i> <span>Rezyumelar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.users.index') }}"><i class="ri-user-settings-line"></i> <span>Foydalanuvchilar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('admin.settings') }}"><i class="ri-settings-3-line"></i> <span>Asosiy sozlamalar</span></a></li>
                        <li class="nav-item"><a class="nav-link menu-link" href="{{ route('front.home') }}"><i class="ri-global-line"></i> <span>Sayt</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="sidebar-background"></div>
        </div>

        <div class="vertical-overlay"></div>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('title', 'Admin')</h4>
                                @yield('actions')
                            </div>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-semibold mb-2">Formada xatolik bor. Maydonlarni tekshiring.</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <script>document.write(new Date().getFullYear())</script> © Doctor A Med Clinic.
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
