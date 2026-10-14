<?php

namespace App\Services\General;

use App\Models\User;

class BrowseCoach
{
   public function getActiveCoaches(array $filters = [])
{
    $query = User::query()
        ->select([
            'id',
            'full_name',
            'email'
        ])
        ->where('role', 'coach')
        ->whereHas('coachProfile', function ($q) use ($filters) {
            $q->where('is_approved', true);

            // 1. الفلترة حسب السعر (أقصى سعر / أو نطاق السعر)
            if (!empty($filters['max_price'])) {
                $q->where('price', '<=', $filters['max_price']);
            }

            if (!empty($filters['min_price'])) {
                $q->where('price', '>=', $filters['min_price']);
            }
        })
        ->with([
            'coachProfile:id,user_id,specialization,experience,location,price,profile_photo',
            'coachProfile.skills:id,name', 
        ])
        ->withCount([
            'subscriptions as active_subscribers_count' => function ($q) {
                $q->where('status', 'accepted'); 
            }
        ])
        ->withAvg([
            'reviews as average_rating' => function ($q) {
                $q->select('rating');
            }
        ], 'rating');

    // 2. البحث باسم الكوتش
    if (!empty($filters['search'])) {
        $query->where('full_name', 'like', '%' . $filters['search'] . '%');
    }

    // 3. الفلترة حسب التخصص / المهارة     
    if (!empty($filters['skill_id'])) {
        $query->whereHas('coachProfile.skills', function ($q) use ($filters) {
            $q->where('skills.id', $filters['skill_id']);
        });
    }

    return $query->paginate(10);
}

    /**
     * جلب تفاصيل الكوتش الكاملة برقم الـ ID لصفحة البروفايل (النبذة، الشهادات، والمراجعات)
     */
   public function getCoachDetails($coachId)
    {
        return User::query()
            ->select(['id', 'full_name', 'email', 'last_seen_at'])
            ->where('role', 'coach')
            ->where('id', $coachId)
            ->with([
                'coachProfile:id,user_id,specialization,experience,location,price,bio,profile_photo',
                'coachProfile.skills:id,name',
                'coachProfile.certifications:id,coach_id,title,issuer,year',
                
                'reviews' => function ($q) {
                    $q->latest()->take(10);
                },
                'reviews.trainee:id,full_name,profile_image',
            ])
            ->withCount([
                'subscriptions as active_subscribers_count' => function ($q) {
                    $q->where('status', 'accepted');
                },
                'reviews as reviews_count'
            ])
            ->withAvg([
                'reviews as average_rating' => function ($q) {
                    $q->select('rating');
                }
            ], 'rating')
            ->firstOrFail();
    }
}