<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class LaporanFoto extends Model
{
    protected $table = 'laporan_foto';

    protected $fillable = ['laporan_id', 'path'];

    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->path));
    }
}