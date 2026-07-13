<!doctype html>
<html lang="uz" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>Admin login | Doctor A Med Clinic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('admin-assets/assets/images/favicon.ico') }}">
    <script src="{{ asset('admin-assets/assets/js/layout.js') }}"></script>
    <link href="{{ asset('admin-assets/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <a href="{{ route('front.home') }}" class="d-inline-block auth-logo" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('front-assets/logo.png') }}" alt="Doctor A Med Clinic" height="58">
                            </a>
                            <p class="mt-3 fs-15 fw-medium">Doctor A Med Clinic boshqaruv paneli</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Admin panel</h5>
                                    <p class="text-muted">Davom etish uchun login va parolni kiriting.</p>
                                </div>

                                <div class="p-2 mt-4">
                                    @error('login')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror

                                    <form method="POST" action="{{ route('admin.login.store') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="login" class="form-label">Login</label>
                                            <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" placeholder="Loginni kiriting" autocomplete="username" required autofocus>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Parol</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5" name="password" placeholder="Parolni kiriting" id="password-input" autocomplete="current-password" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon">
                                                    <i class="ri-eye-fill align-middle"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="auth-remember-check" name="remember">
                                            <label class="form-check-label" for="auth-remember-check">Eslab qolish</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Kirish</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="text-center">
                    <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Doctor A Med Clinic</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/particles.js/particles.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/pages/particles.app.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/pages/password-addon.init.js') }}"></script>
</body>
</html>
