<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;

class ScorePolicy
{
    public function viewAny(User $user, ?Classroom $class = null, ?Subject $subj = null): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'guru') {
            // guru boleh akses jika mengampu mapel+kelas (teacher_subjects) — fallback: izinkan jika teacher_subject record ada untuk user
            return TeacherSubject::where('teacher_id', $user->id)->exists()
                || Schedule::where('teacher_id', $user->id)->exists();
        }

        return false;
    }

    public function store(User $user, Classroom $class, Subject $subj): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role !== 'guru') {
            return false;
        }

        return TeacherSubject::where('teacher_id', $user->id)->where('classroom_id', $class->id)->where('subject_id', $subj->id)->exists()
            || Schedule::where('teacher_id', $user->id)->where('classroom_id', $class->id)->where('subject_id', $subj->id)->exists();
    }
}
