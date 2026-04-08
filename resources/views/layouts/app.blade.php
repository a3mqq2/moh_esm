
<!doctype html>
<html lang="ar" dir="rtl" data-layout="topnav" data-topbar-color="dark" data-menu-color="light" data-skin="nova">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'الرئيسية') | الغرفة المركزية للإستجابة والطوارئ - وزارة الصحة الليبية</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="نظام الغرفة المركزية للإستجابة والطوارئ - وزارة الصحة الليبية" />
        <meta name="keywords" content="وزارة الصحة الليبية, الغرفة المركزية, الإستجابة, الطوارئ, ليبيا, صحة, نظام طوارئ" />
        <meta name="author" content="وزارة الصحة الليبية" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}" />
 <!-- Force RTL & Nova skin before config loads -->
<script>
    (function() {
        var stored = sessionStorage.getItem('__THEME_CONFIG__');
        if (stored) {
            try {
                var config = JSON.parse(stored);
                config.dir = 'rtl';
                config.skin = 'nova';
                sessionStorage.setItem('__THEME_CONFIG__', JSON.stringify(config));
            } catch(e) {}
        }
        document.documentElement.setAttribute('dir', 'rtl');
        document.documentElement.setAttribute('lang', 'ar');
        document.documentElement.setAttribute('data-skin', 'nova');
    })();
</script>
<!-- Theme Config Js -->
<script src="{{ asset('assets/js/config.js') }}"></script>

<!-- Vendor css -->
<link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App css -->
<link id="app-style" href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet" />
<style>
    body, * { font-family: 'Almarai', sans-serif !important; }
    /* Hide any theme customizer triggers */
    [data-bs-target="#theme-settings-offcanvas"],
    .theme-settings-btn,
    #theme-settings-btn { display: none !important; }
    /* Top spacing for content */
    .content-page > .container-fluid { padding-top: 18px; }

    /* Larger fonts globally for admin readability */
    body { font-size: 1rem; }
    .page-main-title { font-size: 1.5rem !important; font-weight: 800; }
    h4, .h4 { font-size: 1.45rem !important; }
    h5, .h5 { font-size: 1.2rem !important; }
    h6, .h6 { font-size: 1.05rem !important; }

    /* Tables */
    .table { font-size: 1rem; }
    .table thead th {
        font-size: 0.95rem;
        font-weight: 800;
        padding: 14px 12px;
        background: #f8f9fb;
        color: #1a1d2e;
    }
    .table tbody td {
        padding: 14px 12px;
        vertical-align: middle;
    }
    .table .btn-sm {
        font-size: 0.85rem;
        padding: 6px 12px;
    }

    /* Forms */
    .form-label { font-size: 0.95rem; font-weight: 700; }
    .form-control, .form-select { font-size: 1rem; padding: 9px 12px; }
    .modal-title { font-size: 1.2rem; font-weight: 800; }
    .modal-body { font-size: 1rem; }

    /* Buttons */
    .btn { font-size: 0.95rem; }
    .btn-primary, .btn-warning, .btn-danger, .btn-success { font-weight: 700; }

    /* Badges */
    .badge { font-size: 0.8rem; padding: 5px 10px; font-weight: 700; }
