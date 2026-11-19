@extends('layouts.app')
@section('title', $tugas->judul)

@section('content')
    <p class="muted"><a class="link" href="{{ route('admin.tugas') }}">&larr; Semua Tugas</a></p>
    <h2 class="page">{{ $tugas->judul }}</h2>
    <p class="sub">{{ $tugas->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

    <div class="panel">
        <h3>Informasi tugas</h3>
        <div class="info">
            <dl>
                <dt>Jabatan</dt><dd>{{ $tugas->labelJabatan() }}</dd>
                <dt>Ditujukan</dt><dd>{{ $tugas->ditujukan() }}</dd>
                <dt>Batas waktu</dt><dd>@waktu($tugas->batas_waktu)</dd>
                <dt>Dibuat</dt><dd>@waktu($tugas->created_at)</dd>
            </dl>
        </div>
    </div>

    <div class="panel">
        <h3>Laporan petugas</h3>
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => true, 'kolomTugas' => false])
    </div>
@endsection