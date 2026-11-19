@extends('layouts.app')
@section('title', 'Detail Laporan')

@section('content')
    <p class="muted"><a class="link" href="{{ route('admin.verifikasi') }}">&larr; Verifikasi</a></p>
    <h2 class="page">{{ $laporan->tugas->judul }}</h2>
    <p class="sub">{{ $laporan->tugas->deskripsi ?: 'Tidak ada deskripsi tugas.' }}</p>

    <div class="panel">
        <h3>Informasi</h3>
        <div class="info">
            <dl>
                <dt>Petugas</dt><dd>{{ $laporan->user->name }} ({{ $laporan->user->labelJabatan() }})</dd>
                <dt>Status</dt><dd>@include('partials.badge', ['value' => $laporan->status])</dd>
                <dt>Tugas diterima</dt><dd>@waktu($laporan->diterima_at)</dd>
                <dt>Batas waktu</dt><dd>@waktu($laporan->tugas->batas_waktu)</dd>
                <dt>Laporan dikirim</dt><dd>@waktu($laporan->dikirim_at)</dd>
                <dt>Jumlah revisi</dt><dd>{{ $laporan->jumlah_revisi }}</dd>
            </dl>
            @if ($laporan->catatan)
                <p><span class="muted">Catatan petugas:</span><br>{{ $laporan->catatan }}</p>
            @endif
        </div>
    </div>

    @include('partials.foto')

    @if ($laporan->status === 'disetujui')
        @include('partials.nilai')
    @endif

    @if ($laporan->status === 'revisi')
        <div class="alert warn" style="margin-top:20px">
            Menunggu revisi dari petugas. Catatan admin: {{ $laporan->catatan_admin }}
        </div>
    @endif

    @if ($laporan->status === 'menunggu')
        <div class="panel">
            <h3>Penilaian</h3>
            <form class="form" method="POST" action="{{ route('admin.laporan.nilai', $laporan) }}">
                @csrf
                <p class="muted">Isi nilai 0-100 tiap kriteria jika laporan disetujui. Nilai akhir dihitung otomatis sesuai bobot.</p>

                <div class="nilai-grid">
                    @foreach (\App\Models\Laporan::KRITERIA as $kunci => [$label, $bobot])
                        <div>
                            <label for="nilai_{{ $kunci }}">{{ $label }} ({{ $bobot }}%)</label>
                            <input type="number" min="0" max="100" id="nilai_{{ $kunci }}" name="nilai_{{ $kunci }}" value="{{ old('nilai_'.$kunci) }}">
                        </div>
                    @endforeach
                </div>

                <label for="catatan_admin">Catatan untuk petugas (wajib jika meminta revisi)</label>
                <textarea id="catatan_admin" name="catatan_admin">{{ old('catatan_admin') }}</textarea>

                <div class="actions">
                    <button class="btn" type="submit" name="aksi" value="setujui">Setujui dan Beri Nilai</button>
                    <button class="btn btn-dark" type="submit" name="aksi" value="revisi">Minta Revisi</button>
                </div>
            </form>
        </div>
    @endif

    @include('partials.riwayat')
@endsection