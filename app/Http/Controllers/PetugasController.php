<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tugas;
use App\Services\FotoKompres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    public function tugas()
    {
        $user = auth()->user();

        $tugas = Tugas::untuk($user)
            ->whereDoesntHave('laporan', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('petugas.tugas', compact('tugas'));
    }

    public function terima(Tugas $tugas)
    {
        $user = auth()->user();

        abort_unless(Tugas::untuk($user)->whereKey($tugas->id)->exists(), 403);

        $laporan = Laporan::firstOrCreate(
            ['tugas_id' => $tugas->id, 'user_id' => $user->id],
            ['status' => 'diterima', 'diterima_at' => now()]
        );

        if ($laporan->wasRecentlyCreated) {
            $laporan->catat('diterima', null, $user->id);
        }

        return redirect()->route('petugas.laporan.show', $laporan)
            ->with('ok', 'Tugas diterima. Kirim laporan setelah tugas selesai.');
    }

    public function laporan()
    {
        $laporan = Laporan::with('tugas')
            ->where('user_id', auth()->id())
            ->latest('diterima_at')
            ->get();

        return view('petugas.laporan', compact('laporan'));
    }

    public function laporanShow(Laporan $laporan)
    {
        abort_unless($laporan->user_id === auth()->id(), 403);

        $laporan->load(['tugas', 'fotos', 'riwayat.user']);

        return view('petugas.laporan_show', compact('laporan'));
    }

    public function kirim(Request $request, Laporan $laporan)
    {
        abort_unless($laporan->user_id === auth()->id(), 403);
        abort_unless($laporan->bisaDikirim(), 403, 'Laporan ini tidak bisa dikirim lagi.');

        $wajibFoto = $laporan->fotos()->doesntExist();

        $request->validate([
            'selesai'  => ['accepted'],
            'catatan'  => ['nullable', 'string', 'max:1000'],
            'foto'     => [$wajibFoto ? 'required' : 'nullable', 'array', 'max:5'],
            'foto.*'   => ['image', 'mimes:jpg,jpeg,png,webp', 'max:15360'],
        ], [
            'selesai.accepted' => 'Centang dulu bahwa tugas sudah selesai.',
            'foto.required'    => 'Foto bukti pekerjaan wajib diunggah.',
            'foto.max'         => 'Maksimal 5 foto.',
            'foto.*.image'     => 'File harus berupa gambar.',
            'foto.*.mimes'     => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.*.max'       => 'Ukuran tiap foto maksimal 15 MB.',
        ]);

        if ($request->hasFile('foto')) {
            // Foto baru menggantikan foto lama
            foreach ($laporan->fotos as $lama) {
                Storage::disk('public')->delete($lama->path);
            }
            $laporan->fotos()->delete();

            foreach ($request->file('foto') as $file) {
                $laporan->fotos()->create(['path' => FotoKompres::simpan($file)]);
            }
        }

        $ulang = $laporan->status === 'revisi';

        $laporan->update([
            'status'     => 'menunggu',
            'dikirim_at' => now(),
            'catatan'    => $request->input('catatan'),
        ]);

        $laporan->catat($ulang ? 'dikirim_ulang' : 'dikirim', $request->input('catatan'), auth()->id());

        return redirect()->route('petugas.laporan.show', $laporan)
            ->with('ok', 'Laporan berhasil dikirim dan menunggu verifikasi admin.');
    }

    public function pengaturan()
    {
        return view('pages.pengaturan', ['user' => auth()->user()]);
    }
}