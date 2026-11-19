@extends('layouts.app')
@section('title', 'Tugas Tersedia')

@section('content')
    <h2 class="page">Tugas Tersedia</h2>
    <p class="sub">Tugas untuk {{ auth()->user()->labelJabatan() }}. Terima tugas lalu kirim laporan setelah selesai.</p>

    @if (! auth()->user()->jabatan)
        <div class="alert warn">Akun kamu belum punya jabatan. Hubungi admin.</div>
    @endif

    <div class="panel">
        <div class="scroll">
            <table>
                <thead>
                    <tr><th>Tugas</th><th>Batas waktu</th><th>Dibuat</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($tugas as $t)
                        <tr>
                            <td>
                                <strong>{{ $t->judul }}</strong>
                                @if ($t->deskripsi)<div class="muted">{{ $t->deskripsi }}</div>@endif
                            </td>
                            <td>@waktu($t->batas_waktu)</td>
                            <td>@waktu($t->created_at)</td>
                            <td>
                                <form method="POST" action="{{ route('petugas.terima', $t) }}">
                                    @csrf
                                    <button class="btn btn-sm" type="submit">Terima Tugas</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">Belum ada tugas baru untuk kamu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection