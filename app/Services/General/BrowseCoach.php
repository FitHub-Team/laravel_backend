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
        ->whereHas('coachProfile', function ($q) {
            $q->where('is_approved', true);
            // تم إزالة ->where('status', 'active') مؤقتاً لضمان ظهور كل المدربين المعتمدين لد فريق الفلاتر
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

    // البحث باسم الكوتش
    if (!empty($filters['search'])) {
        $query->where('full_name', 'like', '%' . $filters['search'] . '%');
    }

    // الفلترة حسب التخصص / المهارة    
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
            ->select(['id', 'full_name', 'email'])
            ->where('role', 'coach')
            ->where('id', $coachId)
            ->with([
                // جلب تفاصيل الملف الشخصي شاملة النبذة (bio) والسعر والصورة
                'coachProfile:id,user_id,specialization,experience,location,price,bio,profile_photo',
                'coachProfile.skills:id,name',
                'coachProfile.certifications:id,coach_id,title,issuer,year',
                
                // جلب أحدث المراجعات والتقييمات مع صورة واسم المتدرب
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