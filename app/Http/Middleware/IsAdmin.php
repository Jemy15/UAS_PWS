<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user sudah login
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Cek role admin
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Admin only.'
            ], 403);
        }

        // Lanjutkan request
        return $next($request);
    }
}
