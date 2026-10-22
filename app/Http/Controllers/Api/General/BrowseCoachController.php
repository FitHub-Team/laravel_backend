<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrowseCoachRequest;
use App\Http\Resources\CoachResource;
use App\Services\General\BrowseCoach;
use App\Http\Resources\CoachIndexResource;
use App\Http\Resources\CoachSubscriptionResource;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseCoachController extends Controller
{
    public function __construct(
        private BrowseCoach $browseCoach
    ) {}

    public function index(BrowseCoachRequest $request)
    {
        $coaches = $this->browseCoach->getActiveCoaches(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => CoachResource::collection($coaches),
        ]);
    }
public function getReviews($id)
    {
        $reviews = \Illuminate\Support\Facades\DB::table('coach_reviews')
            ->where('coach_id', $id)
            ->limit(10) 
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }
public function getSubscriptionStatus($id)
{
    $user = auth()->user();
    
    $subscription = \Illuminate\Support\Facades\DB::table('subscriptions')
        ->where('coach_id', $id)
        ->where('trainee_id', $user?->id) 
        ->first();

    return response()->json([
        'success' => true,
        'is_subscribed' => $subscription ? true : false,
        'data' => $subscription
    ]);
}
public function getSubscriptionDetails($id)
{
    $user = auth()->user();

    // 1. البحث عن اشتراك المستخدم الحالي مع هذا المدرب
    $subscription = \Illuminate\Support\Facades\DB::table('subscriptions')
        ->where('coach_id', $id)
        ->where('trainee_id', $user?->id)
        ->first();

    // 2. جلب معلومات المدرب وملفه الشخصي مع المهارات والشهادات
    $coachUser = \App\Models\User::with(['coachProfile.skills', 'coachProfile.certifications'])->find($id);
    $profile = $coachUser?->coachProfile;

    // الحصول على اللقب أو الشهادة الأولى للمدرب
    $title = $profile?->certifications->first()?->title ?? 'أخصائي تغذية رياضية CISSN';

    return response()->json([
        'success' => true,
        'data' => [
            'coach' => [
                'id' => $coachUser?->id,
                'name' => $coachUser?->name ?? 'الكابتن البراء',
                'title' => $title,
                'profile_photo' => $profile?->profile_photo ? asset($profile->profile_photo) : null,
                'skills' => $profile?->skills->pluck('name')->toArray() ?? ['التضخيم العضلي', 'اللياقة البدنية'],
            ],
            'plan' => [
                'title' => 'الاشتراك الشهري',
                'subtitle' => 'اشتراك شهري مع متابعة مستمرة',
                'price' => $profile?->price ? (float)$profile->price : 99, // السعر صافي بدون حقل عملة
                'period' => 'شهر',
                'includes' => [
                    [
                        'title' => 'خطة تمارين مخصصة',
                        'description' => 'مبنية حسب هدفك ومستواك',
                    ],
                    [
                        'title' => 'إرشادات تغذية مخصصة',
                        'description' => 'نظام غذائي يناسب أهدافك',
                    ],
                    [
                        'title' => 'تواصل مباشر مع المدرب',
                        'description' => 'عبر الرسائل في أي وقت',
                    ],
                    [
                        'title' => 'متابعة وتقييم مستمر',
                        'description' => 'لقياس تقدمك وتعديل خطتك',
                    ],
                ],
            ],
            'subscription' => [
                'is_subscribed' => $subscription ? true : false,
                'status' => $subscription?->status ?? 'none',
                'auto_renew' => $subscription?->auto_renew ?? true,
                'start_date' => $subscription?->start_date,
                'end_date' => $subscription?->end_date,
            ]
        ]
    ]);
}
}