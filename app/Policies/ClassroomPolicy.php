<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Classroom $c): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Classroom $c): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Classroom $c): bool
    {
        return $user->role === 'admin';
    }
}
