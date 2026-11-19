@extends('layouts.app')
@section('title', $user->name)

@section('content')
    <p class="muted"><a class="link" href="{{ route('admin.akun') }}">&larr; Semua Akun</a></p>
    <h2 class="page">{{ $user->name }}</h2>
    <p class="sub">{{ $user->labelJabatan() }} - {{ $user->email }}</p>

    <div class="stats">
        <div class="stat"><div class="n">{{ $laporan->count() }}</div><div class="l">Total tugas diterima</div></div>
        <div class="stat"><div class="n">{{ $laporan->where('status', 'disetujui')->count() }}</div><div class="l">Disetujui</div></div>
        <div class="stat"><div class="n">{{ $rata !== null ? number_format($rata, 1) : '-' }}</div><div class="l">Rata-rata nilai</div></div>
    </div>

    <div class="panel">
        <h3>Semua tugas dan laporan</h3>
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => true, 'kolomPetugas' => false])
    </div>
@endsection