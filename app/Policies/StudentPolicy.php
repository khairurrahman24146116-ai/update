<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\TeacherSubject;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru', 'wali_murid', 'bendahara']);
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->role === 'admin' || $user->role === 'bendahara') {
            return true;
        }
        if ($user->role === 'wali_murid') {
            return (int) $student->parent_id === (int) $user->id;
        }
        if ($user->role === 'guru') {
            return TeacherSubject::where('teacher_id', $user->id)->where('classroom_id', $student->classroom_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Student $student): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->role === 'admin';
    }
}
