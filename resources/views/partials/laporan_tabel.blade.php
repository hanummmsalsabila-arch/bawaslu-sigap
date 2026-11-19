@php
    $adminLink    = $adminLink ?? true;
    $kolomPetugas = $kolomPetugas ?? true;
    $kolomTugas   = $kolomTugas ?? true;
    $span         = 4 + (int) $kolomPetugas + (int) $kolomTugas;
@endphp
<div class="scroll">
    <table>
        <thead>
            <tr>
                @if ($kolomPetugas)<th>Petugas</th>@endif
                @if ($kolomTugas)<th>Tugas</th>@endif
                <th>Terakhir update</th>
                <th>Status</th>
                <th>Nilai</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $l)
                <tr>
                    @if ($kolomPetugas)
                        <td>{{ $l->user->name }}<div class="muted">{{ $l->user->labelJabatan() }}</div></td>
                    @endif
                    @if ($kolomTugas)
                        <td>{{ $l->tugas->judul }}</td>
                    @endif
                    <td>@waktu($l->dikirim_at ?? $l->diterima_at)</td>
                    <td>@include('partials.badge', ['value' => $l->status])</td>
                    <td>{{ $l->nilai_total !== null ? number_format($l->nilai_total, 1) : '-' }}</td>
                    <td>
                        <a class="link" href="{{ $adminLink ? route('admin.laporan.show', $l) : route('petugas.laporan.show', $l) }}">Buka</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ $span }}" class="empty">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>