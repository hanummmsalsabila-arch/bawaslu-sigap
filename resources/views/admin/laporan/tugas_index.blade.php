@extends('layouts.app')
@section('title', $judul)

@section('content')
    <h2 class="page">{{ $judul }}</h2>
    <p class="sub">{{ $jabatan ? 'Tugas dan petugas untuk jabatan ini.' : 'Seluruh tugas dari semua jabatan.' }}</p>

    <div class="toolbar">
        <a class="btn" href="{{ route('admin.tugas.buat') }}">+ Buat Tugas</a>
    </div>

    <div class="panel">
        <h3>Daftar tugas</h3>
        <div class="scroll">
            <table>
                <thead>
                    <tr>
                        <th>Tugas</th><th>Jabatan</th><th>Ditujukan</th><th>Batas waktu</th>
                        <th>Laporan</th><th>Menunggu</th><th>Disetujui</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugas as $t)
                        <tr>
                            <td><a class="link" href="{{ route('admin.tugas.show', $t) }}">{{ $t->judul }}</a></td>
                            <td>{{ $t->labelJabatan() }}</td>
                            <td>{{ $t->ditujukan() }}</td>
                            <td>@waktu($t->batas_waktu)</td>
                            <td>{{ $t->laporan_count }}</td>
                            <td>{{ $t->menunggu_count }}</td>
                            <td>{{ $t->disetujui_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada tugas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($jabatan)
        <div class="panel">
            <h3>Petugas {{ \App\Models\Tugas::JABATAN[$jabatan] }}</h3>
            <div class="scroll">
                <table>
                    <thead>
                        <tr><th>Nama</th><th>Email</th><th>Jumlah laporan</th><th>Rata-rata nilai</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse ($petugas as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->laporan_count }}</td>
                                <td>{{ $p->rata_nilai !== null ? number_format($p->rata_nilai, 1) : '-' }}</td>
                                <td><a class="link" href="{{ route('admin.akun.show', $p) }}">Lihat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty">Belum ada petugas di jabatan ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection