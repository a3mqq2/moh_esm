
<!doctype html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'وزارة الصحة الليبية') - الغرفة المركزية للإستجابة والطوارئ</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="نظام الغرفة المركزية للإستجابة والطوارئ - وزارة الصحة الليبية" />
        <meta name="keywords" content="وزارة الصحة الليبية, الغرفة المركزية, الإستجابة, الطوارئ, ليبيا, صحة, نظام طوارئ" />
        <meta name="author" content="وزارة الصحة الليبية" />
        <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}" />
        <script src="{{ asset('assets/js/config.js') }}"></script>
        <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
        <link id="app-style" href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet" />
        <style>
            body, * { font-family: 'Almarai', sans-serif !important; }
            body {
                background: url("{{ asset('assets/images/auth.jpg') }}") no-repeat center center fixed;
                background-size: cover;
                min-height: 100vh;
                overflow: hidden;
                position: relative;
            }
            .auth-bg-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                background: linear-gradient(135deg, rgba(26,26,46,0.85) 0%, rgba(22,33,62,0.8) 30%, rgba(15,52,96,0.75) 60%, rgba(83,19,30,0.85) 100%);
                pointer-events: none;
            }
            .auth-bg-texture {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                pointer-events: none;
                overflow: hidden;
            }
            .auth-bg-texture svg {
                width: 100%;
                height: 100%;
            }
            .pulse-circle {
                animation: pulse 4s ease-in-out infinite;
                transform-origin: center;
            }
            .pulse-circle:nth-child(2) { animation-delay: 1s; }
            .pulse-circle:nth-child(3) { animation-delay: 2s; }
            .pulse-circle:nth-child(4) { animation-delay: 3s; }
            @keyframes pulse {
                0%, 100% { opacity: 0.25; transform: scale(1); }
                50% { opacity: 0.55; transform: scale(1.15); }
            }
            .float-cross {
                animation: floatUp 8s ease-in-out infinite;
                opacity: 0.25;
            }
            .float-cross:nth-child(2) { animation-delay: 2s; animation-duration: 10s; }
            .float-cross:nth-child(3) { animation-delay: 4s; animation-duration: 12s; }
            .float-cross:nth-child(4) { animation-delay: 1s; animation-duration: 9s; }
            .float-cross:nth-child(5) { animation-delay: 3s; animation-duration: 11s; }
            @keyframes floatUp {
                0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.15; }
                50% { transform: translateY(-30px) rotate(10deg); opacity: 0.4; }
            }
            .wave-line {
                animation: waveMove 6s ease-in-out infinite;
                opacity: 0.3;
            }
            .wave-line:nth-child(2) { animation-delay: 2s; }
            .wave-line:nth-child(3) { animation-delay: 4s; }
            @keyframes waveMove {
                0%, 100% { transform: translateX(0); opacity: 0.2; }
                50% { transform: translateX(20px); opacity: 0.45; }
            }
            .auth-box { position: relative; z-index: 1; }
            .card-body form, .card-body .form-label, .card-body .form-control,
            .card-body .form-check, .card-body .form-check-label,
            .card-body .d-flex { direction: rtl; text-align: right; }
            .card-body .form-control { text-align: right; }
            .card-body .app-search .app-search-icon { left: auto; right: 10px; }
            .card-body .app-search .form-control { padding-left: 0.75rem; padding-right: 2.2rem; }
            .card-body .form-check { padding-left: 0; padding-right: 1.5em; }
            .card-body .form-check .form-check-input { float: right; margin-left: 0; margin-right: -1.5em; }
            .auth-loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(26,26,46,0.9);
                z-index: 9999;
                display: none;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }
            .auth-loading-overlay.active { display: flex; }
            .auth-spinner {
                width: 50px;
                height: 50px;
                border: 4px solid rgba(255,255,255,0.2);
                border-top-color: #e74c3c;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            .auth-loading-overlay p {
                color: #fff;
                margin-top: 15px;
                font-size: 1.1rem;
            }
        </style>
    </head>

    <body>
        <div class="auth-loading-overlay" id="loadingOverlay">
            <div class="auth-spinner"></div>
            <p>جاري تسجيل الدخول...</p>
        </div>
        <div class="auth-bg-overlay"></div>
        <div class="auth-bg-texture">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice">
                <!-- Pulse circles (heartbeat / emergency) -->
                <circle class="pulse-circle" cx="200" cy="150" r="120" fill="none" stroke="#e74c3c" stroke-width="2.5"/>
                <circle class="pulse-circle" cx="1200" cy="700" r="160" fill="none" stroke="#ff6b6b" stroke-width="2.5"/>
                <circle class="pulse-circle" cx="700" cy="450" r="200" fill="none" stroke="#e74c3c" stroke-width="2"/>
                <circle class="pulse-circle" cx="1000" cy="200" r="100" fill="none" stroke="#ff6b6b" stroke-width="2"/>

                <!-- Medical crosses -->
                <g class="float-cross" transform="translate(150, 600)">
                    <rect x="-6" y="-18" width="12" height="36" rx="3" fill="#ff6b6b"/>
                    <rect x="-18" y="-6" width="36" height="12" rx="3" fill="#ff6b6b"/>
                </g>
                <g class="float-cross" transform="translate(1300, 150)">
                    <rect x="-8" y="-24" width="16" height="48" rx="3" fill="#e74c3c"/>
                    <rect x="-24" y="-8" width="48" height="16" rx="3" fill="#e74c3c"/>
                </g>
                <g class="float-cross" transform="translate(900, 100)">
                    <rect x="-5" y="-15" width="10" height="30" rx="2" fill="#ff6b6b"/>
                    <rect x="-15" y="-5" width="30" height="10" rx="2" fill="#ff6b6b"/>
                </g>
                <g class="float-cross" transform="translate(400, 800)">
                    <rect x="-7" y="-21" width="14" height="42" rx="3" fill="#e74c3c"/>
                    <rect x="-21" y="-7" width="42" height="14" rx="3" fill="#e74c3c"/>
                </g>
                <g class="float-cross" transform="translate(1100, 500)">
                    <rect x="-6" y="-18" width="12" height="36" rx="3" fill="#ff6b6b"/>
                    <rect x="-18" y="-6" width="36" height="12" rx="3" fill="#ff6b6b"/>
                </g>

                <!-- ECG / heartbeat wave lines -->
                <polyline class="wave-line" points="0,450 100,450 130,450 150,400 160,500 170,420 180,470 200,450 400,450 500,450 530,450 550,400 560,500 570,420 580,470 600,450 800,450" fill="none" stroke="#ff6b6b" stroke-width="3"/>
                <polyline class="wave-line" points="400,300 500,300 530,300 550,250 560,350 570,270 580,320 600,300 800,300 900,300 930,300 950,250 960,350 970,270 980,320 1000,300 1200,300" fill="none" stroke="#e74c3c" stroke-width="2.5"/>
                <polyline class="wave-line" points="200,700 300,700 330,700 350,650 360,750 370,670 380,720 400,700 600,700 700,700 730,700 750,650 760,750 770,670 780,720 800,700 1000,700" fill="none" stroke="#ff6b6b" stroke-width="2"/>
            </svg>
        </div>
        <div class="auth-box d-flex align-items-center">
            <div class="container-xxl">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8">
                        <div class="card">
                                    <div class="card-body p-4">
                                        <div class="auth-brand text-center mb-4 position-relative">
                                            <a href="/" class="logo-dark">
                                                <img src="{{ asset('assets/images/logo-v.png') }}" alt="dark logo" style="height: 80px !important;" />
                                            </a>
                                            <a href="/" class="logo-light">
                                                <img src="{{ asset('assets/images/white-logo.png') }}" alt="logo" style="max-height: 80px !important;" />
                                            </a>
                                            <h3 class="fw-800 text-danger mt-3 mb-1" style="font-size: 1.4rem;font-weight:bold!important; letter-spacing: 0.5px;">الغرفة المركزية للإستجابة والطوارئ</h3>
                                            @yield('header')
                                        </div>

                                        @yield('content')

                                        <p class="text-center text-muted mt-4 mb-1">
                                            ©
                                            <script>
                                                document.write(new Date().getFullYear())
                                            </script>
                                            <span class="fw-bold">وزارة الصحة الليبية</span>
                                        </p>
                                        <p class="text-center text-muted mb-0 fs-13">
                                            تطوير <span class="fw-bold">مكتب تقنية المعلومات الصحية</span>
                                        </p>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end auth-fluid-->

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var loginInput = document.getElementById('username');
                var passwordInput = document.getElementById('password');
                var form = document.querySelector('form');
                var loadingOverlay = document.getElementById('loadingOverlay');

                if (loginInput && passwordInput) {
                    loginInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            passwordInput.focus();
                        }
                    });
                }

                if (form && loadingOverlay) {
                    form.addEventListener('submit', function() {
                        loadingOverlay.classList.add('active');
                    });
                }
            });
        </script>

        @yield('scripts')

    </body>
</html>
