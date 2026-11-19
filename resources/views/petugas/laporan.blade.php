@extends('layouts.app')
@section('title', 'Laporan Saya')

@section('content')
    <h2 class="page">Laporan Saya</h2>
    <p class="sub">Semua tugas yang sudah kamu terima beserta statusnya.</p>

    <div class="panel">
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => false, 'kolomPetugas' => false])
    </div>
@endsection