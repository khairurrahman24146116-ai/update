<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ScheduleService
{
    public function list(?string $search = null, ?int $classroomId = null, ?int $teacherId = null, ?string $day = null, int $perPage = 15): LengthAwarePaginator
    {
        return Schedule::query()->with(['classroom', 'subject', 'teacher'])
            ->when($search, fn ($q) => $q->whereHas('subject', fn ($s) => $s->where('name', 'like', "%{$search}%")))
            ->when($classroomId, fn ($q) => $q->where('classroom_id', $classroomId))
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->when($day, fn ($q) => $q->where('day_of_week', $day))
            ->orderByRaw("FIELD(day_of_week,'mon','tue','wed','thu','fri','sat','sun')")
            ->orderBy('start_time')
            ->paginate($perPage);
    }

    public function forGuru(int $teacherId, ?string $day = null): Collection
    {
        return Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->when($day, fn ($q) => $q->where('day_of_week', $day))
            ->orderByRaw("FIELD(day_of_week,'mon','tue','wed','thu','fri','sat','sun')")
            ->orderBy('start_time')->get();
    }

    public function find(int $id): Schedule
    {
        return Schedule::with(['classroom', 'subject', 'teacher'])->findOrFail($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);

        return $schedule->fresh(['classroom', 'subject', 'teacher']);
    }

    public function delete(Schedule $schedule): void
    {
        $schedule->delete();
    }
}
