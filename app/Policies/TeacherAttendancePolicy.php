<?php

namespace App\Policies;

use App\Models\User;

class TeacherAttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru']);
    }

    public function checkIn(User $user): bool
    {
        return $user->role === 'guru';
    }

    public function checkOut(User $user): bool
    {
        return $user->role === 'guru';
    }
}
