<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemuaLaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        $query = DB::table('laporan_harian as l')
            ->leftJoin('tenaga_pendukung as t', 't.id', '=', 'l.tenaga_pendukung_id')
            ->select([
                'l.id',
                'l.kode_laporan',
                'l.tanggal_kegiatan',
                'l.jam_mulai',
                'l.jam_selesai',
                'l.kategori',
                'l.jenis_pekerjaan',
                'l.lokasi_pekerjaan',
                'l.status_penyelesaian',
                'l.status_verifikasi',
                't.nama_lengkap',
                't.kode_petugas',
            ]);

        // Pencarian bebas: kode laporan, nama petugas, jenis pekerjaan, lokasi
        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('l.kode_laporan', 'like', "%{$q}%")
                    ->orWhere('t.nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('l.jenis_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('l.lokasi_pekerjaan', 'like', "%{$q}%");
            });
        }

        if ($kategori = $request->input('kategori')) {
            $query->where('l.kategori', $kategori);
        }

        if ($status = $request->input('status')) {
            $query->where('l.status_verifikasi', $status);
        }

        if ($dari = $request->input('dari')) {
            $query->whereDate('l.tanggal_kegiatan', '>=', $dari);
        }

        if ($sampai = $request->input('sampai')) {
            $query->whereDate('l.tanggal_kegiatan', '<=', $sampai);
        }

        $laporan = $query
            ->orderByDesc('l.tanggal_kegiatan')
            ->orderByDesc('l.id')
            ->paginate(15)
            ->withQueryString();

        // Pilihan filter diambil dari data yang benar-benar ada di database
        $kategoriList = DB::table('laporan_harian')
            ->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        $statusList = DB::table('laporan_harian')
            ->whereNotNull('status_verifikasi')->distinct()->orderBy('status_verifikasi')->pluck('status_verifikasi');

        return view('admin.laporan.index', compact('laporan', 'kategoriList', 'statusList'));
    }

    public function show(int $id)
    {
        $this->authorizeAdmin();

        $laporan = DB::table('laporan_harian as l')
            ->leftJoin('tenaga_pendukung as t', 't.id', '=', 'l.tenaga_pendukung_id')
            ->where('l.id', $id)
            ->select(
                'l.*',
                't.nama_lengkap',
                't.kode_petugas',
                't.area_tanggung_jawab',
                't.jadwal_shift',
                't.no_hp'
            )
            ->first();

        abort_if(! $laporan, 404);

        return view('admin.laporan.show', compact('laporan'));
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}