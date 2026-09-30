<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['name' => 'Pendidikan Agama dan Budi Pekerti', 'code' => 'PABP'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKN'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN'],
            ['name' => 'Matematika Wajib', 'code' => 'MTK-W'],
            ['name' => 'Sejarah Indonesia', 'code' => 'SEJ'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG'],
            ['name' => 'Seni Budaya', 'code' => 'SBK'],
            ['name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'code' => 'PJOK'],
            ['name' => 'Prakarya dan Kewirausahaan', 'code' => 'PKWU'],
            ['name' => 'Fisika', 'code' => 'FIS'],
            ['name' => 'Kimia', 'code' => 'KIM'],
            ['name' => 'Biologi', 'code' => 'BIO'],
        ];
        foreach ($mapels as $m) {
            Subject::firstOrCreate(['code' => $m['code']], ['name' => $m['name']]);
        }
    }
}
