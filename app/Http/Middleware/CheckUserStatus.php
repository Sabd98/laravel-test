<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->status) {
            return response()->json([
                'message' => 'Your account is inactive'
            ], 403);
        }

        return $next($request);
    }
}
