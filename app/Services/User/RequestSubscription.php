<?php

namespace App\Services\User;

use App\Models\Subscription;
use App\Models\User;

class RequestSubscription
{
    public function sendRequest(int $traineeId, int $coachId, bool $autoRenew = true)
    {
        $trainee = User::findOrFail($traineeId);

        if ($trainee->role !== 'user') {
            throw new \Exception('Only trainees can send subscription requests.');
        }
        $coach = User::findOrFail($coachId);

        if ($coach->role !== 'coach') {
            throw new \Exception('Only coaches can receive subscription requests.');
        }
        $existingSubscription = Subscription::where('trainee_id', $traineeId)
            ->where('coach_id', $coachId)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingSubscription) {
            throw new \Exception('A subscription request already exists for this trainee and coach.');
        }

        return Subscription::create([
            'trainee_id' => $traineeId,
            'coach_id' => $coachId,
            'auto_renew' => $autoRenew, // حفظ حالة التجديد التلقائي (مفعل أو غير مفعل)
            'status' => 'pending',
        ]);
    }

    public function getMyRequests(int $traineeId)
    {
        return Subscription::where('trainee_id', $traineeId)
            ->with('coach')
            ->latest()
            ->get();
    }

    public function updateAutoRenew(int $traineeId, int $subscriptionId, bool $autoRenew)
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('trainee_id', $traineeId)
            ->firstOrFail();

        $subscription->update([
            'auto_renew' => $autoRenew,
        ]);

        return $subscription;
    }
}