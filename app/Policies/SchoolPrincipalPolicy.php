<?php

namespace App\Policies;

use App\Models\SchoolPrincipal;
use App\Models\User;

class SchoolPrincipalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, SchoolPrincipal $principal): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, SchoolPrincipal $principal): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, SchoolPrincipal $principal): bool
    {
        return $user->role === 'admin';
    }
}
