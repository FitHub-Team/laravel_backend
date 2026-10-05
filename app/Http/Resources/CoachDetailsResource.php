<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoachDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'profile_photo' => $this->coachProfile?->profile_photo,
            'experience' => $this->coachProfile?->experience,
            'is_approved' => $this->coachProfile?->is_approved,
            
            // إضافة خاصية متصل الآن
            'is_online' => $this->last_seen_at ? now()->diffInMinutes($this->last_seen_at) < 2 : false,
            
            'location' => $this->coachProfile?->location,
            'bio' => $this->coachProfile?->bio,
            'price' => $this->coachProfile?->price,
            'average_rating' => round($this->average_rating ?? 0, 1),
            'reviews_count' => $this->reviews_count ?? 0,
            'active_subscribers_count' => $this->active_subscribers_count ?? 0,
            
            // المهارات
            'skills' => $this->coachProfile && $this->coachProfile->relationLoaded('skills') 
                ? $this->coachProfile->skills->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                    ];
                }) : [],
            
            // الشهادات الموثقة
            'certifications' => $this->coachProfile && $this->coachProfile->certifications 
                ? $this->coachProfile->certifications->map(function ($cert) {
                    return [
                        'title' => $cert->title,
                        'issuer' => $cert->issuer,
                        'year' => $cert->year,
                    ];
                }) : [],

            // مراجعات المتدربين السابقين
            'reviews' => $this->reviews 
                ? $this->reviews->map(function ($review) {
                    return [
                        'trainee_name' => $review->trainee?->full_name,
                        'trainee_photo' => null,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                    ];
                }) : [],
        ];
    }
}
