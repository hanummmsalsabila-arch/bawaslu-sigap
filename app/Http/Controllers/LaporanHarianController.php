<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanHarian;
use App\Models\TenagaPendukung;
use Illuminate\Support\Facades\Storage;

class LaporanHarianController extends Controller
{
    public function store(Request $request)
    {
        $kodeLaporan = 'SIGAP-' . date('Ymd') . '-' . rand(100, 999);
        $petugas     = TenagaPendukung::find($request->tenaga_pendukung_id);
        $namaPetugas = $petugas ? $petugas->nama_lengkap : 'Petugas_SIGAP';

        $photoUrl = null;

        // Simpan foto ber-watermark langsung ke Local Storage Laravel
        if ($request->filled('watermarked_image')) {
            $parts       = explode(';base64,', $request->watermarked_image);
            $binaryImage = base64_decode($parts[1] ?? $request->watermarked_image);
            $fileName    = $kodeLaporan . '_' . str_replace(' ', '_', $namaPetugas) . '.jpg';

            Storage::disk('public')->put('dokumentasi/' . $fileName, $binaryImage);
            $photoUrl = asset('storage/dokumentasi/' . $fileName);
        }

        $laporan = LaporanHarian::create([
            'kode_laporan'        => $kodeLaporan,
            'tenaga_pendukung_id' => $request->tenaga_pendukung_id,
            'tanggal_kegiatan'    => now()->toDateString(),
            'jam_mulai'           => $request->jam_mulai ?? '07:30',
            'jam_selesai'         => $request->jam_selesai ?? '09:00',
            'kategori'            => $request->kategori,
            'jenis_pekerjaan'     => $request->jenis_pekerjaan,
            'lokasi_pekerjaan'    => $request->lokasi_pekerjaan,
            'uraian_pekerjaan'    => $request->uraian_pekerjaan,
            'status_penyelesaian' => $request->status_penyelesaian ?? 'Selesai 100%',
            'catatan_kendala'     => $request->catatan_kendala,
            'shift_jaga'          => $request->shift_jaga,
            'kondisi_keamanan'    => $request->kondisi_keamanan ?? 'Aman & Kondusif',
            'jumlah_tamu'         => $request->jumlah_tamu ?? 0,
            'gdrive_file_id'      => 'LOCAL-STORAGE',
            'gdrive_view_link'    => $photoUrl,
            'status_verifikasi'   => 'Menunggu Verifikasi',
        ]);

        return response()->json([
            'status'       => 'success',
            'kode_laporan' => $kodeLaporan,
            'photo_url'    => $photoUrl,
            'id'           => $laporan->id
        ]);
    }
}