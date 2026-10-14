<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoachResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'price' => $this->coachProfile?->price,
            'rating' => round($this->average_rating ?? 4.8, 1),
            'active_trainees' => $this->active_subscribers_count ?? 0,
            'tags' => $this->coachProfile?->skills->pluck('name') ?? [],
            'profile_photo' => $this->coachProfile?->profile_photo,
        ];
    }
}