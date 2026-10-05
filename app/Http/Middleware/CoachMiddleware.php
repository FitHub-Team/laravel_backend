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
       $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }
     
        if (($user->role ?? null) !== 'coach') {
            return response()->json(['message' => 'هذا الإجراء متاح للمدربين فقط'], 403);
        }

        return $next($request);
    }
}
