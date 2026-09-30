<?php

namespace App\Policies;

use App\Models\TeacherSubject;
use App\Models\User;

class TeacherSubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TeacherSubject $ts): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, TeacherSubject $ts): bool
    {
        return $user->role === 'admin';
    }
}
