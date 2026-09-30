<?php

namespace App\Services;

use App\Models\TeacherSubject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TeacherSubjectService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return TeacherSubject::query()
            ->with(['teacher', 'subject', 'classroom'])
            ->when($filters['teacher_id'] ?? null, fn ($q, $v) => $q->where('teacher_id', $v))
            ->when($filters['classroom_id'] ?? null, fn ($q, $v) => $q->where('classroom_id', $v))
            ->orderBy('academic_year', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data): TeacherSubject
    {
        return TeacherSubject::create($data);
    }

    public function find(int $id): TeacherSubject
    {
        return TeacherSubject::with(['teacher', 'subject', 'classroom'])->findOrFail($id);
    }

    public function delete(TeacherSubject $teacherSubject): void
    {
        $teacherSubject->delete();
    }
}
