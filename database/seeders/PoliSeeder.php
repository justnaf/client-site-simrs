<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricePoli; // 1. Import model PricePoli Anda

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Lakukan loop untuk setiap id_poli dari 102 hingga 114
        for ($i = 102; $i <= 114; $i++) {

            // 3. Gunakan updateOrCreate untuk membuat atau memperbarui data
            PricePoli::updateOrCreate(
                [
                    'id_poli' => $i // Kunci unik untuk mencari data
                ],
                [
                    'price' => rand(2, 10) * 5000,
                    'desc' => 'Biaya Poli'
                ]
            );
        }
    }
}
