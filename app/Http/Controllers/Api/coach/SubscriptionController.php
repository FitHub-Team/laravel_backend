<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    // عرض قائمة طلبات الاشتراك قيد الانتظار الواردة من المتدربين
    public function pendingRequests(Request $request)
    {
        $requests = $this->subscriptionService->getPendingRequests($request->user()->id);

        return response()->json([
            'status' => true,
            'data' => $requests
        ], 200);
    }

    // قبول طلب الاشتراك
    public function acceptRequest(Request $request, $id)
    {
        $subscription = $this->subscriptionService->acceptSubscription($id, $request->user()->id);

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

        $subscription = $this->subscriptionService->rejectSubscription(
            $id, 
            $request->user()->id, 
            $request->notes
        );

        return response()->json([
            'status' => true,
            'message' => 'Subscription request rejected',
            'data' => $subscription
        ], 200);
    }

    // عرض قائمة المتدربين المشتركين حاليا مع الكوتش
    public function myTrainees(Request $request)
    {
        $trainees = $this->subscriptionService->getAcceptedTrainees($request->user()->id);

        return response()->json([
            'status' => true,
            'data' => $trainees
        ], 200);
    }

    // عرض بيانات متدرب محدد 
    public function showTraineeDetails(Request $request, $trainee_id)
    {
        $trainee = $this->subscriptionService->verifyAndGetTraineeDetails(
            $request->user()->id, 
            $trainee_id
        );

        if (!$trainee) {
            return response()->json([
                'status' => false,
                'message' => 'Trainee not found in your subscription list'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $trainee
        ], 200);
    }
}