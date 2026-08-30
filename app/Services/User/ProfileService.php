<?php

namespace App\Services\User;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            if (isset($data['profile_photo']) && $data['profile_photo'] instanceof \Illuminate\Http\UploadedFile) {
                if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }
                $data['profile_photo'] = $data['profile_photo']->store('profile_photos', 'public');
            }

            $profile->update($data);

            return $user->load('userProfile');
        } catch (Exception $e) {
            Log::error('ProfileService Update Error: ' . $e->getMessage());
            throw new Exception('فشلت عملية تحديث الملف الشخصي: ' . $e->getMessage());
        }

    }
}
