<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenagaPendukung extends Model
{
    protected $table = 'tenaga_pendukung';
    protected $guarded = ['id'];

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'tenaga_pendukung_id');
    }
}