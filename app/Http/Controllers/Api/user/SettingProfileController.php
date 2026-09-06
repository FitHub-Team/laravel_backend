<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfileRequest;
use App\Services\User\ProfileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}


    public function show(Request $request)
    {
        try {
            $profile = $this->profileService->show(
                $request->user()
            );
            if (!$profile) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'تم جلب بيانات الملف الشخصي بنجاح',
                'data' => $profile,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء جلب الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();

            $profile = $this->profileService->update(
                $request->user(),
                $validated
            );

            if (!$profile) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث بيانات الملف الشخصي بنجاح',
                'data' => $profile,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء تحديث الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = $request->user();

            // Store the new profile photo
            // $photoPath = $request->file('profile_photo')->store('user_avatar', 'public');
            $file = $request->file('profile_photo');

            $filename = $file->getClientOriginalName();

            $photoPath = $file->storeAs('user_avatar', $filename, 'public');

            // Update the user's profile photo
            $profile = $this->profileService->updateProfilePhoto($user, $photoPath);


            return response()->json([
                'status' => true,
                'message' => 'تم تحديث صورة الملف الشخصي بنجاح',
                'data' => [
                    'profile_photo' => asset('storage/' . $photoPath),
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء تحديث صورة الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
