@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('header')
    <h4 class="fw-bold text-dark mt-2">مرحبا بك</h4>
    <p class="text-muted mx-auto">قم بتسجيل الدخول للمتابعة</p>
@endsection

@section('content')
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">
                البريد الإلكتروني أو اسم المستخدم
                <span class="text-danger">*</span>
            </label>
            <div class="app-search">
                <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="البريد الإلكتروني أو اسم المستخدم" required autofocus />
                <i class="ti ti-user app-search-icon text-muted"></i>
            </div>
            @error('username')
                <div class="text-danger mt-1 fs-13">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                كلمة المرور
                <span class="text-danger">*</span>
            </label>
            <div class="app-search">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required />
                <i class="ti ti-lock-password app-search-icon text-muted"></i>
            </div>
            @error('password')
                <div class="text-danger mt-1 fs-13">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input form-check-input-light fs-14" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                <label class="form-check-label" for="remember">تذكرني</label>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg fw-semibold py-2">تسجيل الدخول</button>
        </div>
    </form>
@endsection
