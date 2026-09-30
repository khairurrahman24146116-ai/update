<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertAttendanceRequest;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $service) {}

    public function index(int $scheduleId, Request $request)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $this->authorize('view', $schedule);

        return $this->service->listBySchedule($scheduleId, $request->query('date'));
    }

    public function sheet(int $scheduleId, Request $request)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $this->authorize('view', $schedule);

        return $this->service->sheet($scheduleId, $request->query('date', date('Y-m-d')));
    }

    public function store(int $scheduleId, UpsertAttendanceRequest $request)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $this->authorize('store', $schedule);
        $this->service->upsertMany($scheduleId, $request->query('date', date('Y-m-d')), $request->validated()['rows']);

        return response()->noContent();
    }
}
