<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(protected ScheduleService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Schedule::class);

        return $this->service->list(
            $request->query('search'),
            $request->query('classroom_id') ? (int) $request->query('classroom_id') : null,
            $request->query('teacher_id') ? (int) $request->query('teacher_id') : null,
            $request->query('day')
        );
    }

    public function store(StoreScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);

        return response()->json($this->service->create($request->validated()), 201);
    }

    public function show(int $id)
    {
        $s = $this->service->find($id);
        $this->authorize('view', $s);

        return $s;
    }

    public function update(StoreScheduleRequest $request, int $id)
    {
        $s = $this->service->find($id);
        $this->authorize('update', $s);

        return $this->service->update($s, $request->validated());
    }

    public function destroy(int $id)
    {
        $s = $this->service->find($id);
        $this->authorize('delete', $s);
        $this->service->delete($s);

        return response()->noContent();
    }
}
