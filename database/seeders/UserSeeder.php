<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sigap.test'],
            [
                'name'     => 'Admin SIGAP',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sasa@sigap.test'],
            [
                'name'     => 'sasa',
                'password' => Hash::make('password123'),
                'role'     => 'user',
            ]
        );
    }
}