<?php

namespace App\Services\General;

use App\Models\Subscription;
use App\Models\User;

class RequestSubscription
{
    public function sendRequest(int $traineeId, int $coachId)
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
            'status' => 'pending',
        ]);
    }
    // get all subscription requests for a specific coach
    public function getMyRequests(int $traineeId)
    {
        return Subscription::where('trainee_id', $traineeId)
            ->with('coach')
            ->latest()
            ->get();
    }
}
