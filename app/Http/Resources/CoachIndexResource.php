<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoachIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name, // اسم المدرب
            'profile_photo' => $this->coachProfile?->profile_photo, // صورة المدرب
            'experience' => $this->coachProfile?->experience, // عدد سنوات الخبرة
            'active_subscribers_count' => $this->active_subscribers_count, // عدد المتدربين النشطين
            'price' => $this->coachProfile?->price, // السعر
            'average_rating' => round($this->average_rating, 1), // التقييم
           'specialization' => $this->coachProfile?->specialization, //   // التخصصات/المهارات  
        ];
    }
}