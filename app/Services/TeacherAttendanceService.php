<?php

namespace App\Services;

use App\Models\TeacherAttendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TeacherAttendanceService
{
    public function list(?string $date = null, ?int $teacherId = null, int $perPage = 15): LengthAwarePaginator
    {
        return TeacherAttendance::with('teacher')
            ->when($date, fn ($q) => $q->where('date', $date))
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->orderBy('date', 'desc')->paginate($perPage);
    }

    public function todayFor(int $teacherId, string $date): ?TeacherAttendance
    {
        return TeacherAttendance::where('teacher_id', $teacherId)->where('date', $date)->first();
    }

    public function checkIn(int $teacherId, string $date, string $time): TeacherAttendance
    {
        return TeacherAttendance::firstOrCreate(
            ['teacher_id' => $teacherId, 'date' => $date],
            ['status' => 'hadir']
        )->tap(function (TeacherAttendance $r) use ($time) {
            if ($r->check_in) {
                abort(409, 'Sudah check-in hari ini');
            }
            $r->update(['check_in' => $time, 'status' => 'hadir']);
        });
    }

    public function checkOut(int $teacherId, string $date, string $time): TeacherAttendance
    {
        $r = TeacherAttendance::where('teacher_id', $teacherId)->where('date', $date)->first();
        if (! $r || ! $r->check_in) {
            abort(422, 'Belum check-in');
        }
        if ($r->check_out) {
            abort(409, 'Sudah check-out hari ini');
        }
        $r->update(['check_out' => $time]);

        return $r->fresh();
    }

    public function update(TeacherAttendance $record, array $data): TeacherAttendance
    {
        $record->update($data);

        return $record->fresh('teacher');
    }
}
