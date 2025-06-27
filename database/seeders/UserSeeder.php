<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Kunci unik untuk mencari
            [
                'username' => 'admin',
                'name' => 'Admin User',
                'password' => Hash::make('password') // Ganti dengan password yang aman jika perlu
            ]
        );
        $admin->assignRole('admin');


        // 2. Buat User Pasien
        $pasien = User::firstOrCreate(
            ['email' => 'pasien@example.com'],
            [
                'username' => 'pasien',
                'name' => 'Pasien User',
                'password' => Hash::make('password')
            ]
        );
        $pasien->assignRole('pasien');


        // 3. Buat User API
        $apiUser = User::firstOrCreate(
            ['email' => 'api@example.com'],
            [
                'username' => 'apiuser',
                'name' => 'API User',
                'password' => Hash::make('password')
            ]
        );
        $apiUser->assignRole('api');
    }
}
