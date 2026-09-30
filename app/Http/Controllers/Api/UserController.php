<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
        $q = User::query()->select(['id', 'name', 'email', 'phone', 'role', 'is_active', 'is_protected', 'created_at'])
            ->when($request->role, fn ($qq, $v) => $qq->where('role', $v))
            ->when($request->search, fn ($qq, $v) => $qq->where(fn ($q2) => $q2->where('name', 'like', "%{$v}%")->orWhere('email', 'like', "%{$v}%")))
            ->when($request->has('is_active'), fn ($qq) => $qq->where('is_active', (bool) $request->is_active));

        return $q->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
        $v = $request->validate(['name' => 'required|string', 'email' => 'required|email|unique:users', 'password' => 'required|string|min:6', 'role' => 'required|in:admin,guru,bendahara,wali_murid', 'is_active' => 'boolean']);
        $v['password'] = Hash::make($v['password']);
        $u = User::create($v);

        return response()->json($u->only(['id', 'name', 'email', 'role', 'is_active', 'is_protected', 'phone', 'created_at']), 201);
    }

    public function update(Request $request, int $id)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
        $u = User::findOrFail($id);
        $v = $request->validate(['name' => 'sometimes|string', 'email' => 'sometimes|email|unique:users,email,'.$id, 'password' => 'sometimes|nullable|string|min:6', 'role' => 'sometimes|in:admin,guru,bendahara,wali_murid', 'is_active' => 'sometimes|boolean']);
        if (array_key_exists('is_active', $v) && $v['is_active'] === false) {
            if ($u->is_protected) {
                abort(403, 'Akun utama tidak dapat dinonaktifkan');
            }
            if ((int) $u->id === (int) $request->user()->id) {
                abort(403, 'Tidak dapat menonaktifkan akun sendiri');
            }
            if ($u->role === 'admin' && User::where('role', 'admin')->where('is_active', true)->where('id', '!=', $u->id)->count() === 0) {
                abort(409, 'Minimal satu admin aktif harus tersisa');
            }
        }
        if (isset($v['password']) && $v['password']) {
            $v['password'] = Hash::make($v['password']);
        } else {
            unset($v['password']);
        }
        $u->update($v);

        return $u->fresh()->only(['id', 'name', 'email', 'role', 'is_active', 'is_protected', 'phone', 'created_at']);
    }

    public function destroy(Request $request, int $id)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
        $u = User::findOrFail($id);
        if ($u->is_protected) {
            abort(403, 'Akun utama tidak dapat dihapus');
        }
        if ((int) $u->id === (int) $request->user()->id) {
            abort(403, 'Tidak dapat menghapus akun sendiri');
        }
        if ($u->role === 'admin' && User::where('role', 'admin')->where('id', '!=', $u->id)->count() === 0) {
            abort(409, 'Minimal satu admin harus tersisa');
        }
        if (Student::where('parent_id', $u->id)->exists()) {
            abort(409, 'Akun masih terhubung dengan data santri');
        }
        $u->delete();
        ActivityLogger::log('user.delete', $request->user(), User::class, $id, ['deleted_id' => $id]);

        return response()->noContent();
    }
}
