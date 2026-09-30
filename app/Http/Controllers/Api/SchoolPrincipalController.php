<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolPrincipal;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SchoolPrincipalController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return SchoolPrincipal::where('is_current', true)->first();
    }

    public function store(Request $request)
    {
        $this->authorize('create', SchoolPrincipal::class);
        $v = $request->validate(['name' => 'required|string', 'title' => 'nullable|string', 'term_start' => 'nullable|date']);
        if ($request->hasFile('photo')) {
            $v['photo_path'] = $request->file('photo')->store('principals', 'public');
        }
        if ($request->hasFile('signature')) {
            $v['signature_path'] = $request->file('signature')->store('principals/signatures', 'public');
        }
        $v['is_current'] = true;
        $p = SchoolPrincipal::create($v);
        ActivityLogger::log('principal.create', $request->user(), SchoolPrincipal::class, $p->id, $v);

        return response()->json($p, 201);
    }

    public function update(Request $request, SchoolPrincipal $schoolPrincipal)
    {
        $this->authorize('update', $schoolPrincipal);
        $v = $request->validate(['name' => 'sometimes|required|string', 'title' => 'nullable|string', 'term_start' => 'nullable|date']);
        if ($request->hasFile('photo')) {
            $v['photo_path'] = $request->file('photo')->store('principals', 'public');
        }
        if ($request->hasFile('signature')) {
            $v['signature_path'] = $request->file('signature')->store('principals/signatures', 'public');
        }
        $old = $schoolPrincipal->toArray();
        $schoolPrincipal->update($v);
        ActivityLogger::log('principal.update', $request->user(), SchoolPrincipal::class, $schoolPrincipal->id, ['old' => $old, 'new' => $v]);

        return $schoolPrincipal;
    }

    public function destroy(Request $request, SchoolPrincipal $schoolPrincipal)
    {
        $this->authorize('delete', $schoolPrincipal);
        $id = $schoolPrincipal->id;
        $schoolPrincipal->delete();
        ActivityLogger::log('principal.delete', $request->user(), SchoolPrincipal::class, $id, ['id' => $id]);

        return response()->noContent();
    }
}
