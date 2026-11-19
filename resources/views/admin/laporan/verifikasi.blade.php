@extends('layouts.app')
@section('title', 'Verifikasi & Penilaian')

@section('content')
    <h2 class="page">Verifikasi dan Penilaian</h2>
    <p class="sub">Laporan yang menunggu dinilai, urut dari yang paling lama.</p>

    <div class="panel">
        @include('partials.laporan_tabel', ['rows' => $laporan, 'adminLink' => true])
    </div>
@endsection