<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\HospitalDashboardController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WardController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/update-password', [AuthController::class, 'updatePassword'])->middleware('auth')->name('update-password');

Route::middleware('auth')->group(function () {
    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('wards', WardController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('hospitals', HospitalController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('hospitals/{hospital}/units', [HospitalController::class, 'getUnits']);
        Route::post('hospitals/{hospital}/units', [HospitalController::class, 'syncUnits']);
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Hospital manager only
    Route::middleware('role:hospital_manager')->group(function () {
        Route::get('my-hospital', [HospitalDashboardController::class, 'index'])->name('my-hospital');
        Route::post('my-hospital/update-vacant', [HospitalDashboardController::class, 'updateVacant'])->name('my-hospital.update-vacant');
    });

    // Observer + Admin can access monitor
    Route::middleware('role:observer,admin')->group(function () {
        Route::get('monitor', [MonitorController::class, 'index'])->name('monitor');
        Route::get('monitor/data', [MonitorController::class, 'data'])->name('monitor.data');
    });
});
