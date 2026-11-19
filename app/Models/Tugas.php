<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = ['judul', 'deskripsi', 'jabatan', 'user_id', 'batas_waktu', 'dibuat_oleh'];

    public const JABATAN = [
        'satpam'     => 'Satpam',
        'pramubakti' => 'Pramubakti (OB)',
    ];

    protected function casts(): array
    {
        return ['batas_waktu' => 'datetime'];
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    // Tugas yang boleh dilihat petugas: sesuai jabatan, dan umum atau khusus untuk dia
    public function scopeUntuk(Builder $query, User $user): Builder
    {
        return $query->where('jabatan', $user->jabatan)
            ->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $user->id));
    }

    public function labelJabatan(): string
    {
        return self::JABATAN[$this->jabatan] ?? '-';
    }

    public function ditujukan(): string
    {
        return $this->petugas?->name ?? 'Semua '.$this->labelJabatan();
    }
}