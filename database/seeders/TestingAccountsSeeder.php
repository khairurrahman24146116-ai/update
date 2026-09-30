<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $roles = ['admin', 'guru', 'bendahara', 'wali_murid'];

        foreach ($roles as $role) {
            User::updateOrCreate(
                ['email' => "{$role}@madani.test"],
                [
                    'name' => ucfirst($role).' Test Account',
                    'password' => $password,
                    'role' => $role,
                    'is_active' => true,
                ]
            );
        }
        $guru = User::where('email', 'guru@madani.test')->first();
        $kelas = Classroom::first() ?? Classroom::create(['name' => 'X-1', 'grade' => 10]);
        $fisika = Subject::where('code', 'FIS')->first() ?? Subject::firstOrCreate(['code' => 'FIS'], ['name' => 'Fisika']);
        if ($guru && $kelas && $fisika) {
            TeacherSubject::firstOrCreate(['teacher_id' => $guru->id, 'classroom_id' => $kelas->id, 'subject_id' => $fisika->id]);
            Schedule::firstOrCreate(
                ['teacher_id' => $guru->id, 'classroom_id' => $kelas->id, 'subject_id' => $fisika->id, 'day_of_week' => 'mon'],
                ['start_time' => '08:00', 'end_time' => '09:30']
            );
            $nisnMap = ['Andi' => '0071234567', 'Budi' => '0071234568', 'Citra' => '0071234569'];
            foreach (['Andi', 'Budi', 'Citra'] as $nm) {
                $s = Student::where('name', $nm)->where('classroom_id', $kelas->id)->first();
                if (! $s) {
                    Student::create(['name' => $nm, 'nis' => 'NIS'.rand(1000, 9999), 'nisn' => $nisnMap[$nm], 'gender' => 'L', 'classroom_id' => $kelas->id, 'status' => 'aktif']);
                } elseif (! $s->nisn) {
                    $s->update(['nisn' => $nisnMap[$nm]]);
                }
            }
            $wali = User::where('email', 'wali_murid@madani.test')->first();
            if ($wali) {
                $andi = Student::where('name', 'Andi')->where('classroom_id', $kelas->id)->first();
                if ($andi && ! $andi->parent_id) {
                    $andi->update(['parent_id' => $wali->id]);
                }
            }
        }
    }
}
