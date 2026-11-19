@extends('layouts.app')
@section('title', 'Detail Laporan')

@section('content')
    <p class="muted"><a class="link" href="{{ route('petugas.laporan') }}">&larr; Laporan Saya</a></p>
    <h2 class="page">{{ $laporan->tugas->judul }}</h2>
    <p class="sub">{{ $laporan->tugas->deskripsi ?: 'Tidak ada deskripsi tugas.' }}</p>

    @if ($laporan->status === 'revisi')
        <div class="alert err">
            <strong>Perlu revisi.</strong> {{ $laporan->catatan_admin }}
        </div>
    @endif

    <div class="panel">
        <h3>Informasi</h3>
        <div class="info">
            <dl>
                <dt>Status</dt><dd>@include('partials.badge', ['value' => $laporan->status])</dd>
                <dt>Tugas diterima</dt><dd>@waktu($laporan->diterima_at)</dd>
                <dt>Batas waktu</dt><dd>@waktu($laporan->tugas->batas_waktu)</dd>
                <dt>Laporan dikirim</dt><dd>@waktu($laporan->dikirim_at)</dd>
                <dt>Jumlah revisi</dt><dd>{{ $laporan->jumlah_revisi }}</dd>
            </dl>
        </div>
    </div>

    @if ($laporan->fotos->isNotEmpty())
        @include('partials.foto')
    @endif

    @if ($laporan->status === 'disetujui')
        @include('partials.nilai')
    @endif

    @if ($laporan->bisaDikirim())
        <div class="panel">
            <h3>{{ $laporan->status === 'revisi' ? 'Kirim ulang laporan' : 'Kirim laporan' }}</h3>
            <form class="form" method="POST" action="{{ route('petugas.kirim', $laporan) }}" enctype="multipart/form-data">
                @csrf

                <label for="foto">
                    Foto bukti pekerjaan (maks. 5, otomatis dikompres dan diberi hari + jam)
                </label>
                <input type="file" id="foto" name="foto[]" accept="image/*" multiple>
                @if ($laporan->fotos->isNotEmpty())
                    <div class="muted">Kosongkan jika ingin memakai foto yang sudah ada. Foto baru akan menggantikan yang lama.</div>
                @endif

                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" name="catatan">{{ old('catatan', $laporan->catatan) }}</textarea>

                <label class="check" style="margin-top:18px">
                    <input type="checkbox" name="selesai" value="1" required>
                    Tugas ini sudah selesai dikerjakan
                </label>

                <div class="actions">
                    <button class="btn" type="submit">Kirim Laporan</button>
                </div>
            </form>
        </div>
    @endif

    @include('partials.riwayat')
@endsection