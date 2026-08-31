<?php

namespace App\Services\User;

use App\Models\User;
// use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ProfileService
{

    public function show(User $user)
    {

        try {
            return $user->load('userProfile');
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

            // 2- update new profile pictuer and delete the old
            // if (isset($data['profile_photo']) && $data['profile_photo'] instanceof \Illuminate\Http\UploadedFile) {
            //     if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
            //         Storage::disk('public')->delete($profile->profile_photo);
            //     }
            //     $data['profile_photo'] = $data['profile_photo']->store('profile_photos', 'public');
            // }

            $profile->update($data);
            return $user->load('userProfile');
        } catch (Exception $e) {
            Log::error('ProfileService Update Error: ' . $e->getMessage());
            throw new Exception('فشلت عملية تحديث الملف الشخصي: ' . $e->getMessage());
        }
    }
    public function updateProfilePhoto(User $user, string $photoPath)
    {
        try {
            return DB::transaction(function () use ($user, $photoPath) {

                $profile = $user->userProfile;

                if (!$profile) {
                    return null;
                }

                // Delete the old profile photo if it exists
                if (
                    $profile->profile_photo &&
                    Storage::disk('public')->exists($profile->profile_photo)
                ) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }

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
}
