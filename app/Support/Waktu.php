<?php

namespace App\Support;

use Carbon\Carbon;

class Waktu
{
    public static function tampil($waktu): string
    {
        if (! $waktu) {
            return '-';
        }

        return Carbon::parse($waktu)->locale('id')->translatedFormat('l, d M Y H:i').' WIB';
    }
}