<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SemuaLaporanController;
use Illuminate\Support\Facades\Route;

// Hanya untuk yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/laporan', [SemuaLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/{id}', [SemuaLaporanController::class, 'show'])->whereNumber('id')->name('admin.laporan.show');
});
