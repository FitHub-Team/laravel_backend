<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    // عرض قائمة طلبات الاشتراك قيد الانتظار الواردة من المتدربين
    public function pendingRequests(Request $request)
    {
        $requests = Subscription::where('coach_id', $request->user()->id)
            ->where('status', 'pending')
            ->with(['trainee']) // تم إزالة package
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $requests
        ], 200);
    }

    // قبول طلب الاشتراك
    public function acceptRequest(Request $request, $id)
    {
        $subscription = Subscription::where('id', $id)
            ->where('coach_id', $request->user()->id)
            ->firstOrFail();

        // مدة ثابتة للاشتراك الشهري (30 يوماً) لعدم وجود باقات
        $durationDays = 30;

        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($durationDays);

        $subscription->update([
            'status' => 'accepted',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subscription request accepted successfully',
            'data' => $subscription
        ], 200);
    }

    // رفض طلب الاشتراك
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $subscription = Subscription::where('id', $id)
            ->where('coach_id', $request->user()->id)
            ->firstOrFail();

        $subscription->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subscription request rejected',
            'data' => $subscription
        ], 200);
    }

    // عرض قائمة المتدربين المشتركين حاليا مع الكوتش
    public function myTrainees(Request $request)
    {
        $trainees = Subscription::where('coach_id', $request->user()->id)
            ->where('status', 'accepted')
            ->with(['trainee']) 
            ->get();

        return response()->json([
            'status' => true,
            'data' => $trainees
        ], 200);
    }

    // عرض بيانات متدرب محدد 
    public function showTraineeDetails(Request $request, $trainee_id)
    {
        $hasSubscription = Subscription::where('coach_id', $request->user()->id)
            ->where('trainee_id', $trainee_id)
            ->exists();

        if (!$hasSubscription) {
            return response()->json([
                'status' => false,
                'message' => 'Trainee not found in your subscription list'
            ], 404);
        }

        $trainee = User::where('id', $trainee_id)
            ->with('profile') 
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $trainee
        ], 200);
    }
}