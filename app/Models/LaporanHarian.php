<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    protected $table = 'penilaian_kinerja';
    protected $guarded = ['id'];

    public function tenagaPendukung()
    {
        return $this->belongsTo(TenagaPendukung::class, 'tenaga_pendukung_id');
    }
}