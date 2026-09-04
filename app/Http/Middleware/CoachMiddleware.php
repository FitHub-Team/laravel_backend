<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoachMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'coach') {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to access this resource.',
            ], 403);
        }
        return $next($request);
    }
}
