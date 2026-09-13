<?php

namespace App\Services\Coach;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    public function getPendingRequests(int $coachId)
    {
        return Subscription::where('coach_id', $coachId)
            ->where('status', 'pending')
            ->with(['trainee'])
            ->latest()
            ->get();
    }

    public function acceptSubscription(int $subscriptionId, int $coachId): Subscription
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        $durationDays = 30; // مدة ثابتة للاشتراك الشهري
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($durationDays);

        $subscription->update([
            'status' => 'accepted',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return $subscription;
    }

    public function rejectSubscription(int $subscriptionId, int $coachId, ?string $notes): Subscription
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        $subscription->update([
            'status' => 'rejected',
            'notes' => $notes,
        ]);

        return $subscription;
    }

    public function getAcceptedTrainees(int $coachId)
    {
        return Subscription::where('coach_id', $coachId)
            ->where('status', 'accepted')
            ->with(['trainee'])
            ->get();
    }

    public function verifyAndGetTraineeDetails(int $coachId, int $traineeId): ?User
    {
        $hasSubscription = Subscription::where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->exists();

        if (!$hasSubscription) {
            return null;
        }

        return User::where('id', $trainee_id)
            ->with('profile')
            ->firstOrFail();
    }
}