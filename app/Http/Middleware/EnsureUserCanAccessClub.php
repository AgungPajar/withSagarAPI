<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserCanAccessClub
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $clubId = $request->route('clubId');

        // Debug
        // logger()->info('User Role: ' . $user->role);
        // logger()->info('User Club ID: ' . $user->club_id);
        // logger()->info('Requested Club ID: ' . $clubId);

        if ($user->role === 'osis') {
            return $next($request);
        }

        if ($user->role === 'club_pengurus' && $user->club_id == $clubId) {
            return $next($request);
        }

        return response()->json(['message' => 'Tidak punya akses'], 403);
    }
}
