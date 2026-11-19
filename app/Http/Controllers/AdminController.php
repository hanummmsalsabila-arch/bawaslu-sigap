<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Semua tugas, atau per jabatan (satpam / pramubakti)
    public function tugas(?string $jabatan = null)
    {
        abort_if($jabatan !== null && ! isset(Tugas::JABATAN[$jabatan]), 404);

        $tugas = Tugas::with('petugas')
            ->withCount([
                'laporan',
                'laporan as menunggu_count'  => fn ($q) => $q->where('status', 'menunggu'),
                'laporan as disetujui_count' => fn ($q) => $q->where('status', 'disetujui'),
            ])
            ->when($jabatan, fn ($q) => $q->where('jabatan', $jabatan))
            ->latest()
            ->get();

        $petugas = $jabatan
            ? User::where('role', 'user')->where('jabatan', $jabatan)
                ->withCount('laporan')
                ->withAvg('laporan as rata_nilai', 'nilai_total')
                ->orderBy('name')->get()
            : collect();

        $judul = $jabatan ? 'Tugas '.Tugas::JABATAN[$jabatan] : 'Semua Tugas';

        return view('admin.tugas_index', compact('tugas', 'petugas', 'judul', 'jabatan'));
    }

    public function tugasBuat()
    {
        $petugas = User::where('role', 'user')->whereNotNull('jabatan')->orderBy('name')->get();

        return view('admin.tugas_buat', compact('petugas'));
    }

    public function tugasSimpan(Request $request)
    {
        $data = $request->validate([
            'judul'       => ['required', 'string', 'max:150'],
            'deskripsi'   => ['nullable', 'string', 'max:2000'],
            'jabatan'     => ['required', Rule::in(array_keys(Tugas::JABATAN))],
            'user_id'     => ['nullable', 'exists:users,id'],
            'batas_waktu' => ['nullable', 'date'],
        ]);

        if (! empty($data['user_id'])) {
            $petugas = User::find($data['user_id']);
            if ($petugas->jabatan !== $data['jabatan']) {
                return back()->withInput()
                    ->withErrors(['user_id' => 'Jabatan petugas tidak sama dengan jabatan tugas.']);
            }
        }

        $data['dibuat_oleh'] = auth()->id();
        Tugas::create($data);

        return redirect()->route('admin.tugas')->with('ok', 'Tugas berhasil dibuat.');
    }

    public function tugasShow(Tugas $tugas)
    {
        $tugas->load('petugas');
        $laporan = $tugas->laporan()->with('user')->latest('diterima_at')->get();

        return view('admin.tugas_show', compact('tugas', 'laporan'));
    }

    public function verifikasi()
    {
        $laporan = Laporan::with(['user', 'tugas'])
            ->where('status', 'menunggu')
            ->orderBy('dikirim_at')
            ->get();

        return view('admin.verifikasi', compact('laporan'));
    }

    public function laporanShow(Laporan $laporan)
    {
        $laporan->load(['tugas', 'user', 'fotos', 'riwayat.user']);

        return view('admin.laporan_show', compact('laporan'));
    }

    public function nilai(Request $request, Laporan $laporan)
    {
        abort_unless($laporan->status === 'menunggu', 403, 'Laporan ini tidak sedang menunggu verifikasi.');

        $rules = [
            'aksi'          => ['required', 'in:setujui,revisi'],
            'catatan_admin' => ['required_if:aksi,revisi', 'nullable', 'string', 'max:1000'],
        ];
        foreach (array_keys(Laporan::KRITERIA) as $kunci) {
            $rules["nilai_$kunci"] = ['required_if:aksi,setujui', 'nullable', 'integer', 'between:0,100'];
        }

        $request->validate($rules, [
            'required_if' => 'Kolom :attribute wajib diisi.',
            'between'     => 'Nilai harus antara 0 sampai 100.',
        ]);

        if ($request->aksi === 'revisi') {
            $laporan->update([
                'status'        => 'revisi',
                'catatan_admin' => $request->catatan_admin,
                'jumlah_revisi' => $laporan->jumlah_revisi + 1,
            ]);
            $laporan->catat('revisi', $request->catatan_admin, auth()->id());

            return redirect()->route('admin.verifikasi')->with('ok', 'Laporan dikembalikan ke petugas untuk revisi.');
        }

        $nilai = [];
        $total = 0;
        foreach (Laporan::KRITERIA as $kunci => [$label, $bobot]) {
            $n = (int) $request->input("nilai_$kunci");
            $nilai["nilai_$kunci"] = $n;
            $total += $n * $bobot / 100;
        }

        $laporan->update($nilai + [
            'status'        => 'disetujui',
            'nilai_total'   => round($total, 2),
            'catatan_admin' => $request->catatan_admin,
            'dinilai_at'    => now(),
        ]);
        $laporan->catat('disetujui', $request->catatan_admin, auth()->id());

        return redirect()->route('admin.verifikasi')->with('ok', 'Laporan disetujui dan dinilai.');
    }

    public function akun()
    {
        $akun = User::withCount('laporan')
            ->withAvg('laporan as rata_nilai', 'nilai_total')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.akun_index', compact('akun'));
    }

    public function akunShow(User $user)
    {
        $laporan = $user->laporan()->with('tugas')->latest('diterima_at')->get();
        $rata = $laporan->whereNotNull('nilai_total')->avg('nilai_total');

        return view('admin.akun_show', compact('user', 'laporan', 'rata'));
    }
}