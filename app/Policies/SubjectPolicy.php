<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Subject $s): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Subject $s): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Subject $s): bool
    {
        return $user->role === 'admin';
    }
}
