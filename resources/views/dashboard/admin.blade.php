@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <h2 class="page">Halo, {{ auth()->user()->name }}</h2>
    <p class="sub">Ringkasan laporan harian tenaga pendukung hari ini.</p>

    <div class="stats">
        @foreach ($stats as $s)
            <div class="stat">
                <div class="n {{ $s['merah'] ? 'merah' : '' }}">{{ $s['angka'] }}</div>
                <div class="l">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <h3>Laporan terbaru</h3>
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => true])
    </div>
@endsection