@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <h2 class="page">Halo, {{ auth()->user()->name }}</h2>
    <p class="sub">{{ auth()->user()->labelJabatan() }} - ringkasan tugas dan laporan kamu.</p>

    @if ($perluRevisi > 0)
        <div class="alert warn">
            Ada {{ $perluRevisi }} laporan yang perlu direvisi.
            <a href="{{ route('petugas.laporan') }}">Lihat sekarang</a>
        </div>
    @endif

    <div class="stats">
        @foreach ($stats as $s)
            <div class="stat">
                <div class="n {{ $s['merah'] ? 'merah' : '' }}">{{ $s['angka'] }}</div>
                <div class="l">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <h3>Aktivitas terakhir</h3>
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => false, 'kolomPetugas' => false])
    </div>
@endsection