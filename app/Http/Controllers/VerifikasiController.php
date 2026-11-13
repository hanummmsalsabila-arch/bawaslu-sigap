<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanHarian;

class VerifikasiController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $laporan = LaporanHarian::find($id);
        if ($laporan) {
            $laporan->update([
                'status_verifikasi' => $request->status,
                'verified_at'       => now()
            ]);
        }

        return response()->json([
            'status'     => 'success',
            'new_status' => $request->status
        ]);
    }
}