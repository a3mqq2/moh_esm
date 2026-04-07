<header class="topnav">
    <nav class="navbar navbar-expand-lg">
        <nav class="container-fluid">
            <div class="collapse navbar-collapse" id="topnav-menu">
                <ul class="navbar-nav">
                    @if(auth()->user()->role === 'hospital_manager')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('my-hospital') }}">
                                <span class="menu-icon"><i class="ti ti-building-hospital fs-xl"></i></span>
                                <span class="menu-text">مستشفيي</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->role === 'observer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('monitor') }}">
                                <span class="menu-icon"><i class="ti ti-broadcast fs-xl"></i></span>
                                <span class="menu-text">شاشة المراقبة</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <span class="menu-icon"><i class="ti ti-home fs-xl"></i></span>
                                <span class="menu-text">الرئيسية</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('departments.index') }}">
                                <span class="menu-icon"><i class="ti ti-building fs-xl"></i></span>
                                <span class="menu-text">الأقسام</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wards.index') }}">
                                <span class="menu-icon"><i class="ti ti-bed fs-xl"></i></span>
                                <span class="menu-text">العنايات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('hospitals.index') }}">
                                <span class="menu-icon"><i class="ti ti-building-hospital fs-xl"></i></span>
                                <span class="menu-text">المستشفيات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <span class="menu-icon"><i class="ti ti-users fs-xl"></i></span>
                                <span class="menu-text">المستخدمين</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('monitor') }}">
                                <span class="menu-icon"><i class="ti ti-broadcast fs-xl"></i></span>
                                <span class="menu-text">شاشة المراقبة</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </nav>
</header>
<!-- Horizontal Menu End -->
