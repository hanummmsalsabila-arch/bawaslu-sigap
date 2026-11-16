<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $isAdmin = $user->role === 'admin'; // sesuaikan dengan kolom/nilai di tabel users

        // TODO: ganti data contoh di bawah dengan query ke tabel laporan
        $ringkasan = [
            'masuk'    => 24,
            'menunggu' => 9,
            'revisi'   => 3,
        ];

        $laporanTerbaru = [
            ['nama' => 'Rina Wulandari', 'kegiatan' => 'Rekap data pengawasan TPS',   'tanggal' => '16 Nov 2026', 'status' => 'Menunggu'],
            ['nama' => 'Dimas Prakoso',  'kegiatan' => 'Penginputan berkas sengketa', 'tanggal' => '16 Nov 2026', 'status' => 'Disetujui'],
            ['nama' => 'Siti Aminah',    'kegiatan' => 'Dokumentasi sosialisasi',     'tanggal' => '15 Nov 2026', 'status' => 'Revisi'],
            ['nama' => 'Yoga Pratama',   'kegiatan' => 'Arsip surat masuk',           'tanggal' => '15 Nov 2026', 'status' => 'Disetujui'],
        ];

        return view('dashboard', compact('user', 'isAdmin', 'ringkasan', 'laporanTerbaru'));
    }
}