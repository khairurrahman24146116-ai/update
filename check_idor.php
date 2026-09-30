<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Policies\AttendancePolicy;
use App\Policies\SchedulePolicy;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

$admin = User::where('role', 'admin')->first() ?? User::create(['name' => 'Admin', 'email' => 'admin@madani.test', 'password' => Hash::make('pass'), 'role' => 'admin']);
$guruA = User::where('email', 'guruA@madani.test')->first() ?? User::create(['name' => 'Guru A', 'email' => 'guruA@madani.test', 'password' => Hash::make('pass'), 'role' => 'guru']);
$guruB = User::where('email', 'guruB@madani.test')->first() ?? User::create(['name' => 'Guru B', 'email' => 'guruB@madani.test', 'password' => Hash::make('pass'), 'role' => 'guru']);
$class = Classroom::first() ?? Classroom::create(['name' => 'X-A', 'grade' => 10]);
$subj = Subject::first() ?? Subject::create(['name' => 'Matematika', 'code' => 'MTK']);
$scheduleA = Schedule::where('teacher_id', $guruA->id)->first();
if (! $scheduleA) {
    $scheduleA = Schedule::create(['classroom_id' => $class->id, 'subject_id' => $subj->id, 'teacher_id' => $guruA->id, 'day_of_week' => 'mon', 'start_time' => '08:00', 'end_time' => '09:30']);
}

$ap = new AttendancePolicy;
echo 'IDOR 1 — guruB store di schedule guruA: '.($ap->store($guruB, $scheduleA) ? 'FAIL leak' : 'PASS blocked')."\n";
echo 'IDOR 2 — guruA store di schedule sendiri: '.($ap->store($guruA, $scheduleA) ? 'PASS allowed' : 'FAIL blocked')."\n";
$sp = new SchedulePolicy;
echo 'IDOR 3 — guru create schedule: '.($sp->create($guruA) ? 'FAIL leak' : 'PASS blocked')."\n";
echo 'IDOR 4 — admin create schedule: '.($sp->create($admin) ? 'PASS allowed' : 'FAIL blocked')."\n";
echo 'IDOR 5 — admin view any: '.($sp->viewAny($admin) ? 'PASS' : 'FAIL')."\n";
echo "Done — 5/5 expected PASS = all blocked/allowed correctly\n";
