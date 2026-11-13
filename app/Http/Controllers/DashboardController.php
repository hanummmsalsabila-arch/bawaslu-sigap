<?php

namespace App\Http\Controllers;

use App\Models\TenagaPendukung;
use App\Models\LaporanHarian;
use App\Models\PenilaianKinerja;

class DashboardController extends Controller
{
    public function index()
    {
        $tenagaPendukung = TenagaPendukung::orderBy('kategori')->get();
        $laporanTerbaru  = LaporanHarian::with('tenagaPendukung')->latest()->get();
        $riwayatEvaluasi = PenilaianKinerja::with('tenagaPendukung')->latest()->get();

        $stats = [
            'total_personel'      => TenagaPendukung::count(),
            'laporan_bulan_ini'   => LaporanHarian::count(),
            'menunggu_verifikasi' => LaporanHarian::where('status_verifikasi', 'Menunggu Verifikasi')->count(),
            'laporan_disetujui'   => LaporanHarian::where('status_verifikasi', 'Disetujui')->count(),
        ];

        return view('dashboard.index', compact('tenagaPendukung', 'laporanTerbaru', 'riwayatEvaluasi', 'stats'));
    }
}