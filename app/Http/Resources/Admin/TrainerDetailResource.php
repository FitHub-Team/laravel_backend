<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->name ?? '-',
            'email' => $this->user->email ?? '-',
            'phone' => $this->user->phone ?? '-',
            'profile_image' => optional($this->user)->profile_image ? asset($this->user->profile_image) : null,
            'specialization' => $this->specialization ?? '-',
            'experience' => $this->experience,
            'birth_year' => $this->birth_year,
            'location' => $this->location ?? '-',
            'national_id' => $this->national_id ?? '-',
            'bio' => $this->bio ?? '-',
            'certifications' => $this->certifications ?? [],
            'is_approved' => (bool) $this->is_approved,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
