<?php
// backend/app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // Not logged in
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login.',
            ], 401);
        }

        // Wrong role
        if ($request->user()->role !== $role) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission.',
            ], 403);
        }

        return $next($request);
    }
}