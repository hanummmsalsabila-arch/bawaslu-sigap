<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            // email, nama, role, jabatan
            ['admin@sigap.test', 'Admin SIGAP',   'admin', null],
            ['sasa@sigap.test',  'sasa',          'user',  'pramubakti'],
            ['budi@sigap.test',  'Budi Santoso',  'user',  'satpam'],
        ];

        foreach ($akun as [$email, $nama, $role, $jabatan]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name'     => $nama,
                    'password' => 'password123', // di-hash otomatis oleh cast model
                    'role'     => $role,
                    'jabatan'  => $jabatan,
                ]
            );
        }
    }
}