@extends('layouts.app')
@section('title', 'Semua Akun')

@section('content')
    <h2 class="page">Semua Akun</h2>
    <p class="sub">Daftar admin dan petugas yang terdaftar di SIGAP.</p>

    <div class="panel">
        <div class="scroll">
            <table>
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Role</th><th>Jabatan</th><th>Laporan</th><th>Rata-rata nilai</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($akun as $a)
                        <tr>
                            <td>{{ $a->name }}</td>
                            <td>{{ $a->email }}</td>
                            <td>{{ $a->isAdmin() ? 'Admin' : 'Petugas' }}</td>
                            <td>{{ $a->labelJabatan() }}</td>
                            <td>{{ $a->laporan_count }}</td>
                            <td>{{ $a->rata_nilai !== null ? number_format($a->rata_nilai, 1) : '-' }}</td>
                            <td>
                                @unless ($a->isAdmin())
                                    <a class="link" href="{{ route('admin.akun.show', $a) }}">Lihat</a>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection