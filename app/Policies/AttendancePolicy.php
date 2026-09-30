<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class AttendancePolicy
{
    public function view(User $user, Schedule $schedule): bool
    {
        return $user->role === 'admin' || ($user->role === 'guru' && $schedule->teacher_id === $user->id);
    }

    public function store(User $user, Schedule $schedule): bool
    {
        return $user->role === 'guru' && $schedule->teacher_id === $user->id;
    }
}
