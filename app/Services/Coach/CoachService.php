<?php

namespace App\Services\Coach;

use App\Helper\ImageHelper;
use App\Models\CoachProfile;
use App\Models\User;
use App\Models\skill;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoachService
{
    public function saveCoachProfile(int $userId, array $data): CoachProfile
    {
        dd($data);
        return DB::transaction(function () use ($userId, $data) {

            $hasSkills = array_key_exists('skills', $data);
            $hasCertifications = array_key_exists('certifications', $data);

            $skills = $data['skills'] ?? [];
            $certifications = $data['certifications'] ?? [];

            // لا نرسل العلاقات إلى updateOrCreate
            unset($data['skills'], $data['certifications']);

            $profile = CoachProfile::updateOrCreate(
                ['user_id' => $userId],
                $data
            );

            /*
            |--------------------------------------------------------------------------
            | Skills
            |--------------------------------------------------------------------------
            |
            | نعدل الـ skills فقط إذا كانت موجودة في الـ request.
            | إذا لم يتم إرسال skills، نحافظ على البيانات القديمة.
            |
            */

            if ($hasSkills) {

                $skillIds = collect($skills)
                    ->map(function ($skillData) {

                        // Skill موجودة مسبقًا
                        if (!empty($skillData['id'])) {
                            return $skillData['id'];
                        }

                        // Skill جديدة
                        if (!empty($skillData['name'])) {
                            return skill::firstOrCreate([
                                'name' => $skillData['name'],
                            ])->id;
                        }

                        return null;
                    })
                    ->filter()
                    ->values()
                    ->toArray();

                $profile->skills()->sync($skillIds);
            }

            /*
            |--------------------------------------------------------------------------
            | Certifications
            |--------------------------------------------------------------------------
            |
            | نحذف ونعيد إنشاء الشهادات فقط إذا certifications
            | تم إرسالها في الـ request.
            |
            */

            if ($hasCertifications) {

                $profile->certifications()->delete();

                foreach ($certifications as $certification) {

                    if (empty($certification['title'])) {
                        continue;
                    }

                    $profile->certifications()->create([
                        'title' => $certification['title'],
                        'issuer' => $certification['issuer'] ?? null,
                        'year' => $certification['year'] ?? null,
                    ]);
                }
            }

            return $profile->fresh([
                'user',
                'skills',
                'certifications',
            ]);
        });
    }

    public function getCoachProfile(int $userId): ?CoachProfile
    {
        return CoachProfile::with([
            'user',
            'skills',
            'certifications',
        ])
            ->where('user_id', $userId)
            ->first();
    }

   public function getPublicProfile(int $id): ?User
    {
<<<<<<< HEAD
        return User::query()
            ->where('role', 'coach')
            ->where('id', $id)
            ->with([
                'coachProfile.skills:id,name',
                'coachProfile.certifications:id,coach_id,title,issuer,year',
                'reviews' => function ($q) {
                    $q->latest()->take(10);
                },
              'reviews.trainee:id,full_name',
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
            ->first();
=======
        return User::with([
            'coachProfile.skills',
            'coachProfile.certifications',
        ])->find($id);
>>>>>>> 5749912 (backup before breeze auth migration)
    }

    public function getTraineeDetails(int $id): ?User
    {
        return User::with([
            'userProfile',
        ])->find($id);
    }

    public function updateProfilePhoto(
        User $user,
        UploadedFile $image
    ) {
        try {

            return DB::transaction(function () use ($user, $image) {

                $profile = $user->coachProfile;

                if (!$profile) {
                    return null;
                }

                $photoPath = ImageHelper::update(
                    $image,
                    $profile->profile_photo,
                    'coach_avatar'
                );

                $profile->update([
                    'profile_photo' => $photoPath,
                ]);

                return $user->fresh('coachProfile');
            });

        } catch (Exception $e) {

            Log::error(
                'CoachService Update Profile Photo Error: ' .
                $e->getMessage()
            );

            throw new Exception(
                'فشلت عملية تحديث صورة الملف الشخصي: ' .
                $e->getMessage()
            );
        }
    }

    public function deleteProfilePhoto(User $user)
    {
        try {

            $profile = $user->coachProfile;

            if (!$profile) {
                return null;
            }

            ImageHelper::delete($profile->profile_photo);

            $profile->update([
                'profile_photo' => null,
            ]);

            return $user->fresh('coachProfile');

        } catch (Exception $e) {

            Log::error(
                'CoachService Delete Profile Photo Error: ' .
                $e->getMessage()
            );

            throw new Exception(
                'فشلت عملية حذف صورة الملف الشخصي: ' .
                $e->getMessage()
            );
        }
    }
}