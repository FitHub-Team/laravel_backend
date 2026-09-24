<?php

namespace App\Services\User;

use App\Helper\ImageHelper;
use App\Models\User;
use App\Repositories\UserRepository;
// use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;


class ProfileService
{
     public function __construct(
        private  UserRepository $userRepository
    ) {}

    public function show(User $user)
    {
        try {
           return $user->load([
            'userProfile',
            'userProfile.goal',
            'userProfile.activityLevel',
            'userProfile.healthConditions',
            'userProfile.dietaryRestrictions',
            'userProfile.trainingLocation',
        ]);
        } catch (Exception $e) {
            Log::error('ProfileService Show Error: ' . $e->getMessage());

            throw new Exception('فشلت عملية جلب بيانات الملف الشخصي.');
        }
    }

    public function update(User $user, array $data)
    {
        try {
            $profile = $user->userProfile;

            if (!$profile) {
                return null;
            }

            // البيانات التي تخص جدول user_profiles
            $profileData = collect($data)->except([
                'health_condition_ids',
                'dietary_restriction_ids',
            ])->toArray();

            $profile->update($profileData);

            // تحديث الحالات الصحية
            if (array_key_exists('health_condition_ids', $data)) {
                $profile->healthConditions()->sync(
                    $data['health_condition_ids'] ?? []
                );
            }

            // تحديث القيود الغذائية
            if (array_key_exists('dietary_restriction_ids', $data)) {
                $profile->dietaryRestrictions()->sync(
                    $data['dietary_restriction_ids'] ?? []
                );
            }

            return $user->load([
                'userProfile',
                'userProfile.goal',
                'userProfile.activityLevel',
                'userProfile.healthConditions',
                'userProfile.dietaryRestrictions',
                'userProfile.trainingLocation',
            ]);
        } catch (Exception $e) {
            Log::error('ProfileService Update Error: ' . $e->getMessage());

            throw new Exception(
                'فشلت عملية تحديث الملف الشخصي: ' . $e->getMessage()
            );
        }
    }


    public function updateProfilePhoto(User $user, UploadedFile $image)
    {
        try {
            return DB::transaction(function () use ($user, $image) {

                $profile = $user->userProfile;
                //   dd($profile);
                if (!$profile) {
                    return null;
                }
                $photoPath = ImageHelper::update(
                    $image,
                    $profile->profile_photo,
                    'user_avatar'
                );

                // Update profile photo
                $profile->update([
                    'profile_photo' => $photoPath,
                ]);

                return $user->fresh('userProfile');
            });
        } catch (Exception $e) {

            Log::error(
                'ProfileService Update Profile Photo Error: ' . $e->getMessage()
            );

            throw new Exception(
                'فشلت عملية تحديث صورة الملف الشخصي: ' . $e->getMessage()
            );
        }
    }
    public function deleteProfilePhoto(User $user)
    {
        try {
            $profile = $user->userProfile;

            if (!$profile) {
                return null;
            }

            ImageHelper::delete($profile->profile_photo);

            $profile->update([
                'profile_photo' => null,
            ]);

            return $user->fresh('userProfile');
        } catch (Exception $e) {
            Log::error(
                'ProfileService Delete Profile Photo Error: ' . $e->getMessage()
            );

            throw new Exception(
                'فشلت عملية حذف صورة الملف الشخصي: ' . $e->getMessage()
            );
        }
    }
}
