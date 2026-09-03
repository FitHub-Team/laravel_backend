<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\RequestSubscriptionRequest;
use App\Models\Subscription;
use App\Services\General\RequestSubscription;

class RequestSubscriptionController extends Controller
{
    public function __construct(private RequestSubscription $subscriptionService) {}

    public function requestSubscription(RequestSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionService->sendRequest(
            $request->user()->id,
            $request->coach_id
        );
        return response()->json([
            'status' => true,
            'message' => 'Subscription request sent successfully',
            'data' => $subscription,
        ], 201);
    }

    public function getMyRequests()
    {
        $traineeId = auth()->id();
        $requests = $this->subscriptionService->getMyRequests($traineeId);
        return response()->json([
            'status' => true,
            'message' => 'Subscription requests fetched successfully',
            'data' => $requests,
        ]);
    }
}