</style>

    </head>

    <body>
        <!-- Page Loading Overlay -->
        <div id="page-loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; align-items:center; justify-content:center; flex-direction:column;">
            <div style="width:46px; height:46px; border:4px solid rgba(255,255,255,0.2); border-top-color:#fff; border-radius:50%; animation:page-spin 0.8s linear infinite;"></div>
            <p id="loader-text" style="color:#fff; margin-top:12px; font-size:1rem; font-family:'Almarai',sans-serif;">جاري التحميل...</p>
        </div>
        <style>@keyframes page-spin { to { transform: rotate(360deg); } }</style>
        <!-- Begin page -->
        <div class="wrapper">
            <header class="app-topbar">
                <div class="container-fluid topbar-menu">
                    <div class="d-flex align-items-center gap-2">
                        <!-- Topbar Brand Logo -->
                        <div class="logo-topbar">
                            <!-- Logo light (dark topbar = white logo) -->
                            <a href="/" class="logo-light">
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/white-logo.png') }}" alt="logo" style="height: 40px;" />
                                </span>
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-primary.png') }}" alt="small logo" style="height: 36px;" />
                                </span>
                            </a>

                            <!-- Logo Dark (light topbar = dark logo) -->
                            <a href="/" class="logo-dark">
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-v.png') }}" alt="dark logo" style="height: 40px;" />
                                </span>
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-primary.png') }}" alt="small logo" style="height: 36px;" />
                                </span>
                            </a>
                        </div>

                        <!-- Sidebar Menu Toggle Button -->
                        <button class="sidenav-toggle-button btn btn-primary btn-icon">
                            <i class="ti ti-menu-4"></i>
                        </button>

                        <!-- Horizontal Menu Toggle Button -->
                        <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu">
                            <i class="ti ti-menu-4"></i>
                        </button>

                        <div class="d-none d-md-flex align-items-center">
                            <h5 class="mb-0 fw-bold topbar-system-name" style="font-size: 0.95rem;">الغرفة المركزية للإستجابة والطوارئ</h5>
                        </div>
                        <style>
                            /* Adapt text color to topbar background */
                            .topbar-system-name { color: rgba(255,255,255,0.85); }
                            html[data-topbar-color="light"] .topbar-system-name { color: rgba(0,0,0,0.7); }
                        </style>

                        @yield('topbar-extras')

                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div id="user-dropdown-detailed" class="topbar-item nav-user">
                            <div class="dropdown">
                                <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" href="#!" aria-haspopup="false" aria-expanded="false">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'مستخدم') }}&background=random&color=fff&size=32&font-size=0.4&bold=true" width="32" class="rounded-circle me-lg-2 d-flex" alt="user-avatar" />
                                    <div class="d-lg-flex align-items-center gap-1 d-none">
                                        <span>
                                            <h5 class="my-0 lh-1 pro-username">{{ Auth::user()->name ?? 'مستخدم' }}</h5>
                                            <span class="fs-xs lh-1">{{ Auth::user()->role ?? 'مستخدم' }}</span>
                                        </span>
                                        <i class="ti ti-chevron-down align-middle"></i>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- Header -->
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">مرحبا {{ Auth::user()->name ?? '' }}</h6>
                                    </div>

                                    <!-- Change Password -->
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="ti ti-lock me-1 fs-lg align-middle"></i>
                                        <span class="align-middle">تغيير كلمة المرور</span>
                                    </a>

                                    <!-- Divider -->
                                    <div class="dropdown-divider"></div>

                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a href="#" class="dropdown-item fw-semibold text-danger" id="logout-btn" onclick="event.preventDefault(); showPageLoader('جاري تسجيل الخروج...'); document.getElementById('logout-form').submit();">
                                            <i class="ti ti-logout me-1 fs-lg align-middle"></i>
                                            <span class="align-middle">تسجيل الخروج</span>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Topbar End -->
 @include('layouts.partials.topnav')


            <!-- ============================================================== -->
            <!-- Start Main Content -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="container-fluid">
                    @yield('page-title')

                    @yield('content')
                </div>
                <!-- container -->

                <!-- Footer Start -->
                <footer class="footer app-footer">
                    <div class="container-fluid text-center">
                        <i class="ti ti-code app-footer-icon"></i>
                        <span>تنفيذ</span>
                        <span class="app-footer-bold">مكتب تقنية المعلومات الصحية</span>
                        <span class="app-footer-sep">|</span>
                        <span class="app-footer-bold">وزارة الصحة الليبية</span>
                    </div>
                </footer>
                <style>
                    .app-footer {
                        background: #fff !important;
                        color: #0a0a0a !important;
                        padding: 16px 20px !important;
                        font-size: 0.95rem !important;
                        font-weight: 600 !important;
                        border-top: 3px solid #0a0a0a !important;
                        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
                    }
                    .app-footer * { color: #0a0a0a !important; }
                    .app-footer-icon {
                        font-size: 1.1rem;
                        margin-left: 6px;
                        color: #0066ff !important;
                    }
                    .app-footer-bold {
                        font-weight: 900 !important;
                        margin: 0 4px;
                    }
                    .app-footer-sep {
                        opacity: 0.4;
                        margin: 0 6px;
                    }
                </style>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End of Main Content -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->

 <!-- Vendor js -->
<script src="{{ asset('assets/js/vendors.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    function showPageLoader(text) {
        var loader = document.getElementById('page-loader');
        if (text) document.getElementById('loader-text').textContent = text;
        else document.getElementById('loader-text').textContent = 'جاري التحميل...';
        loader.style.display = 'flex';
    }
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href]');
        if (link && !link.getAttribute('href').startsWith('#') && !link.getAttribute('href').startsWith('javascript') && !link.hasAttribute('data-bs-toggle') && link.target !== '_blank') {
            showPageLoader();
        }
    });
    window.addEventListener('pageshow', function() {
        document.getElementById('page-loader').style.display = 'none';
    });
