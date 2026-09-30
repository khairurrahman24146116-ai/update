<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherSubject;
use App\Services\ActivityLogger;
use App\Services\TeacherSubjectService;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function __construct(protected TeacherSubjectService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', TeacherSubject::class);

        return $this->service->list($request->all());
    }

    public function show(int $id)
    {
        $ts = $this->service->find($id);
        $this->authorize('view', $ts);

        return $ts;
    }

    public function store(Request $request)
    {
        $this->authorize('create', TeacherSubject::class);
        $v = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'academic_year' => 'sometimes|string|max:20',
        ]);
        $ts = $this->service->create($v);
        ActivityLogger::log('teacher_subject.create', $request->user(), TeacherSubject::class, $ts->id, $v);

        return response()->json($ts, 201);
    }

    public function destroy(int $id, Request $request)
    {
        $ts = $this->service->find($id);
        $this->authorize('delete', $ts);
        $this->service->delete($ts);
        ActivityLogger::log('teacher_subject.delete', $request->user(), TeacherSubject::class, $id);

        return response()->noContent();
    }
}
