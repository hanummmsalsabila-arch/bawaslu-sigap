<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $guarded = [];

    // kunci => [label, bobot %]
    public const KRITERIA = [
        'kualitas'       => ['Kualitas pekerjaan', 30],
        'kehadiran'      => ['Kehadiran', 20],
        'kuantitas'      => ['Kuantitas pekerjaan', 20],
        'ketepatan'      => ['Ketepatan waktu', 15],
        'tanggung_jawab' => ['Tanggung jawab', 15],
    ];

    protected function casts(): array
    {
        return [
            'diterima_at' => 'datetime',
            'dikirim_at'  => 'datetime',
            'dinilai_at'  => 'datetime',
            'nilai_total' => 'float',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(LaporanFoto::class);
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(LaporanRiwayat::class)->orderBy('id');
    }

    public function bisaDikirim(): bool
    {
        return in_array($this->status, ['diterima', 'revisi'], true);
    }

    public function catat(string $aksi, ?string $catatan = null, ?int $userId = null): void
    {
        $this->riwayat()->create([
            'aksi'    => $aksi,
            'catatan' => $catatan,
            'user_id' => $userId,
        ]);
    }

    public function predikat(): string
    {
        $n = $this->nilai_total;

        return match (true) {
            $n === null => '-',
            $n >= 90    => 'Sangat Baik',
            $n >= 80    => 'Baik',
            $n >= 70    => 'Cukup',
            default     => 'Perlu Pembinaan',
        };
    }
}