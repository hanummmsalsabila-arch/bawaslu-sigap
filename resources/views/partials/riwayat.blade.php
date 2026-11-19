@php
    $labelAksi = [
        'diterima'      => 'Tugas diterima',
        'dikirim'       => 'Laporan dikirim',
        'dikirim_ulang' => 'Laporan dikirim ulang',
        'revisi'        => 'Diminta revisi',
        'disetujui'     => 'Disetujui dan dinilai',
    ];
@endphp
<div class="panel">
    <h3>Riwayat dan update</h3>
    <ul class="timeline">
        @foreach ($laporan->riwayat as $r)
            <li class="t-{{ $r->aksi }}">
                <strong>{{ $labelAksi[$r->aksi] ?? $r->aksi }}</strong>
                @if ($r->user) <span class="muted">oleh {{ $r->user->name }}</span> @endif
                <span class="muted">@waktu($r->created_at)</span>
                @if ($r->catatan) <p>{{ $r->catatan }}</p> @endif
            </li>
        @endforeach
    </ul>
</div>