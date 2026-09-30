<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PPDBRegistration;
use App\Models\PPDBWave;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PPDBRegistrationController extends Controller
{
    public function publicStore(Request $request)
    {
        $v = $request->validate([
            'wave_id' => 'required|exists:p_p_d_b_waves,id',
            'name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
        ]);
        $wave = PPDBWave::findOrFail($v['wave_id']);
        if (! $wave->is_public || $wave->status !== 'buka') {
            return response()->json(['message' => 'Gelombang tidak tersedia untuk pendaftaran'], 422);
        }
        if ($wave->start_date && now()->lt($wave->start_date)) {
            return response()->json(['message' => 'Pendaftaran belum dibuka'], 422);
        }
        if ($wave->end_date && now()->gt($wave->end_date)) {
            return response()->json(['message' => 'Pendaftaran sudah ditutup'], 422);
        }
        $reg = PPDBRegistration::create(array_merge($v, ['status' => 'pending']));

        return response()->json($reg, 201);
    }

    public function index(Request $request)
    {
        return PPDBRegistration::with('wave:id,name,slug')
            ->when($request->wave_id, fn ($q, $v) => $q->where('wave_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->search, fn ($q, $v) => $q->where(fn ($qq) => $qq->where('name', 'like', "%{$v}%")->orWhere('nisn', 'like', "%{$v}%")))
            ->latest()->paginate(15);
    }

    public function show(PPDBRegistration $ppdbRegistration)
    {
        return $ppdbRegistration->load('wave:id,name,slug');
    }

    public function update(Request $request, PPDBRegistration $ppdbRegistration)
    {
        $v = $request->validate(['status' => 'required|in:pending,diterima,ditolak,cadangan', 'notes' => 'nullable|string']);
        $ppdbRegistration->update($v);
        ActivityLogger::log('ppdb_registration.update', $request->user(), PPDBRegistration::class, $ppdbRegistration->id, $v);

        return $ppdbRegistration->fresh()->load('wave');
    }

    public function destroy(Request $request, PPDBRegistration $ppdbRegistration)
    {
        $ppdbRegistration->delete();
        ActivityLogger::log('ppdb_registration.delete', $request->user(), PPDBRegistration::class, $ppdbRegistration->id);

        return response()->noContent();
    }
}
