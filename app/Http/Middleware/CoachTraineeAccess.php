<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoachTraineeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $coach = $request->user();
        $traineeId = $request->route('trainee_id');
        $hasAccess = Subscription::where('coach_id', $coach->id)
            ->where('trainee_id', $traineeId)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();
        if (!$hasAccess) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to access this trainee profile.',
            ], 403);
        }
        return $next($request);
    }
}
