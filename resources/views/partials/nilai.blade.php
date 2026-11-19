<div class="panel">
    <h3>Penilaian</h3>
    <div class="info">
        <dl>
            @foreach (\App\Models\Laporan::KRITERIA as $kunci => [$label, $bobot])
                <dt>{{ $label }} ({{ $bobot }}%)</dt>
                <dd>{{ $laporan->{'nilai_'.$kunci} }}</dd>
            @endforeach
            <dt>Nilai akhir</dt>
            <dd>{{ number_format($laporan->nilai_total, 1) }} - {{ $laporan->predikat() }}</dd>
            <dt>Dinilai pada</dt>
            <dd>@waktu($laporan->dinilai_at)</dd>
        </dl>
        @if ($laporan->catatan_admin)
            <p><span class="muted">Catatan admin:</span><br>{{ $laporan->catatan_admin }}</p>
        @endif
    </div>
</div>