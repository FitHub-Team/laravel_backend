<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'user') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالوصول إلى هذا المورد.',
            ], 403);
        }
        return $next($request);
    }
}
