<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\UserOnly;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ===== ADMIN =====
    Route::middleware(AdminOnly::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tugas', [AdminController::class, 'tugas'])->name('tugas');
        Route::get('/tugas/jabatan/{jabatan}', [AdminController::class, 'tugas'])->name('tugas.jabatan');
        Route::get('/tugas/buat', [AdminController::class, 'tugasBuat'])->name('tugas.buat');
        Route::post('/tugas', [AdminController::class, 'tugasSimpan'])->name('tugas.simpan');
        Route::get('/tugas/{tugas}', [AdminController::class, 'tugasShow'])->name('tugas.show');

        Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/laporan/{laporan}', [AdminController::class, 'laporanShow'])->name('laporan.show');
        Route::post('/laporan/{laporan}/nilai', [AdminController::class, 'nilai'])->name('laporan.nilai');

        Route::get('/akun', [AdminController::class, 'akun'])->name('akun');
        Route::get('/akun/{user}', [AdminController::class, 'akunShow'])->name('akun.show');
    });

    // ===== PETUGAS (Satpam / Pramubakti) =====
    Route::middleware(UserOnly::class)->group(function () {
        Route::get('/tugas', [PetugasController::class, 'tugas'])->name('petugas.tugas');
        Route::post('/tugas/{tugas}/terima', [PetugasController::class, 'terima'])->name('petugas.terima');

        Route::get('/laporan', [PetugasController::class, 'laporan'])->name('petugas.laporan');
        Route::get('/laporan/{laporan}', [PetugasController::class, 'laporanShow'])->name('petugas.laporan.show');
        Route::post('/laporan/{laporan}/kirim', [PetugasController::class, 'kirim'])->name('petugas.kirim');

        Route::get('/pengaturan', [PetugasController::class, 'pengaturan'])->name('akun.saya');
    });
});