<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Schedule $s): bool
    {
        return true;
    }

    /**
     * Otorisasi baca lembar absensi didelegasikan ke AttendancePolicy
     * (admin, atau guru pemilik jadwal).
     */
    public function viewAttendance(User $user, Schedule $schedule): bool
    {
        return app(AttendancePolicy::class)->view($user, $schedule);
    }

    /**
     * Otorisasi simpan absensi didelegasikan ke AttendancePolicy
     * (guru pemilik jadwal).
     */
    public function storeAttendance(User $user, Schedule $schedule): bool
    {
        return app(AttendancePolicy::class)->store($user, $schedule);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Schedule $s): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Schedule $s): bool
    {
        return $user->role === 'admin';
    }
}
