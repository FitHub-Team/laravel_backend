<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraineeProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'email' => $this->email,
          'role' => $this->role,
            'profile_photo' => $this->userProfile?->profile_photo,
            'completed_sessions' => 20,        
            'current_week_sessions' => 8,      
        ];
    }
}