</script>

{{-- Change Password Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-lock me-1"></i> تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cp-current" class="form-label">كلمة المرور الحالية <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="cp-current" name="current_password" required />
                        <div class="invalid-feedback" id="cp-current-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="cp-new" class="form-label">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="cp-new" name="new_password" required minlength="6" />
                        <div class="invalid-feedback" id="cp-new-error"></div>
                    </div>
                    <div class="mb-0">
                        <label for="cp-confirm" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="cp-confirm" name="new_password_confirmation" required minlength="6" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="cp-btn">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="cp-spinner"></span>
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function() {
    var form = document.getElementById('changePasswordForm');
    if (!form) return;
    var token = '{{ csrf_token() }}';
    var url = '{{ route("update-password") }}';

    function showCpToast(msg, type) {
        var t = document.createElement('div');
        t.className = 'position-fixed top-0 start-50 translate-middle-x mt-3 alert alert-' + type + ' alert-dismissible fade show';
        t.style.zIndex = '99999';
        t.innerHTML = msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        document.body.appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        form.querySelectorAll('.is-invalid').forEach(function(el) { el.classList.remove('is-invalid'); });
        var spinner = document.getElementById('cp-spinner');
        var btn = document.getElementById('cp-btn');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        var newPw = document.getElementById('cp-new').value;
        var confirmPw = document.getElementById('cp-confirm').value;
        if (newPw !== confirmPw) {
            spinner.classList.add('d-none');
            btn.disabled = false;
            document.getElementById('cp-new').classList.add('is-invalid');
            document.getElementById('cp-new-error').textContent = 'تأكيد كلمة المرور غير مطابق.';
            return;
        }

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({
                current_password: document.getElementById('cp-current').value,
                new_password: newPw,
                new_password_confirmation: confirmPw
            })
        })
        .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
        .then(function(res) {
            spinner.classList.add('d-none');
            btn.disabled = false;
            if (res.ok) {
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                showCpToast(res.data.message, 'success');
            } else if (res.data.errors) {
                Object.keys(res.data.errors).forEach(function(field) {
                    var key = field === 'current_password' ? 'cp-current' : (field === 'new_password' ? 'cp-new' : null);
                    if (key) {
                        var input = document.getElementById(key);
                        var err = document.getElementById(key + '-error');
                        input.classList.add('is-invalid');
                        if (err) err.textContent = res.data.errors[field][0];
                    }
                });
            }
        });
    });
})();
</script>

@yield('scripts')

    </body>
</html>
