<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ClassroomService
{
    public function list(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Classroom::query()->withCount('students')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('academic_year', 'like', "%{$search}%"))
            ->orderBy('grade')->orderBy('name')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return Classroom::orderBy('grade')->orderBy('name')->get();
    }

    public function find(int $id): Classroom
    {
        return Classroom::findOrFail($id);
    }

    public function create(array $data): Classroom
    {
        return Classroom::create($data);
    }

    public function update(Classroom $classroom, array $data): Classroom
    {
        $classroom->update($data);

        return $classroom->fresh();
    }

    public function delete(Classroom $classroom): void
    {
        if ($classroom->students()->exists()) {
            abort(409, 'Kelas masih memiliki siswa');
        }
        $classroom->delete();
    }
}
