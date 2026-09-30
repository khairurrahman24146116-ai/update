<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubjectService
{
    public function list(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Subject::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function find(int $id): Subject
    {
        return Subject::findOrFail($id);
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);

        return $subject->fresh();
    }

    public function delete(Subject $subject): void
    {
        if ($subject->teacherSubjects()->exists()) {
            abort(409, 'Mapel masih dipakai penugasan');
        }
        $subject->delete();
    }
}
