<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && ! $user->is_active) {
            return response()->json(['message' => 'Akun nonaktif. Silakan login kembali.'], 403);
        }

        return $next($request);
    }
}
