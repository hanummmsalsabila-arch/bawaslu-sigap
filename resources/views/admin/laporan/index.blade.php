@extends('layouts.app')

@section('title', 'Semua Laporan - SIGAP Bawaslu')

@section('content')
<style>
    .lap-head h1 { margin: 0 0 6px; font-size: 32px; font-weight: 700; color: #1f2430; }
    .lap-head p { margin: 0 0 24px; color: #6b7280; font-size: 17px; }
    .lap-card { background: #fff; border: 1px solid #e6e8ec; border-radius: 12px; }
    .lap-filter { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; padding: 18px 20px; margin-bottom: 20px; }
    .lap-field { display: flex; flex-direction: column; gap: 6px; }
    .lap-field label { font-size: 13px; font-weight: 600; color: #374151; }
    .lap-field input, .lap-field select { height: 40px; padding: 0 12px; border: 1px solid #d5d9e0; border-radius: 8px; background: #fff; font: inherit; font-size: 14px; color: #1f2430; }
    .lap-field--grow { flex: 1 1 220px; }
    .lap-field--grow input { width: 100%; box-sizing: border-box; }
    .lap-btn { display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 18px; border-radius: 8px; border: 1px solid #b4202a; background: #b4202a; color: #fff; font: inherit; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; }
    .lap-btn--ghost { background: #fff; color: #1f2430; border-color: #d5d9e0; }
    .lap-btn--off { background: #f3f4f6; color: #9ca3af; border-color: #e5e7eb; cursor: default; }
    .lap-table-wrap { overflow-x: auto; }
    .lap-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .lap-table th { text-align: left; padding: 16px 20px; font-size: 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e6e8ec; white-space: nowrap; }
    .lap-table td { padding: 16px 20px; font-size: 15px; color: #1f2430; border-bottom: 1px solid #eef0f3; vertical-align: top; }
    .lap-table tr:last-child td { border-bottom: 0; }
    .lap-sub { display: block; margin-top: 2px; font-size: 13px; color: #6b7280; }
    .lap-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .lap-badge--wait { background: #fdf3d7; color: #8a5a00; }
    .lap-badge--ok { background: #e3f4ea; color: #1d7a46; }
    .lap-badge--bad { background: #fbe3e3; color: #b4202a; }
    .lap-link { color: #b4202a; font-weight: 600; text-decoration: none; }
    .lap-link:hover { text-decoration: underline; }
    .lap-empty { padding: 48px 20px; text-align: center; color: #6b7280; }
    .lap-pager { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; padding: 16px 20px; border-top: 1px solid #e6e8ec; font-size: 14px; color: #6b7280; }
    .lap-pager-nav { display: flex; gap: 8px; }
</style>

@php
    $badgeClass = function ($status) {
        $s = strtolower((string) $status);
        if (str_contains($s, 'setuju')) return 'ok';
        if (str_contains($s, 'revisi')) return 'bad';
        return 'wait';
    };
@endphp

<div class="lap-head">
    <h1>Semua Laporan</h1>
    <p>Seluruh laporan harian tenaga pendukung.</p>
</div>

<form method="GET" action="{{ route('admin.laporan.index') }}" class="lap-card lap-filter">
    <div class="lap-field lap-field--grow">
        <label for="q">Cari</label>
        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Kode, nama, pekerjaan, atau lokasi">
    </div>

    <div class="lap-field">
        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori">
            <option value="">Semua</option>
            @foreach ($kategoriList as $k)
                <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
            @endforeach
        </select>
    </div>

    <div class="lap-field">
        <label for="status">Status verifikasi</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            @foreach ($statusList as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="lap-field">
        <label for="dari">Dari tanggal</label>
        <input type="date" id="dari" name="dari" value="{{ request('dari') }}">
    </div>

    <div class="lap-field">
        <label for="sampai">Sampai tanggal</label>
        <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}">
    </div>

    <button type="submit" class="lap-btn">Terapkan</button>
    <a href="{{ route('admin.laporan.index') }}" class="lap-btn lap-btn--ghost">Reset</a>
</form>

<div class="lap-card">
    <div class="lap-table-wrap">
        <table class="lap-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Penyelesaian</th>
                    <th>Verifikasi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $r)
                    <tr>
                        <td>{{ $r->kode_laporan }}</td>
                        <td>
                            {{ $r->nama_lengkap ?? '-' }}
                            <span class="lap-sub">{{ $r->kategori }}</span>
                        </td>
                        <td>
                            {{ $r->jenis_pekerjaan }}
                            <span class="lap-sub">{{ $r->lokasi_pekerjaan }}</span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($r->tanggal_kegiatan)->translatedFormat('d M Y') }}
                            <span class="lap-sub">{{ substr((string) $r->jam_mulai, 0, 5) }} - {{ substr((string) $r->jam_selesai, 0, 5) }}</span>
                        </td>
                        <td>{{ $r->status_penyelesaian }}</td>
                        <td>
                            <span class="lap-badge lap-badge--{{ $badgeClass($r->status_verifikasi) }}">{{ $r->status_verifikasi }}</span>
                        </td>
                        <td><a class="lap-link" href="{{ route('admin.laporan.show', $r->id) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="lap-empty">Belum ada laporan yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="lap-pager">
        <span>Halaman {{ $laporan->currentPage() }} dari {{ $laporan->lastPage() }} · {{ $laporan->total() }} laporan</span>
        <div class="lap-pager-nav">
            @if ($laporan->onFirstPage())
                <span class="lap-btn lap-btn--off">Sebelumnya</span>
            @else
                <a class="lap-btn lap-btn--ghost" href="{{ $laporan->previousPageUrl() }}">Sebelumnya</a>
            @endif

            @if ($laporan->hasMorePages())
                <a class="lap-btn lap-btn--ghost" href="{{ $laporan->nextPageUrl() }}">Berikutnya</a>
            @else
                <span class="lap-btn lap-btn--off">Berikutnya</span>
            @endif
        </div>
    </div>
</div>
@endsection