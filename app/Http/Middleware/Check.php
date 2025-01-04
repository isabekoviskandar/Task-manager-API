<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Check
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('sanctum')->user();
        if ($user && ($user->role === 'admin' || $user->role === 'moderator')) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Cannot perform this action',
        ], 403);
    }
}

