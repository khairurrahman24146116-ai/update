<?php

namespace Database\Seeders;

use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class LandingSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'hero' => [
                'Membina Generasi Qurani Berwawasan Sains Global dan Berakhlak Mulia.',
                'Pendidikan menengah atas berbasis pesantren yang memadukan kurikulum nasional dengan pembinaan tahfizhul Qur\'an, penguatan sains dan teknologi, serta pembiasaan aktif berbahasa Arab dan Inggris dalam kehidupan sehari-hari santri.',
            ],
            'profil' => [
                'Trilogi Kurikulum SMA Madani Al Aziziyah',
                'Perpaduan kedalaman ilmu-ilmu syar\'i, ketajaman nalar sains modern, dan kemandirian kepemimpinan santri.',
            ],
            'kepsek' => [
                'Kepemimpinan Berintegritas',
                'Pengasuh Dayah Madani Al-Aziziyah: Dr. Tgk. H. Muhammad Hatta, Lc., M.Ed. (Abiya Hatta).',
            ],
            'akademik' => [
                'Pendidikan Terpadu Pesantren & Formal',
                'Pembelajaran formal mengikuti kurikulum nasional, dipadukan dengan pengajian kitab kuning khas dayah salafiyah serta pembinaan bahasa Arab dan Inggris.',
            ],
        ] as $k => [$title, $body]) {
            LandingSection::updateOrCreate(['key' => $k], [
                'title' => $title,
                'body' => $body,
                'order' => array_search($k, ['hero', 'profil', 'kepsek', 'akademik']),
                'is_visible' => true,
            ]);
        }
    }
}
