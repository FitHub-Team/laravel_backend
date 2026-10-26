<?php

namespace App\Http\Resources\user;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraineeProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $profile = $this->userProfile;
        return [
            'full_name' => $this->full_name,
            'gender' => $profile?->gender,
            'date_of_birth' => $profile?->date_of_birth,

            'profile_photo' => $profile?->profile_photo
                ? asset('storage/' . $profile->profile_photo)
                : null,
        ];
    }
}
