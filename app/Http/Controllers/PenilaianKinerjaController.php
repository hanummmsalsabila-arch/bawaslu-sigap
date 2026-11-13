<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenilaianKinerja;

class PenilaianKinerjaController extends Controller
{
    public function store(Request $request)
    {
        $nilaiAkhir = ($request->skor_kehadiran * 0.20)
                    + ($request->skor_kualitas * 0.30)
                    + ($request->skor_kuantitas * 0.20)
                    + ($request->skor_ketepatan * 0.15)
                    + ($request->skor_tanggung_jawab * 0.15);

        $predikat = $nilaiAkhir >= 91 ? 'Sangat Baik' : ($nilaiAkhir >= 81 ? 'Baik' : ($nilaiAkhir >= 71 ? 'Cukup' : 'Perlu Pembinaan'));

        PenilaianKinerja::create([
            'tenaga_pendukung_id' => $request->tenaga_pendukung_id,
            'periode_bulan'       => date('Y-m'),
            'skor_kehadiran'      => $request->skor_kehadiran,
            'skor_kualitas'       => $request->skor_kualitas,
            'skor_kuantitas'      => $request->skor_kuantitas,
            'skor_ketepatan'      => $request->skor_ketepatan,
            'skor_tanggung_jawab' => $request->skor_tanggung_jawab,
            'nilai_akhir'         => $nilaiAkhir,
            'predikat'            => $predikat,
        ]);

        return response()->json([
            'status'      => 'success',
            'nilai_akhir' => round($nilaiAkhir, 2),
            'predikat'    => $predikat
        ]);
    }
}