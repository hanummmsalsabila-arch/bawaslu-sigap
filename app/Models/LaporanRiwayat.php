<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanRiwayat extends Model
{
    protected $table = 'laporan_riwayat';

    protected $fillable = ['laporan_id', 'user_id', 'aksi', 'catatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}