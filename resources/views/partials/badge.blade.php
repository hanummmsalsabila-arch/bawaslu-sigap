@php
    $peta = [
        'diterima'  => ['Dikerjakan', 'b-info'],
        'menunggu'  => ['Menunggu verifikasi', 'b-wait'],
        'revisi'    => ['Revisi', 'b-rev'],
        'disetujui' => ['Disetujui', 'b-ok'],
    ];
    [$label, $kelas] = $peta[$value] ?? [ucfirst($value), 'b-wait'];
@endphp
<span class="badge {{ $kelas }}">{{ $label }}</span>