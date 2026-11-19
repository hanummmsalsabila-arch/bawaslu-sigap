<?php

namespace Database\Seeders;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sigap.test')->first();

        $data = [
            ['Patroli area kantor pagi',   'Keliling area gedung dan foto kondisi gerbang.',        'satpam'],
            ['Cek CCTV dan buku tamu',     'Pastikan semua kamera menyala dan buku tamu terisi.',   'satpam'],
            ['Membersihkan ruang rapat',   'Bersihkan meja, kursi, dan lantai ruang rapat.',        'pramubakti'],
            ['Merapikan pantry dan arsip', 'Rapikan pantry dan susun arsip di rak.',                'pramubakti'],
        ];

        foreach ($data as [$judul, $deskripsi, $jabatan]) {
            Tugas::firstOrCreate(
                ['judul' => $judul],
                ['deskripsi' => $deskripsi, 'jabatan' => $jabatan, 'dibuat_oleh' => $admin?->id]
            );
        }
    }
}