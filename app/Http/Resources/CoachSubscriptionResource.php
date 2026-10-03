<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Subscription;

class CoachSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // جلب أحدث اشتراك بين المتدرب الحالي والكوتش
        $subscription = null;
        if ($request->user()) {
            $subscription = Subscription::where('trainee_id', $request->user()->id)
                ->where('coach_id', $this->id)
                ->latest()
                ->first();
        }

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'profile_photo' => $this->coachProfile?->profile_photo,
            'bio' => $this->coachProfile?->bio,
            'price' => $this->coachProfile?->price ?? '0.00',
            'skills' => $this->coachProfile?->skills ? $this->coachProfile->skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ];
            }) : [],
            'auto_renew' => $subscription ? (bool) $subscription->auto_renew : true,
        ];
    }
}