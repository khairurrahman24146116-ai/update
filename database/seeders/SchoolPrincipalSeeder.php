<?php

namespace Database\Seeders;

use App\Models\SchoolPrincipal;
use Illuminate\Database\Seeder;

class SchoolPrincipalSeeder extends Seeder
{
    public function run(): void
    {
        SchoolPrincipal::updateOrCreate(['name' => 'Dr. Tgk. H. Muhammad Hatta, Lc., M.Ed. (Abiya Hatta)'], [
            'title' => 'PENGASUH DAYAH MADANI AL-AZIZIYAH',
            'photo_path' => '/images/profile/4.png',
            'is_current' => true,
        ]);

        SchoolPrincipal::updateOrCreate(['name' => 'Fahmi, M.Pd.'], [
            'title' => 'KEPALA SEKOLAH & SEKRETARIS',
            'photo_path' => '/images/profile/3.png',
            'is_current' => true,
        ]);
    }
}
