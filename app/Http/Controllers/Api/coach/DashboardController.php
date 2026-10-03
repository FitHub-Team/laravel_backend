<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $coach = $request->user();
        $subscriptionsCount = Subscription::where('coach_id', $coach->id)->count();
        return response()->json([
            'message' => 'Coach dashboard data',
            'coach' => $coach,
            'subscriptions_count'=> $subscriptionsCount
        ]);
    }
}
