<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'stats' => [
                    ['angka' => Laporan::whereDate('dikirim_at', today())->count(), 'label' => 'Laporan masuk hari ini', 'merah' => false],
                    ['angka' => Laporan::where('status', 'menunggu')->count(),      'label' => 'Menunggu verifikasi',    'merah' => true],
                    ['angka' => Laporan::where('status', 'revisi')->count(),        'label' => 'Perlu revisi',           'merah' => false],
                ],
                'laporan' => Laporan::with(['user', 'tugas'])
                    ->whereNotNull('dikirim_at')
                    ->latest('dikirim_at')
                    ->limit(8)
                    ->get(),
            ]);
        }

        $hitung = fn (string $status) => Laporan::where('user_id', $user->id)->where('status', $status)->count();

        $tersedia = Tugas::untuk($user)
            ->whereDoesntHave('laporan', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        return view('dashboard.user', [
            'stats' => [
                ['angka' => $tersedia,            'label' => 'Tugas tersedia',      'merah' => false],
                ['angka' => $hitung('menunggu'),  'label' => 'Menunggu verifikasi', 'merah' => false],
                ['angka' => $hitung('revisi'),    'label' => 'Perlu revisi',        'merah' => true],
            ],
            'perluRevisi' => $hitung('revisi'),
            'laporan' => Laporan::with('tugas')
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->limit(5)
                ->get(),
        ]);
    }
}