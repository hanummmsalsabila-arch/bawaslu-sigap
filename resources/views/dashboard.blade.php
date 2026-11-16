@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Halo, {{ $user->name }}</h1>

    @if ($isAdmin)
        <p class="sub">Ringkasan laporan harian tenaga pendukung hari ini.</p>

        <div class="ringkasan">
            <div class="panel">
                <div class="angka">{{ $ringkasan['masuk'] }}</div>
                <div class="label">Laporan masuk hari ini</div>
            </div>
            <div class="panel perhatian">
                <div class="angka">{{ $ringkasan['menunggu'] }}</div>
                <div class="label">Menunggu verifikasi</div>
            </div>
            <div class="panel">
                <div class="angka">{{ $ringkasan['revisi'] }}</div>
                <div class="label">Perlu revisi</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-judul">Laporan terbaru</div>
            <div class="tabel-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanTerbaru as $laporan)
                            @php
                                $kelas = ['Menunggu' => 'warn', 'Disetujui' => 'ok', 'Revisi' => 'bad'][$laporan['status']] ?? 'warn';
                            @endphp
                            <tr>
                                <td>{{ $laporan['nama'] }}</td>
                                <td>{{ $laporan['kegiatan'] }}</td>
                                <td>{{ $laporan['tanggal'] }}</td>
                                <td><span class="badge {{ $kelas }}">{{ $laporan['status'] }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada laporan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="sub">Pastikan laporan harian Anda sudah diunggah hari ini.</p>

        <div class="ringkasan">
            <div class="panel">
                <div class="angka">{{ $ringkasan['masuk'] }}</div>
                <div class="label">Laporan yang sudah Anda kirim</div>
            </div>
            <div class="panel">
                <div class="angka">{{ $ringkasan['menunggu'] }}</div>
                <div class="label">Menunggu verifikasi</div>
            </div>
            <div class="panel perhatian">
                <div class="angka">{{ $ringkasan['revisi'] }}</div>
                <div class="label">Perlu Anda revisi</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-judul">Laporan hari ini</div>
            <div class="panel-isi">
                <p style="margin-top:0">Anda belum mengunggah laporan harian untuk hari ini.</p>
                <a href="#" class="tombol">Upload laporan harian</a>
            </div>
        </div>
    @endif
@endsection
