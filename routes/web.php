<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\PenilaianKinerjaController;

// Langsung buka Dashboard Utama BAWASLU-SIGAP (Tanpa Login)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Endpoint Simpan Laporan & Foto Bukti
Route::post('/api/laporan/store', [LaporanHarianController::class, 'store'])->name('laporan.store');

// Endpoint Verifikasi Laporan
Route::post('/api/verifikasi/{id}', [VerifikasiController::class, 'updateStatus'])->name('verifikasi.update');

// Endpoint Simpan Evaluasi Kinerja
Route::post('/api/penilaian/store', [PenilaianKinerjaController::class, 'store'])->name('penilaian.store');