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
