@extends('layouts.app')

@section('title', 'Detail Laporan - SIGAP Bawaslu')

@section('content')
<style>
    .lap-back { display: inline-block; margin-bottom: 16px; color: #b4202a; font-weight: 600; text-decoration: none; }
    .lap-back:hover { text-decoration: underline; }
    .lap-head h1 { margin: 0 0 6px; font-size: 32px; font-weight: 700; color: #1f2430; }
    .lap-head p { margin: 0 0 24px; color: #6b7280; font-size: 17px; }
    .lap-card { background: #fff; border: 1px solid #e6e8ec; border-radius: 12px; max-width: 860px; }
    .lap-row { display: grid; grid-template-columns: 220px 1fr; gap: 16px; padding: 16px 24px; border-bottom: 1px solid #eef0f3; }
    .lap-row:last-child { border-bottom: 0; }
    .lap-row dt { font-size: 14px; font-weight: 600; color: #6b7280; }
    .lap-row dd { margin: 0; font-size: 15px; color: #1f2430; line-height: 1.6; }
    .lap-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 600; }
    .lap-badge--wait { background: #fdf3d7; color: #8a5a00; }
    .lap-badge--ok { background: #e3f4ea; color: #1d7a46; }
    .lap-badge--bad { background: #fbe3e3; color: #b4202a; }
    .lap-link { color: #b4202a; font-weight: 600; text-decoration: none; }
    .lap-link:hover { text-decoration: underline; }
    @media (max-width: 640px) { .lap-row { grid-template-columns: 1fr; gap: 4px; } }
</style>

@php
    $s = strtolower((string) $laporan->status_verifikasi);
    $badge = str_contains($s, 'setuju') ? 'ok' : (str_contains($s, 'revisi') ? 'bad' : 'wait');

    $rows = [
        'Petugas'              => $laporan->nama_lengkap ?? '-',
        'Kode petugas'         => $laporan->kode_petugas ?? '-',
        'Kategori'             => $laporan->kategori,
        'Area tanggung jawab'  => $laporan->area_tanggung_jawab ?? '-',
        'Jadwal shift'         => $laporan->jadwal_shift ?? '-',
        'No. HP'               => $laporan->no_hp ?? '-',
        'Tanggal kegiatan'     => \Carbon\Carbon::parse($laporan->tanggal_kegiatan)->translatedFormat('d F Y'),
        'Jam'                  => substr((string) $laporan->jam_mulai, 0, 5) . ' - ' . substr((string) $laporan->jam_selesai, 0, 5),
        'Jenis pekerjaan'      => $laporan->jenis_pekerjaan,
        'Lokasi pekerjaan'     => $laporan->lokasi_pekerjaan,
    ];

    $linkValid = $laporan->gdrive_view_link && preg_match('#^https?://#i', $laporan->gdrive_view_link);
@endphp

<a class="lap-back" href="{{ route('admin.laporan.index') }}">&larr; Kembali ke semua laporan</a>

<div class="lap-head">
    <h1>{{ $laporan->kode_laporan }}</h1>
    <p>Laporan harian {{ $laporan->nama_lengkap ?? '-' }}</p>
</div>

<dl class="lap-card" style="margin-top:0;margin-bottom:0;">
    @foreach ($rows as $label => $value)
        <div class="lap-row">
            <dt>{{ $label }}</dt>
            <dd>{{ $value }}</dd>
        </div>
    @endforeach

    <div class="lap-row">
        <dt>Uraian pekerjaan</dt>
        <dd>{!! nl2br(e($laporan->uraian_pekerjaan)) !!}</dd>
    </div>

    <div class="lap-row">
        <dt>Status penyelesaian</dt>
        <dd>{{ $laporan->status_penyelesaian }}</dd>
    </div>

    <div class="lap-row">
        <dt>Status verifikasi</dt>
        <dd><span class="lap-badge lap-badge--{{ $badge }}">{{ $laporan->status_verifikasi }}</span></dd>
    </div>

    <div class="lap-row">
        <dt>Bukti (Google Drive)</dt>
        <dd>
            @if ($linkValid)
                <a class="lap-link" href="{{ $laporan->gdrive_view_link }}" target="_blank" rel="noopener noreferrer">Buka bukti</a>
            @else
                Belum ada bukti diunggah
            @endif
        </dd>
    </div>
</dl>
@endsection