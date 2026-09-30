<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\ActivityLogger;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Subject::class);

        return $this->service->list($request->query('search'));
    }

    public function show(int $id)
    {
        $subject = $this->service->find($id);
        $this->authorize('view', $subject);

        return $subject;
    }

    public function store(Request $request)
    {
        $this->authorize('create', Subject::class);
        $v = $request->validate(['name' => 'required|string', 'code' => 'required|string|unique:subjects']);
        $s = $this->service->create($v);
        ActivityLogger::log('subject.create', $request->user(), Subject::class, $s->id, $v);

        return response()->json($s, 201);
    }

    public function update(Request $request, int $id)
    {
        $s = $this->service->find($id);
        $this->authorize('update', $s);
        $v = $request->validate(['name' => 'string', 'code' => 'string|unique:subjects,code,'.$id]);
        $updated = $this->service->update($s, $v);
        ActivityLogger::log('subject.update', $request->user(), Subject::class, $id, $v);

        return $updated;
    }

    public function destroy(int $id, Request $request)
    {
        $subject = $this->service->find($id);
        $this->authorize('delete', $subject);
        $this->service->delete($subject);
        ActivityLogger::log('subject.delete', $request->user(), Subject::class, $id);

        return response()->noContent();
    }
}
