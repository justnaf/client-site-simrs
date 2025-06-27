<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Registration; // 1. Import model Registration Anda
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3. Daftar no_registrasi dari data sampel Anda sebelumnya
        $registrationNumbers = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            29,
            30,
            31,
            32
        ];

        foreach ($registrationNumbers as $regNumber) {
            Registration::updateOrCreate(
                [
                    'no_registrasi' => $regNumber // Kunci unik untuk mencari data
                ],
                [
                    // Data yang akan dibuat atau di-update
                    'unicode' => 'OFF-' . strtoupper(Str::random(8)), // 'OFF-' ditambah 8 karakter acak kapital
                    'payment_type_id' => 5 // Nilai statis sesuai permintaan
                ]
            );
        }
    }
}
